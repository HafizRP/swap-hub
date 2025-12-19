<x-mail::message>
    # 💬 You Have a New Message!

    Hi **{{ $recipient->name }}**,

    **{{ $sender->name }}** sent you a new message in **{{ $conversation->name }}**.

    <x-mail::panel>
        {{ Str::limit($message->content, 150) }}
    </x-mail::panel>

    <x-mail::button :url="route('chat.show', $conversation)">
        View Message
    </x-mail::button>

    **Quick Reply:**
    Click the button above to view the full message and reply directly in the chat.

    ---

    *You're receiving this email because you're a participant in this conversation. You can manage your notification
    preferences in your account settings.*

    Thanks,<br>
    {{ config('app.name') }} Team
</x-mail::message>