# API & ARCHITECTURE CONTRACT — SWAP HUB

This document is the shared contract consumed by all agents working on the Swap Hub codebase.

## Project Metadata
- **Project**: `swap-hub`
- **Framework**: Laravel 12.x on PHP 8.4
- **Database**: MariaDB 10.11 / MySQL
- **Frontend**: Blade + Livewire 3 + Tailwind CSS + Alpine.js
- **Version**: 1.0.0

## Ports
| Service | Host Port | Container Port | Purpose |
|---------|-----------|----------------|---------|
| `app` | `5541` | `80` | Nginx + PHP 8.4-FPM application server |
| `db` | `3306` | `3306` | MariaDB 10.11 database server |
| `redis` | `6379` | `6379` | Redis cache and queue backend |
| `reverb` | `8080` | `8080` | Laravel Reverb WebSocket broadcaster |

---

## Core Database Schema

### `users`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Unique user ID |
| `name` | `VARCHAR(255)` | NOT NULL | Full name |
| `email` | `VARCHAR(255)` | UNIQUE, NOT NULL | Account email |
| `password` | `VARCHAR(255)` | NULLABLE | Password hash (null for OAuth-only users) |
| `role_id` | `BIGINT UNSIGNED` | FK → `roles.id`, DEFAULT 2 | Role reference (1=Admin, 2=Student) |
| `avatar` | `VARCHAR(255)` | NULLABLE | Avatar path or URL |
| `github_id` | `VARCHAR(255)` | NULLABLE | GitHub OAuth identifier |
| `google_id` | `VARCHAR(255)` | NULLABLE | Google OAuth identifier |
| `email_verified_at` | `TIMESTAMP` | NULLABLE | Verification timestamp |
| `created_at` | `TIMESTAMP` | NULLABLE | Creation timestamp |
| `updated_at` | `TIMESTAMP` | NULLABLE | Last update timestamp |

### `skills`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Skill identifier |
| `name` | `VARCHAR(255)` | UNIQUE, NOT NULL | Skill name (e.g. PHP, React, UI/UX) |
| `category` | `VARCHAR(100)` | NULLABLE | Category (Frontend, Backend, Design) |

### `user_skills` (Pivot)
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `user_id` | `BIGINT UNSIGNED` | FK → `users.id` ON DELETE CASCADE | User reference |
| `skill_id` | `BIGINT UNSIGNED` | FK → `skills.id` ON DELETE CASCADE | Skill reference |
| `proficiency` | `ENUM` | 'beginner', 'intermediate', 'advanced' | Skill level |

### `projects`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Project ID |
| `user_id` | `BIGINT UNSIGNED` | FK → `users.id` | Project owner/creator |
| `title` | `VARCHAR(255)` | NOT NULL | Project title |
| `slug` | `VARCHAR(255)` | UNIQUE, NOT NULL | URL-friendly slug |
| `description` | `TEXT` | NOT NULL | Project overview |
| `status` | `ENUM` | 'open', 'in_progress', 'completed', 'archived' | Project lifecycle status |
| `github_repo` | `VARCHAR(255)` | NULLABLE | Connected GitHub repository |
| `google_calendar_id` | `VARCHAR(255)` | NULLABLE | Associated Google Calendar ID |
| `created_at` | `TIMESTAMP` | NULLABLE | Creation timestamp |
| `updated_at` | `TIMESTAMP` | NULLABLE | Update timestamp |

### `project_members`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Membership record ID |
| `project_id` | `BIGINT UNSIGNED` | FK → `projects.id` ON DELETE CASCADE | Project reference |
| `user_id` | `BIGINT UNSIGNED` | FK → `users.id` ON DELETE CASCADE | Member user reference |
| `role` | `VARCHAR(100)` | DEFAULT 'contributor' | Role inside the project |
| `status` | `ENUM` | 'pending', 'accepted', 'rejected' | Application / invite status |
| `validated_at` | `TIMESTAMP` | NULLABLE | Date validated by owner |

### `tasks`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Task ID |
| `project_id` | `BIGINT UNSIGNED` | FK → `projects.id` ON DELETE CASCADE | Project reference |
| `assigned_to` | `BIGINT UNSIGNED` | FK → `users.id` NULLABLE | Assignee |
| `title` | `VARCHAR(255)` | NOT NULL | Task title |
| `description` | `TEXT` | NULLABLE | Details |
| `status` | `ENUM` | 'todo', 'in_progress', 'review', 'done' | Kanban column status |
| `due_date` | `DATE` | NULLABLE | Target completion date |
| `google_event_id` | `VARCHAR(255)` | NULLABLE | Linked Google Calendar event |

### `conversations` & `messages`
| Table | Columns | Description |
|-------|---------|-------------|
| `conversations` | `id`, `type` ('direct', 'group'), `name`, `created_at` | Chat room |
| `messages` | `id`, `conversation_id`, `user_id`, `body`, `created_at` | Text message |
| `message_attachments` | `id`, `message_id`, `file_path`, `file_name`, `file_size` | Attachment file |

