<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Task;
use Spatie\GoogleCalendar\GoogleCalendarFactory;

class CreateGoogleCalendarEvent implements ShouldQueue
{
    use Queueable;

    public $task;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get calendar ID from project, fallback to env
            // Refresh project to get the latest google_calendar_id (may have been set by another job)
            $this->task->project->refresh();
            $calendarId = $this->task->project->google_calendar_id
                ?? config('google-calendar.calendar_id');

            if (!$calendarId) {
                // Calendar may not be created yet (race condition with CreateProjectGoogleCalendar)
                // Release back to queue and retry after 30 seconds
                if ($this->attempts() < 3) {
                    $this->release(30);
                    \Log::info("No Google Calendar ID yet for project [{$this->task->project->title}]. Retrying in 30s.");
                    return;
                }
                \Log::warning("No Google Calendar ID set for project [{$this->task->project->title}] after retries. Skipping.");
                return;
            }

            // Build authenticated Google Calendar service
            $config  = config('google-calendar');
            $client  = GoogleCalendarFactory::createAuthenticatedGoogleClient($config);
            $service = new \Google_Service_Calendar($client);

            // Build event
            $googleEvent = new \Google_Service_Calendar_Event();
            $googleEvent->setSummary("[Swap Hub - {$this->task->project->title}] {$this->task->title}");
            $googleEvent->setDescription(
                $this->task->description .
                "\n\nAssignee: " . ($this->task->assignee ? $this->task->assignee->name : 'Unassigned')
            );

            // Set date
            if ($this->task->due_date) {
                $start = new \Google_Service_Calendar_EventDateTime();
                $start->setDate(\Carbon\Carbon::parse($this->task->due_date)->format('Y-m-d'));
                $start->setTimeZone(config('app.timezone', 'UTC'));

                $end = new \Google_Service_Calendar_EventDateTime();
                $end->setDate(\Carbon\Carbon::parse($this->task->due_date)->addDay()->format('Y-m-d'));
                $end->setTimeZone(config('app.timezone', 'UTC'));
            } else {
                $start = new \Google_Service_Calendar_EventDateTime();
                $start->setDateTime(\Carbon\Carbon::now()->format(\DateTime::RFC3339));
                $start->setTimeZone(config('app.timezone', 'UTC'));

                $end = new \Google_Service_Calendar_EventDateTime();
                $end->setDateTime(\Carbon\Carbon::now()->addHour()->format(\DateTime::RFC3339));
                $end->setTimeZone(config('app.timezone', 'UTC'));
            }

            $googleEvent->setStart($start);
            $googleEvent->setEnd($end);

            // NOTE: Service accounts cannot invite attendees without Google Workspace
            // Domain-Wide Delegation. We include assignee info in the description instead.

            // Insert event to the project's Google Calendar
            $createdEvent = $service->events->insert($calendarId, $googleEvent);

            // Save google_event_id to the task
            if ($createdEvent && $createdEvent->getId()) {
                $this->task->update([
                    'google_event_id' => $createdEvent->getId(),
                ]);
                \Log::info("Google Calendar event created for task [{$this->task->title}]: {$createdEvent->getId()}");
            }

        } catch (\Exception $e) {
            \Log::error('Failed to create Google Calendar Event: ' . $e->getMessage());
        }
    }
}
