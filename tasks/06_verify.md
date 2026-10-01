# ROLE: QA & RELEASE VERIFICATION (INDEPENDENT AGENT 6)
# AGENT ID: verifier_agent
# TARGET BRANCH: main
# EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND
# RULE: Zero human interaction. Run all verifications autonomously. Self-heal on errors.

## 1. OBJECTIVE
Execute full end-to-end integration verification, smoke tests, database migration dry-runs, and handover checklist for Swap Hub.

## 2. INTER-AGENT COMMUNICATION (IAC)
- **Subscribes on Bus**:
  - `INFRA_READY`: activates container launch and smoke verification.
- **Emits to Bus**:
  - `SYSTEM_VERIFIED`: marks total completion of multi-agent pipeline and triggers branch auto-merge.

## 3. SCOPE OF IMPLEMENTATION
1. Create and execute an automated verification script `verify.sh`:
   ```bash
   #!/usr/bin/env bash
   set -euo pipefail
   BASE_URL="http://127.0.0.1:${APP_PORT:-5541}"
   PASS=0; FAIL=0

   echo "==> Running Swap Hub E2E Verification..."
   # 1. Container health
   # 2. GET /healthz → 200 OK
   # 3. GET /readyz → 200 OK
   # 4. Landing page HTTP status
   # 5. Database connectivity & migrations check
   # 6. Redis ping
   # 7. Artisan test suite pass
   # 8. Pint style clean pass
   ```
2. Run database migration verification:
   ```bash
   docker compose exec app php artisan migrate --pretend
   ```
3. Run container test suite:
   ```bash
   docker compose exec app php artisan test
   ```
4. Confirm zero open port leaks outside intended bindings.

## 4. ARTIFACT CONTRACT REQUIREMENT
- Write verification audit log to `artifacts/verification_report.md`.
- Emit verdict `"PASS"` or `"BLOCKED"` adhering strictly to `contracts/TASK_OUTPUT_SCHEMA.md`.

## 5. VERIFICATION CRITERIA
- 100% of smoke test assertions pass.
- Zero PHP fatals, unhandled exceptions, or database connection errors in `docker compose logs`.
- App ready for production deployment.
