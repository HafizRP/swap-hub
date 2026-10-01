# PIPELINE STATE SCHEMA — SWAP HUB

This document defines the pipeline state structure stored in `state/status.json` for Swap Hub.

## Initial State
The pipeline starts in `NOT_STARTED` status with all agents in `PENDING` state.

## State Structure

```json
{
  "status": "NOT_STARTED | IN_PROGRESS | COMPLETED | BLOCKED",
  "started_at": "ISO 8601 timestamp | null",
  "completed_at": "ISO 8601 timestamp | null",
  "total_review_rounds": 0,
  "agents": {
    "backend_agent": {
      "status": "PENDING | RUNNING | WAITING_REVIEW | REVISING | COMPLETED | FAILED",
      "branch": "feat/backend-api",
      "retry_count": 0,
      "last_updated": null,
      "verdict": null
    },
    "worker_agent": {
      "status": "PENDING | RUNNING | WAITING_REVIEW | REVISING | COMPLETED | FAILED",
      "branch": "feat/worker-engine",
      "retry_count": 0,
      "last_updated": null,
      "verdict": null
    },
    "frontend_agent": {
      "status": "PENDING | RUNNING | WAITING_REVIEW | REVISING | COMPLETED | FAILED",
      "branch": "feat/frontend-ui",
      "retry_count": 0,
      "last_updated": null,
      "verdict": null
    },
    "reviewer_agent": {
      "status": "PENDING | RUNNING | WAITING_REVIEW | REVISING | COMPLETED | FAILED",
      "branch": "main",
      "retry_count": 0,
      "last_updated": null,
      "verdict": null
    },
    "infra_agent": {
      "status": "PENDING | RUNNING | WAITING_REVIEW | REVISING | COMPLETED | FAILED",
      "branch": "infra/docker-deploy",
      "retry_count": 0,
      "last_updated": null,
      "verdict": null
    },
    "verifier_agent": {
      "status": "PENDING | RUNNING | WAITING_REVIEW | REVISING | COMPLETED | FAILED",
      "branch": "main",
      "retry_count": 0,
      "last_updated": null,
      "verdict": null
    }
  },
  "merge_results": {
    "feat/backend-api": { "status": "PENDING", "detail": "" },
    "feat/worker-engine": { "status": "PENDING", "detail": "" },
    "feat/frontend-ui": { "status": "PENDING", "detail": "" },
    "infra/docker-deploy": { "status": "PENDING", "detail": "" }
  },
  "pipeline_events": []
}
```

## Agent Registry

| Agent | Branch | Initial Status |
|-------|--------|---------------|
| `backend_agent` | `feat/backend-api` | `PENDING` |
| `worker_agent` | `feat/worker-engine` | `PENDING` |
| `frontend_agent` | `feat/frontend-ui` | `PENDING` |
| `reviewer_agent` | `main` | `PENDING` |
| `infra_agent` | `infra/docker-deploy` | `PENDING` |
| `verifier_agent` | `main` | `PENDING` |

## Status Transitions
```
PENDING → RUNNING → WAITING_REVIEW → REVISING → WAITING_REVIEW → ... → COMPLETED
                                                                     → FAILED
```
