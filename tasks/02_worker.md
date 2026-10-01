# ROLE: BACKGROUND WORKER & DATA PIPELINE (INDEPENDENT AGENT 2)
# AGENT ID: worker_agent
# TARGET BRANCH: feat/worker-engine
# EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND
# RULE: Zero human interaction. Write all files to disk. No TODOs. Self-heal on errors.

## 1. OBJECTIVE
Build, configure, and maintain background jobs, event listeners, queues, and external service integrations for Swap Hub.

## 2. INTER-AGENT COMMUNICATION (IAC)
- **Subscribes on Bus**:
  - `CONTRACT_PUBLISHED`: wakes up worker agent to ingest `contracts/API_CONTRACT.md`.
- **Emits to Bus**:
  - `WORKER_READY` (notifies `reviewer_agent`).
- **Listens from Inbox (`bus/inboxes/worker_agent/`)**:
  - `REVISION_REQUEST` from `reviewer_agent`: receives feedback for defect remediation.

## 3. CODEBASE CONTEXT & INTEGRATIONS (DO NOT BREAK)
- **GitHub Webhook Pipeline**: `GitHubWebhookController` + `GitHubWebhookService` — HMAC-SHA256 verified, logs `GitHubActivity`, increments gamification points, triggers chat updates. CSRF exempt in `bootstrap/app.php`.
- **Google Calendar Integration**: Spatie `laravel-google-calendar` — `CreateProjectGoogleCalendar`, `AddMemberToProjectCalendar`, `CreateGoogleCalendarEvent` jobs.
- **Real-Time Broadcasting**: Pusher / Laravel Reverb via `MessageSent` event. Channel authorizations in `routes/channels.php`.
- **Mail Notification Classes**: `app/Mail/` (`WelcomeEmail`, `ProjectMemberAdded`, `MemberValidated`, `NewMessageNotification`).
- **Queue Engine**: `QUEUE_CONNECTION=database` (or Redis). Processed via `php artisan queue:listen` or `queue:work`.

## 4. SCOPE OF IMPLEMENTATION
1. Create new queued Jobs in `app/Jobs/` with explicit `$tries = 3` and `$timeout = 30`.
2. Ensure all jobs implement idempotency to safely tolerate worker restarts.
3. Configure dual-engine resilience for external APIs (Google Calendar, GitHub): gracefully degrade when third-party services fail or rate-limit.
4. Schedule periodic jobs in `routes/console.php` with appropriate cadences.

## 5. ARTIFACT CONTRACT REQUIREMENT
- Document worker configuration in `.env.example`.
- Emit final execution report adhering strictly to `contracts/TASK_OUTPUT_SCHEMA.md`.

## 6. VERIFICATION CRITERIA
- `docker compose exec app php artisan queue:work --once` executes cleanly without hanging.
- All worker and job tests in `tests/Feature/` pass with exit code 0.
- `docker compose exec app php vendor/bin/pint --test` passes with zero violations.