### `github_activities`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Activity ID |
| `user_id` | `BIGINT UNSIGNED` | FK → `users.id` | Developer user |
| `project_id` | `BIGINT UNSIGNED` | FK → `projects.id` | Target project |
| `commit_hash` | `VARCHAR(40)` | NOT NULL | Commit SHA |
| `message` | `TEXT` | NOT NULL | Commit log message |
| `points` | `INTEGER` | DEFAULT 10 | Gamification points rewarded |

---

## Route Catalog

### Public Routes
| Method | Path | Handler / View | Description |
|--------|------|----------------|-------------|
| `GET` | `/` | `view('welcome')` | Public landing page |
| `GET` | `/healthz` | Health Controller / Route | Liveness check (200 OK) |
| `GET` | `/readyz` | Readiness Controller / Route | Readiness check (DB, Redis, App) |
| `POST` | `/webhooks/github` | `GitHubWebhookController@handle` | Ingests GitHub repo push events (CSRF exempt) |

### OAuth Routes
| Method | Path | Description |
|--------|------|-------------|
| `GET` | `/auth/github` | Redirects to GitHub OAuth |
| `GET` | `/auth/github/callback` | Handles GitHub OAuth callback |
| `GET` | `/auth/google` | Redirects to Google OAuth |
| `GET` | `/auth/google/callback` | Handles Google OAuth callback |

### Authenticated Student Routes (`middleware: ['auth', 'verified']`)
| Method | Path | Name | Description |
|--------|------|------|-------------|
| `GET` | `/dashboard` | `dashboard` | User dashboard overview |
| `GET` | `/profile` | `profile.edit` | Profile editing form |
| `PATCH` | `/profile` | `profile.update` | Update profile information |
| `GET` | `/profile/{user}` | `profile.show` | Public student profile |
| `POST` | `/profile/skills` | `profile.skills.add` | Add skill to profile |
| `DELETE` | `/profile/skills/{skill}` | `profile.skills.remove` | Remove skill |
| `GET` | `/profile/{user}/resume` | `profile.resume` | Download student PDF resume |
| `GET` | `/projects` | `projects.index` | Browse and filter projects |
| `POST` | `/projects` | `projects.store` | Create project |
| `GET` | `/projects/{project}` | `projects.show` | Project detail page |
| `GET` | `/projects/{project}/workspace` | `projects.workspace` | Project Kanban & collaboration workspace |
| `POST` | `/projects/{project}/members` | `projects.members.add` | Invite or add member |
| `POST` | `/projects/{project}/apply` | `projects.apply` | Apply to join project |
| `POST` | `/projects/{project}/applications/{user}/accept` | `projects.applications.accept` | Accept application |
| `POST` | `/projects/{project}/applications/{user}/reject` | `projects.applications.reject` | Reject application |
| `GET` | `/chat/{conversation?}` | `chat` | Livewire full SPA chat page |
| `POST` | `/chat/direct/{user}` | `chat.direct` | Open or create direct message |

### Admin Backoffice Routes (`middleware: ['auth', 'admin']`)
| Method | Path | Name | Description |
|--------|------|------|-------------|
| `GET` | `/admin` | `admin.dashboard` | Admin analytics dashboard |
| `GET` | `/admin/users` | `admin.users.index` | User management table |
| `POST` | `/admin/users/{user}/toggle-role` | `admin.users.toggle-role` | Promote/demote student or admin |
| `GET` | `/admin/projects` | `admin.projects.index` | Project moderation table |
| `POST` | `/admin/projects/{project}/archive` | `admin.projects.archive` | Administrative project archive |
| `GET` | `/admin/system-health` | `admin.health.index` | Livewire system health diagnostics |

---

## Environment Variables
| Variable | Required | Default in Docker | Description |
|----------|----------|-------------------|-------------|
| `APP_ENV` | Yes | `development` | Environment name |
| `APP_KEY` | Yes | — | Laravel application encryption key |
| `APP_PORT` | No | `5541` | Host port for app web access |
| `DB_CONNECTION` | Yes | `mysql` | Database driver |
| `DB_HOST` | Yes | `db` | Database hostname inside Docker |
| `DB_PORT` | No | `3306` | Database port |
| `DB_DATABASE` | Yes | `swap_hub` | Database name |
| `DB_USERNAME` | Yes | `root` | Database username |
| `DB_PASSWORD` | Yes | `root` | Database password |
| `REDIS_HOST` | Yes | `redis` | Redis hostname |
| `REDIS_PORT` | No | `6379` | Redis port |
| `BROADCAST_CONNECTION` | Yes | `reverb` | Broadcasting driver (`reverb` or `pusher`) |
| `REVERB_APP_KEY` | No | `swaphubreverbkey` | Reverb key |
| `GITHUB_CLIENT_ID` | No | — | GitHub OAuth Client ID |
| `GITHUB_CLIENT_SECRET` | No | — | GitHub OAuth Secret |
| `GITHUB_WEBHOOK_SECRET` | No | — | HMAC-SHA256 secret for webhook validation |
