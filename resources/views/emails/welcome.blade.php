<x-mail::message>
    # 👋 Welcome to {{ config('app.name') }}!

    Hi **{{ $user->name }}**,

    We're thrilled to have you join our community of talented students collaborating on amazing projects!

    ## What is {{ config('app.name') }}?

    {{ config('app.name') }} is a platform where students can:
    - **Collaborate** on exciting projects with peers
    - **Showcase** their skills and build their portfolio
    - **Earn** reputation through validated contributions
    - **Connect** with like-minded students

    ## Get Started

    <x-mail::button :url="route('dashboard')">
        Explore Dashboard
    </x-mail::button>

    **Here's what you can do next:**

    1. **Complete Your Profile** - Add your skills, bio, and connect your GitHub account
    2. **Browse Projects** - Find interesting projects to join or create your own
    3. **Start Collaborating** - Join a project and start making contributions
    4. **Build Reputation** - Get your contributions validated and earn reputation points

    ## Need Help?

    If you have any questions or need assistance, feel free to reach out to our support team.

    We can't wait to see what you'll create!

    Thanks,<br>
    The {{ config('app.name') }} Team

    ---

    *P.S. Make sure to complete your profile to get the most out of the platform!*
</x-mail::message>