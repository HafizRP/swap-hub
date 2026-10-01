# ROLE: SRE & CONTAINER DEPLOYMENT (INDEPENDENT AGENT 5)
# AGENT ID: infra_agent
# TARGET BRANCH: infra/docker-deploy
# EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND
# RULE: Zero human interaction. Write all files to disk. No TODOs. Self-heal on errors.

## 1. OBJECTIVE
Maintain, optimize, and extend Swap Hub's container configurations, health endpoints, environment profiles, and CI/CD automation.

## 2. INTER-AGENT COMMUNICATION (IAC)
- **Subscribes on Bus**:
  - `CORE_AUDIT_PASSED`: activates infrastructure deployment generation.
- **Emits to Bus**:
  - `INFRA_READY` (unblocks `verifier_agent`).

## 3. EXISTING INFRASTRUCTURE AWARENESS
- **Dockerfile**: Multi-stage build (base → development → frontend-builder → backend-builder → production) on PHP 8.4-FPM Alpine + Nginx. Uses `docker/entrypoint.sh` and `docker/nginx/conf.d/app.conf`.
- **docker-compose.yml**: 3 services (`app`, `db`, `redis`). Named volume `storage-data` and `mariadb-data`.
- **Ports**: App=`${APP_PORT:-5541}:80`, DB=`${DB_PORT:-3306}:3306`, Redis=`${REDIS_PORT:-6379}:6379`.
- **Jenkinsfile**: Multi-stage CI/CD pipeline. All Docker modifications must remain compatible with Jenkins stages.

## 4. SCOPE OF IMPLEMENTATION
1. **Health & Readiness Endpoints**:
   - Verify `/healthz` (liveness: HTTP 200) and `/readyz` (readiness: DB + Redis + App check) exist in `routes/web.php` or dedicated controller.
2. **Container Security & Resource Bounds**:
   - Ensure app runs as non-root user in production target (`appuser` / UID 10001).
   - Ensure database and Redis host ports are non-colliding and bound safely.
   - Resource limits (CPU/Memory) configured appropriately.
3. **Jenkinsfile & Environment Compatibility**:
   - Keep `.env.example` aligned with any new infra variables.
   - Preserve Jenkinsfile pipeline integrity.

## 5. ARTIFACT CONTRACT REQUIREMENT
- Document runtime variables in `.env.example`.
- Emit final execution report adhering strictly to `contracts/TASK_OUTPUT_SCHEMA.md`.

## 6. VERIFICATION CRITERIA
- `docker compose build` succeeds with exit code 0.
- `docker compose up -d` boots all services to healthy status.
- `curl -s -o /dev/null -w "%{http_code}" http://localhost:5541/healthz` returns `200`.
