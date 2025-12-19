<x-mail::message>
    # ⭐ Your Contribution Has Been Validated!

    Hi **{{ $member->name }}**,

    Congratulations! Your contribution to **{{ $project->title }}** has been validated by the project owner.

    ## Validation Details

    <x-mail::panel>
        **Project:** {{ $project->title }}

        **Rating:** {{ str_repeat('⭐', $rating) }} ({{ $rating }}/5)

        **Reputation Earned:** +{{ $reputationEarned }} points

        @if($notes)
            **Feedback:** {{ $notes }}
        @endif
    </x-mail::panel>

    This validation has been added to your profile and will help build your reputation on the platform. Keep up the
    excellent work!

    <x-mail::button :url="route('profile.show', $member)">
        View Your Profile
    </x-mail::button>

    **What's Next?**
    - Continue contributing to projects
    - Build your reputation
    - Showcase your validated skills

    Thanks,<br>
    {{ config('app.name') }} Team
</x-mail::message>