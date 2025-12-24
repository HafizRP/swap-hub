// Chat AJAX Navigation
// Add this script to enable smooth conversation switching without page reload

document.addEventListener('DOMContentLoaded', function () {
    const conversationLinks = document.querySelectorAll('.sidebar-conversation-item');

    conversationLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const conversationId = this.href.split('/').pop();

            // Update active state
            conversationLinks.forEach(l => {
                l.classList.remove('active-chat-tab');
                l.classList.add('hover-bg-light');
            });
            this.classList.add('active-chat-tab');
            this.classList.remove('hover-bg-light');

            // Load messages via AJAX
            loadConversation(conversationId);
        });
    });
});

function loadConversation(conversationId) {
    // Show loading state
    const messagesContainer = document.querySelector('[x-ref="messageContainer"]');
    if (messagesContainer) {
        messagesContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    }

    // Fetch messages
    fetch(`/chat/${conversationId}/messages`)
        .then(response => response.json())
        .then(data => {
            // Update Alpine.js data
            Alpine.store('chat').messages = data.messages;
            Alpine.store('chat').conversationId = conversationId;

            // Re-subscribe to Pusher channel
            if (window.Echo) {
                window.Echo.leave(`chat.${Alpine.store('chat').oldConversationId}`);
                window.Echo.private(`chat.${conversationId}`)
                    .listen('.message.sent', (e) => {
                        Alpine.store('chat').addMessage(e);
                    });
            }

            Alpine.store('chat').oldConversationId = conversationId;
        })
        .catch(error => {
            console.error('Error loading conversation:', error);
        });
}
