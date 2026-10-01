# [EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND]
# INDEPENDENT MULTI-AGENT ARCHITECTURE & EVENT BUS SPECIFICATION — SWAP HUB

## 0. Background Execution Mandate
This entire pipeline runs **unattended in the background** with ZERO human intervention:
1. **No Questions**: Never ask for clarification, confirmation, or approval. Make all decisions autonomously.
2. **No Halting**: Never pause, wait for input, or present interactive prompts. If ambiguous, pick the most reasonable default and proceed.
3. **No Interactive I/O**: No `read`, `select`, `confirm`, or any stdin-blocking operation. All decisions are deterministic.
4. **Self-Healing**: If a build fails, test fails, or dependency is missing — diagnose and fix it autonomously. Retry up to `MAX_RETRIES` before marking as `FAILED`.
5. **Write Directly to Disk**: Create and modify real production files directly in the working directory. No dry-runs.
6. **Complete Deliverables**: Every file must be 100% production-ready. No `TODO` comments, empty stubs, mock functions, or placeholder content.
7. **Silent Progress**: Log structured status updates to `state/` and emit bus events. Do not expect a human to read stdout in real-time.

## 1. System Philosophy: Independent Autonomous Agents
1. **No Hierarchical Sub-Agents**: Every agent is an autonomous, peer-level process running concurrently.
2. **True Parallel Execution**: All agents spawn simultaneously at $T=0$.
3. **Asynchronous Coordination**: Agents synchronize state via the shared Event & Message Bus (`bus/`).
4. **Git Worktree Isolation**: Builder agents operate in dedicated `git worktree` directories (`.worktrees/`), enabling safe parallel branch work without checkout conflicts.
5. **Quality Feedback Loop**: Independent Reviewer Agent audits work in multi-pass rounds and sends targeted revision messages to specific peer inboxes.
6. **Bounded Retries**: All agents enforce `MAX_RETRIES` (default: 3) to prevent infinite loops.
7. **Auto-Merge**: Upon `SYSTEM_VERIFIED`, all feature branches are automatically merged into `main`.

## 2. Independent Agent Fleet for Swap Hub
Each agent uses whatever LLM model and provider is available in the executing environment. No provider or model configuration is needed — the host platform (Copilot, Hermes, OpenCode, Claude CLI, etc.) handles model routing automatically.

### Agent Fleet
- **`backend_agent`**: Autonomous Schema & API Architect.
  - Branch: `feat/backend-api`
  - Starts: $T=0$ immediately.
  - Scope: Eloquent models, migrations, controllers, FormRequests, services in `app/`.
  - Emits: `CONTRACT_PUBLISHED`, `BACKEND_READY`.
  - Ingests: Revision messages from `reviewer_agent`.
- **`worker_agent`**: Autonomous Background & Queue Worker.
  - Branch: `feat/worker-engine`
  - Starts: $T=0$ in parallel. Waits for `CONTRACT_PUBLISHED`.
  - Scope: Queued jobs (`app/Jobs/`), event listeners, GitHub webhook & Google Calendar sync.
  - Emits: `WORKER_READY`.
  - Ingests: Revision messages from `reviewer_agent`.
- **`frontend_agent`**: Autonomous Client & Admin UI Engineer.
  - Branch: `feat/frontend-ui`
  - Starts: $T=0$ in parallel. Waits for `CONTRACT_PUBLISHED`.
  - Scope: Blade views (`resources/views/`), Livewire 3 components (`app/Livewire/`), Tailwind CSS, Alpine.js.
  - Emits: `FRONTEND_READY`.
  - Ingests: Revision messages from `reviewer_agent`.
- **`reviewer_agent`**: Autonomous Code & Security Auditor.
  - Branch: `main`
  - Starts: $T=0$ in parallel. Listens for `*_READY` events.
  - Multi-Pass Audit: Loops up to `MAX_RETRIES` rounds. Runs `pint --test`, `artisan test`, OWASP security checks.
  - Targeted Messaging: Sends `REVISION_REQUEST` only to agents with defects (via `reject_targets` field).
  - Emits: `CORE_AUDIT_PASSED` when backend, worker, and frontend are approved.
- **`infra_agent`**: Autonomous SRE & Container Engineer.
  - Branch: `infra/docker-deploy`
  - Starts: $T=0$ in parallel. Waits for `CORE_AUDIT_PASSED`.
  - Scope: Multi-stage Dockerfile, `docker-compose.yml`, health probes (`/healthz`, `/readyz`), Jenkinsfile.
  - Emits: `INFRA_READY`.
