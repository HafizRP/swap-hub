<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\GoogleCalendar\GoogleCalendarFactory;

class AddMemberToProjectCalendar implements ShouldQueue
{
    use Queueable;

    public Project $project;
    public User $user;
    public string $role;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project, User $user, string $role = 'writer')
    {
        $this->project = $project;
        $this->user    = $user;
        $this->role    = $role;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $calendarId = $this->project->google_calendar_id;

            if (!$calendarId) {
                \Log::info("Project [{$this->project->title}] has no Google Calendar. Skipping member add.");
                return;
            }

            if (!$this->user->email) {
                return;
            }

            // Build authenticated Google Calendar service
            $config  = config('google-calendar');
            $client  = GoogleCalendarFactory::createAuthenticatedGoogleClient($config);
            $service = new \Google_Service_Calendar($client);

            // Add user to calendar via ACL
            $rule  = new \Google_Service_Calendar_AclRule();
            $scope = new \Google_Service_Calendar_AclRuleScope();

            $scope->setType('user');
            $scope->setValue($this->user->email);

            $rule->setScope($scope);

            // contributor = read-only, owner/member = can write
            // $aclRole = ($this->role === 'contributor') ? 'reader' : 'writer';
            $rule->setRole($this->role);

            $service->acl->insert($calendarId, $rule);

            \Log::info("User [{$this->user->email}] added to Google Calendar of project [{$this->project->title}]");

        } catch (\Exception $e) {
            \Log::error("Failed to add member [{$this->user->email}] to Google Calendar: " . $e->getMessage());
        }
    }
}
