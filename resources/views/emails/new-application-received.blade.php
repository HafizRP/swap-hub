<x-mail::message>
# 📬 New Application for Your Project!

Hi **{{ $project->owner->name }}**,

**{{ $applicant->name }}** has applied to join your project **{{ $project->title }}**.

## Application Details
- **Project:** {{ $project->title }}
- **Applicant:** {{ $applicant->name }} ({{ $applicant->email }})
- **Message:** {{ $applicationMessage }}

<x-mail::button :url="route('projects.show', $project)">
Review Application
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
