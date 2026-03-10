<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\GoogleCalendar\GoogleCalendarFactory;
use Google_Service_Calendar_Calendar;

class CreateProjectGoogleCalendar implements ShouldQueue
{
    use Queueable;

    public Project $project;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Skip if project already has a calendar
            if ($this->project->google_calendar_id) {
                return;
            }

            // Get authenticated Google Calendar service via Spatie's factory
            $config = config('google-calendar');
            $client = GoogleCalendarFactory::createAuthenticatedGoogleClient($config);

            $service = new \Google_Service_Calendar($client);

            // Create a new Google Calendar for this project
            $calendar = new Google_Service_Calendar_Calendar();
            $calendar->setSummary("Swap Hub - {$this->project->title}");
            $calendar->setDescription("Task calendar for project: {$this->project->description}");
            $calendar->setTimeZone(config('app.timezone', 'UTC'));

            $createdCalendar = $service->calendars->insert($calendar);
            $calendarId = $createdCalendar->getId();

            // Save the Calendar ID to the project
            $this->project->update([
                'google_calendar_id' => $calendarId,
            ]);

            \Log::info("Google Calendar created for project [{$this->project->title}]: {$calendarId}");

            // Auto-share the calendar with the project owner
            $this->shareCalendarWithOwner($service, $calendarId);

        } catch (\Exception $e) {
            \Log::error("Failed to create Google Calendar for project [{$this->project->title}]: " . $e->getMessage());
        }
    }

    /**
     * Share the calendar with the project owner so it appears in their Google Calendar.
     */
    protected function shareCalendarWithOwner(\Google_Service_Calendar $service, string $calendarId): void
    {
        try {
            // Load owner fresh (may not be loaded when job was serialized)
            $owner = $this->project->owner()->first();

            if (!$owner || !$owner->email) {
                return;
            }

            $rule = new \Google_Service_Calendar_AclRule();
            $scope = new \Google_Service_Calendar_AclRuleScope();

            $scope->setType('user');
            $scope->setValue($owner->email);

            $rule->setScope($scope);
            $rule->setRole('writer'); // owner can add/edit events

            $service->acl->insert($calendarId, $rule);

            \Log::info("Google Calendar [{$calendarId}] shared with owner [{$owner->email}]");

        } catch (\Exception $e) {
            \Log::error("Failed to share Google Calendar with owner: " . $e->getMessage());
        }
    }
}
