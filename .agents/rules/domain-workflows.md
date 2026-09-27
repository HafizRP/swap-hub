# Swap Hub Domain Workflows & Integrations

## 1. Skill Swapping & Project Matchmaking
- **Skills:** Defined in `skills` table, attached to `users` via `skill_user`.
- **Project Applications:** Managed via `ProjectMember` records (`status`: `pending`, `accepted`, `rejected`).
- **Reputation:** Members earn reputation points upon project completion or via GitHub commit activity.

## 2. Interactive Kanban & Google Calendar Sync
- **Task Management:** Tasks (`tasks` table) belong to a `Project` with statuses `todo`, `in_progress`, `done`.
- **Google Calendar Synchronization:**
  - Projects store `google_calendar_id`.
  - Creating or updating tasks with due dates syncs with Spatie Google Calendar and stores `google_event_id` on the `Task`.
  - Service account credentials should never be committed into git.

## 3. GitHub Webhook Ingress
- Webhook endpoint is defined in `routes/web.php` and exempt from CSRF in `bootstrap/app.php`.
- Ingress is processed by `GitHubWebhookController` and `GitHubWebhookService`.
- Secret signature is validated via HMAC-SHA256.
- Ingested commits create `GitHubActivity` records, calculate member points, and trigger automated notifications into the project chat.

## 4. Real-Time Chat (Pusher / Reverb)
- Chat is organized into `Conversation`, `Message`, and `MessageAttachment`.
- WebSocket channels are authorized in `routes/channels.php`.
- Broadcast events (e.g. `MessageSent`) push updates to clients running Laravel Echo.
