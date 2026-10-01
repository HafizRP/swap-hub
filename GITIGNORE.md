# GITIGNORE SPECIFICATION — SWAP HUB MULTI-AGENT RUNTIME

This document defines the `.gitignore` rules for agent runtime artifacts in Swap Hub.

## Ignored Paths

### Agent Runtime & Communication Bus
```
bus/
.worktrees/
*.tmp
*.log
```

### State & Logs
```
state/logs/*
!state/logs/.gitkeep
state/.status.lock
state/.status.lock.d
```

### Generated Artifacts
```
artifacts/*
!artifacts/.gitkeep
```
