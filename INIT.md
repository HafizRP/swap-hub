# [EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND]
# PROJECT INITIALIZER SPECIFICATION — SWAP HUB

This document describes the project initialization process for Swap Hub. Runs fully unattended — no interactive prompts.

## 1. Purpose
Prepares the Swap Hub workspace, resets runtime state, and initializes the worktree directories for autonomous pipeline execution.

## 2. Initialization Steps

### Step 1: Safety Check
If `state/status.json` has status `IN_PROGRESS` or `COMPLETED`:
- Log warning: `[INIT_RESET] Pipeline was '${STATUS}'. Resetting state for re-initialization.`
- Proceed automatically without prompting. Background mode — no confirmation required.

### Step 2: Reset State
Write clean `state/status.json`:
```json
{
  "status": "NOT_STARTED",
  "started_at": null,
  "completed_at": null,
  "total_review_rounds": 0,
  "agents": {
    "backend_agent": { "status": "PENDING", "branch": "feat/backend-api", "retry_count": 0, "last_updated": null, "verdict": null },
    "worker_agent": { "status": "PENDING", "branch": "feat/worker-engine", "retry_count": 0, "last_updated": null, "verdict": null },
    "frontend_agent": { "status": "PENDING", "branch": "feat/frontend-ui", "retry_count": 0, "last_updated": null, "verdict": null },
    "reviewer_agent": { "status": "PENDING", "branch": "main", "retry_count": 0, "last_updated": null, "verdict": null },
    "infra_agent": { "status": "PENDING", "branch": "infra/docker-deploy", "retry_count": 0, "last_updated": null, "verdict": null },
    "verifier_agent": { "status": "PENDING", "branch": "main", "retry_count": 0, "last_updated": null, "verdict": null }
  },
  "merge_results": {},
  "pipeline_events": []
}
```

### Step 3: Clean Runtime Artifacts
- Remove `bus/`, `.worktrees/`
- Remove all `*.log`, `*.tmp`, `*.json` from `state/logs/`
- Preserve `state/logs/.gitkeep`
- Clear `artifacts/`, preserve `artifacts/.gitkeep`
- Recreate `bus/events/` and `bus/inboxes/`

### Step 4: Ensure Git Isolation
Ensure git branches exist for worktrees:
- `feat/backend-api`
- `feat/worker-engine`
- `feat/frontend-ui`
- `infra/docker-deploy`