- **`verifier_agent`**: Autonomous QA & Integration Verifier.
  - Branch: `main`
  - Starts: $T=0$ in parallel. Waits for `INFRA_READY`.
  - Scope: End-to-end container health, smoke assertions, migration dry-runs, handover report.
  - Emits: `SYSTEM_VERIFIED`.

## 3. Inter-Agent Communication (IAC) Protocol
Communication occurs lock-free via the Event Bus protocol (see `scripts/EVENT_BUS.md`):
1. **Broadcast Events** (`bus/events/<timestamp>_<EVENT>.json`):
   - Emit: `emit --from <agent> --event <EVENT> --payload '<json>'`
   - Wait: `wait --event <EVENT> --timeout 300`
2. **Direct Point-to-Point Messaging** (`bus/inboxes/<recipient>/<timestamp>_<TYPE>.json`):
   - Send: `send --from <agent> --to <target> --type REVISION_REQUEST --body '<json>'`
   - Read: `inbox --agent <agent> --consume`
3. **Contracts on Disk**:
   - `contracts/API_CONTRACT.md`: Shared schema for backend, worker, and frontend.
   - `contracts/TASK_OUTPUT_SCHEMA.md`: Standardized completion report schema (includes `reject_targets` for targeted reviewer feedback).

## 4. Prompt Architecture
Each agent prompt is split into **system** and **user** messages for maximum model compatibility:
- **System message** (`_system.tmp`): Role definition, mandates, code conventions, output schema. Persistent across retries.
- **User message** (`_prompt.tmp`): Data contracts, working directory, peer context, task specification. Changes per execution.

## 5. LLM Dispatch
Each agent delegates LLM calls to the host platform. The executing environment (Copilot, Hermes, OpenCode, Claude CLI, or any LLM-powered tool) handles model selection, API routing, and authentication transparently. No dispatch configuration is required in this template.

## 6. Git Worktree Isolation
Builder agents operate in isolated working directories to prevent file collisions:
- `backend_agent`: `.worktrees/backend/` on branch `feat/backend-api`
- `worker_agent`: `.worktrees/worker/` on branch `feat/worker-engine`
- `frontend_agent`: `.worktrees/frontend/` on branch `feat/frontend-ui`
- `infra_agent`: `.worktrees/infra/` on branch `infra/docker-deploy`
- `reviewer_agent` & `verifier_agent`: root repository on `main`

Worktrees are created during initialization and cleaned up upon pipeline completion.

## 7. Retry Logic & Circuit Breaker
- Every agent is bounded by `MAX_RETRIES` (default: 3).
- If an agent fails to produce a valid response after `MAX_RETRIES`, its status is set to `FAILED`.
- The pipeline terminates with status `BLOCKED` if any agent exhausts all retries.
- Reviewer agent can reject builder agents up to `MAX_RETRIES` rounds. If defects remain after round 3, the reviewer marks the verdict as `EXHAUSTED` or `APPROVED_WITH_WARNING`.

## 8. Event Filtering with `--after`
To prevent agents from reacting to stale events from previous rounds:
- The event bus `wait` command accepts an `--after <timestamp>` parameter.
- Agents record the timestamp of their last processed event and pass it to subsequent `wait` calls.
- Stale events with timestamps $\le$ the `--after` value are skipped.

## 9. File Manifest
```
ORCHESTRATOR.md               # Architecture specification (this file)
RUNNER.md                     # Orchestration runner specification
INIT.md                       # Project initializer specification
GITIGNORE.md                  # Gitignore rules
mandates/
  hard_rules.md               # System invariants (zero tolerance)
  code_style.md               # Architectural & Laravel conventions
contracts/
  API_CONTRACT.md             # Shared Swap Hub API contract
  TASK_OUTPUT_SCHEMA.md       # Standardized task output schema
scripts/
  EVENT_BUS.md                # Event bus specification
  EXTRACT_JSON.md             # JSON extraction specification
state/
  STATUS_SCHEMA.md            # Pipeline state schema
  logs/                       # Per-agent execution logs
artifacts/                    # Handover reports and deliverables
tasks/
  01_backend.md               # Backend Architect role spec
  02_worker.md                # Worker Engineer role spec
  03_frontend.md              # Frontend Engineer role spec
  04_reviewer.md              # Reviewer & Auditor role spec
  05_infra.md                 # SRE & Container Deployment role spec
  06_verify.md                # QA & Verification role spec
```
