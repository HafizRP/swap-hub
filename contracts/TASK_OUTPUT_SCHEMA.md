# TASK OUTPUT SCHEMA — SWAP HUB

This document defines the JSON schema that every agent MUST adhere to in their final response when executing tasks in Swap Hub.

## Schema Version
JSON Schema Draft-07

## Required Fields
| Field | Type | Description |
|-------|------|-------------|
| `task_id` | `string` | Agent identifier (e.g., `backend_agent`, `worker_agent`, `frontend_agent`, `reviewer_agent`, `infra_agent`, `verifier_agent`) |
| `verdict` | `string` | One of: `PASS`, `REJECT`, `BLOCKED` |
| `summary` | `string` | Brief description of work done or defects found |

## Optional Fields
| Field | Type | Description |
|-------|------|-------------|
| `modified_files` | `string[]` | List of files created or modified |
| `feedback_notes` | `string` | Detailed defect descriptions (used by `reviewer_agent`) |
| `reject_targets` | `string[]` | Agent IDs needing revision. Valid values: `backend_agent`, `worker_agent`, `frontend_agent`. Omit or empty array if verdict is `PASS`. |

## Verdict Definitions
| Verdict | Meaning | Used By |
|---------|---------|---------|
| `PASS` | Task completed successfully, all checks pass | All agents |
| `REJECT` | Defects found, targeted agents must revise | `reviewer_agent` only |
| `BLOCKED` | External dependency unavailable, cannot proceed | Any agent |

## Example Output

### Successful Backend Task
```json
{
  "task_id": "backend_agent",
  "verdict": "PASS",
  "summary": "Implemented new project milestone migration, Milestone model, controller, and form request with 100% test coverage.",
  "modified_files": [
    "database/migrations/2026_03_31_000001_create_project_milestones_table.php",
    "app/Models/ProjectMilestone.php",
    "app/Http/Controllers/ProjectMilestoneController.php",
    "app/Http/Requests/StoreMilestoneRequest.php",
    "routes/web.php",
    "tests/Feature/ProjectMilestoneTest.php"
  ],
  "feedback_notes": ""
}
```

### Reviewer Rejection
```json
{
  "task_id": "reviewer_agent",
  "verdict": "REJECT",
  "summary": "Audit failed: missing Pint formatting and unparameterized database query.",
  "modified_files": [],
  "feedback_notes": "CRITICAL: app/Http/Controllers/ProjectMilestoneController.php line 42 has unescaped DB::raw query. Line formatting fails Laravel Pint. Re-run vendor/bin/pint.",
  "reject_targets": ["backend_agent"]
}
```
