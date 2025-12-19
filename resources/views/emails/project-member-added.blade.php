<x-mail::message>
    # 🎉 New Member Joined Your Project!

    Hi **{{ $owner->name }}**,

    Great news! **{{ $member->name }}** has joined your project **{{ $project->title }}**.

    ## Project Details
    - **Project:** {{ $project->title }}
    - **Category:** {{ $project->category }}
    - **Status:** {{ ucfirst($project->status) }}
    - **New Member:** {{ $member->name }}

    Your project team is growing! You can now collaborate with {{ $member->name }} on this exciting project.

    <x-mail::button :url="route('projects.show', $project)">
        View Project
    </x-mail::button>

    **Next Steps:**
    - Welcome your new team member
    - Assign tasks and responsibilities
    - Start collaborating in the project chat

    Thanks,<br>
    {{ config('app.name') }} Team
</x-mail::message>