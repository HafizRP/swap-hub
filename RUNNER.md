# [EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND]
# RUNNER SPECIFICATION — MULTI-AGENT ORCHESTRATION — SWAP HUB

This document contains the complete runner specification for Swap Hub. The executing LLM or CI system MUST run this pipeline fully in the background without any human interaction.

## 0. Background Execution Rules
1. **Zero Human Interaction**: No prompts, no confirmations, no interactive input. All decisions are autonomous.
2. **Silent Operation**: All progress tracked via `state/status.json` and event bus files. No reliance on stdout monitoring.
3. **Self-Healing**: On failure, retry autonomously up to `MAX_RETRIES`. Only mark `FAILED` after exhausting all retries.
4. **Crash Recovery**: If the pipeline is interrupted, it can be re-run safely — idempotent initialization cleans stale state.
5. **Docker Execution Wrapper**: Host PHP may differ; all PHP, Artisan, Pint, and test commands MUST execute inside Docker (`docker compose exec app ...`).

## 1. Purpose
Entry point that spawns all 6 independent agents concurrently, manages event bus coordination, git worktree isolation, LLM dispatch, and auto-merge upon `SYSTEM_VERIFIED`.

## 2. Prerequisites
- `jq` (JSON processor)
- `python3` (for event bus)
- `git` (for worktree isolation)
- `docker` & `docker compose` (runtime container stack)

## 3. Configuration Variables

| Variable | Default | Purpose |
|----------|---------|---------|
| `MAX_RETRIES` | `3` | Max builder retry rounds |
| `AGENT_TIMEOUT` | `600` | Wall-clock seconds before builder gives up |
| `APP_PORT` | `5541` | Swap Hub HTTP port |
| `DB_PORT` | `3306` | MariaDB port |
| `REDIS_PORT` | `6379` | Redis port |

## 4. Portable File Locking
Uses `mkdir`-based atomic lock (POSIX-guaranteed, works on macOS + Linux without `flock`):
- `acquire_lock <lock_dir>`: Spin-wait up to 10 iterations (0.1s each). On timeout, force-break and proceed.
- `release_lock <lock_dir>`: `rmdir` the lock directory.
- Lock directory: `state/.status.lock.d`

## 5. Initialization Sequence
1. Clean previous runtime state: `rm -rf bus/ .worktrees/`
2. Create directories: `bus/events/`, `bus/inboxes/`, `state/logs/`, `contracts/`, `artifacts/`, `.worktrees/`
3. Initialize `state/status.json` with all 6 agents in `PENDING` state.
4. Register `trap cleanup SIGINT SIGTERM EXIT` to kill child processes and prune worktrees.

## 6. Git Worktree Setup
```
setup_worktree <branch_name>
```
- Creates branch from `HEAD` if not exists.
- Runs `git worktree add .worktrees/<branch_slug> <branch>`.
- Sets `AGENT_WORKDIR` to the worktree path.
- Falls back to project root if not in a git repo.

## 7. State Management Functions

### `update_agent_status <agent_name> <status> [verdict] [retry_count]`
Atomically updates `state/status.json` via `acquire_lock` + `jq` + temp file + `mv`.

### `append_pipeline_event <event_name> <agent_name> [detail]`
Appends to `pipeline_events[]` array in `state/status.json`. PID-scoped temp files (`*.evt.${agent}.$$.tmp`).

### `record_merge_result <branch> <status> [detail]`
Records merge outcome per branch in `merge_results{}` object.

## 8. Task Execution — `execute_agent_task`
```
execute_agent_task <agent_name> <spec_file> [extra_context] [work_dir]
```

### Prompt Architecture (System + User Split)
- **System message**: Role definition + `mandates/hard_rules.md` + `mandates/code_style.md` + output schema. Persistent across retries.
- **User message**: API contract + working directory + peer inbox context + task specification. Changes per execution.

The host platform (Copilot, Hermes, OpenCode, Claude CLI, etc.) handles model selection and API routing automatically.

### Output Processing
- Raw LLM output saved to `state/logs/<agent>_raw.log`
- Parsed via extraction logic (see `scripts/EXTRACT_JSON.md`) to `state/logs/<agent>_output.json`
- Fallback: synthetic pass or retry object.

## 9. Agent Lifecycles

### Builder Agents (`backend_agent`, `worker_agent`, `frontend_agent`)
1. Setup git worktree for isolated branch work.
2. Execute task via LLM dispatch chain.
3. Emit `*_READY` event to bus.
4. Enter retry loop (max `MAX_RETRIES`):
   - Check if `CORE_AUDIT_PASSED` → exit success.
   - Consume inbox for `REVISION_REQUEST`.
   - If revision found: re-execute task with reviewer feedback as extra context.
   - Re-emit `*_READY` event.
   - Wall-clock timeout check (`AGENT_TIMEOUT`).
5. On max retries exhausted: exit with `FAILED` status.

### Reviewer Agent (`reviewer_agent`)
1. Wait for `BACKEND_READY`, `WORKER_READY`, `FRONTEND_READY` (240s each).
2. Enter multi-pass audit loop (max `MAX_RETRIES` rounds):
   - Execute review task via LLM dispatch.
   - Run `docker compose exec app php vendor/bin/pint --test`.
   - Run `docker compose exec app php artisan test`.
   - If PASS: emit `CORE_AUDIT_PASSED` → exit success.
   - If REJECT: parse `reject_targets[]`, send `REVISION_REQUEST` to target inboxes, record timestamp, wait for updated `*_READY` events.
3. On max rounds exhausted: force-approve with `APPROVED_WITH_WARNING`.

### Infra Agent (`infra_agent`)
1. Wait for `CORE_AUDIT_PASSED` (300s).
2. Setup worktree `infra/docker-deploy`.
3. Verify multi-stage Dockerfile and `docker-compose.yml`.
4. Emit `INFRA_READY`.

### Verifier Agent (`verifier_agent`)
1. Wait for `INFRA_READY` (300s).
2. Run end-to-end container verification (`verify.sh`).
3. Emit `SYSTEM_VERIFIED`.

## 10. Concurrent Spawn & Merge Phase

All 6 agents spawn simultaneously:
```bash
agent_backend &
agent_worker &
agent_frontend &
agent_reviewer &
agent_infra &
agent_verifier &
```

### Auto-Merge Phase
Upon `SYSTEM_VERIFIED`:
1. Remove all worktrees and prune.
2. Checkout `main`.
3. Merge each feature branch with `--no-ff`:
   - `feat/backend-api`
   - `feat/worker-engine`
   - `feat/frontend-ui`
   - `infra/docker-deploy`
4. On conflict: abort merge, record `CONFLICT`, continue with next branch.
5. On success: record `SUCCESS`.
6. Emit `PIPELINE_COMPLETED`.

## 11. Cleanup Handler
Triggered on `SIGINT`, `SIGTERM`, or `EXIT`:
1. Kill all child PIDs.
2. Remove temp files.
3. Remove lock directory.
4. Remove and prune git worktrees.
