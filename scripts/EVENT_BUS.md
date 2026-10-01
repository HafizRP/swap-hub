# EVENT BUS — INTER-AGENT COMMUNICATION PROTOCOL

This document specifies the event bus system used for inter-agent coordination in Swap Hub. The executing LLM or CI system MUST implement this protocol.

## 1. Purpose
Lock-free, file-based inter-agent communication system supporting broadcast events and direct point-to-point messaging.

## 2. Directory Structure
```
bus/
├── events/           # Broadcast events (all agents can read)
│   └── <timestamp>_<EVENT_NAME>.json
└── inboxes/          # Direct messages (per-agent inbox)
    └── <agent_name>/
        └── <timestamp>_<MESSAGE_TYPE>.json
```

## 3. Commands

### 3.1 `emit` — Broadcast an Event
Writes a timestamped JSON file to `bus/events/`.

**Usage**:
```bash
python3 scripts/event_bus.py emit --from <agent> --event <EVENT> --payload '<json>'
```

**Event File Format** (`bus/events/<timestamp_ms>_<EVENT>.json`):
```json
{
  "event": "<EVENT_NAME>",
  "from": "<sender_agent>",
  "timestamp": 1711929600000,
  "payload": { ... }
}
```

### 3.2 `check` — Check if Event Exists
Returns exit code 0 if event found, 1 if not.

**Usage**:
```bash
python3 scripts/event_bus.py check --event <EVENT>
```

### 3.3 `wait` — Block Until Event Arrives
Polls `bus/events/` directory at regular intervals until matching event file appears.

**Usage**:
```bash
python3 scripts/event_bus.py wait --event <EVENT> --timeout 300 --interval 1.0 --after <timestamp_ms>
```

**Parameters**:
| Parameter | Default | Description |
|-----------|---------|-------------|
| `--event` | (required) | Event name to wait for |
| `--timeout` | `300` | Max seconds to wait |
| `--interval` | `1.0` | Polling interval in seconds |
| `--after` | `0` | Only match events with timestamp > this value (epoch ms). Used to skip stale events from previous rounds. |

**Returns**: JSON content of matched event on stdout. Exit code 1 on timeout.

### 3.4 `send` — Direct Message to Agent Inbox
Writes a timestamped JSON file to `bus/inboxes/<recipient>/`.

**Usage**:
```bash
python3 scripts/event_bus.py send --from <agent> --to <target> --type <TYPE> --body '<json>'
```

**Message File Format** (`bus/inboxes/<recipient>/<timestamp_ms>_<TYPE>.json`):
```json
{
  "type": "<MESSAGE_TYPE>",
  "from": "<sender_agent>",
  "to": "<recipient_agent>",
  "timestamp": 1711929600000,
  "body": { ... }
}
```

### 3.5 `inbox` — Read and Optionally Consume Messages
Reads all messages in agent's inbox.

**Usage**:
```bash
python3 scripts/event_bus.py inbox --agent <agent> [--consume]
```

**Flags**:
- `--consume`: Delete messages after reading them. Without this flag, messages are read-only.

**Returns**: JSON array of message objects on stdout.

---

## 4. Event Catalog for Swap Hub

| Event | Emitted By | Payload | Purpose |
|-------|------------|---------|---------|
| `CONTRACT_PUBLISHED` | `backend_agent` | `{ "contract_path": "contracts/API_CONTRACT.md" }` | Unblocks `worker_agent` & `frontend_agent` |
| `BACKEND_READY` | `backend_agent` | `{ "branch": "feat/backend-api", "modified_files": [...] }` | Notifies `reviewer_agent` |
| `WORKER_READY` | `worker_agent` | `{ "branch": "feat/worker-engine", "jobs": [...] }` | Notifies `reviewer_agent` |
| `FRONTEND_READY` | `frontend_agent` | `{ "branch": "feat/frontend-ui", "components": [...] }` | Notifies `reviewer_agent` |
| `CORE_AUDIT_PASSED` | `reviewer_agent` | `{ "verdict": "PASS", "round": 1 }` | Unblocks `infra_agent` |
| `INFRA_READY` | `infra_agent` | `{ "branch": "infra/docker-deploy" }` | Unblocks `verifier_agent` |
| `SYSTEM_VERIFIED` | `verifier_agent` | `{ "verdict": "PASS", "smoke_tests": "100%" }` | Triggers final merge |

## 5. Message Types

| Type | From | To | Purpose |
|------|------|----|---------|
| `REVISION_REQUEST` | `reviewer_agent` | `backend_agent` \| `worker_agent` \| `frontend_agent` | Point-to-point defect details for autonomous self-healing |
