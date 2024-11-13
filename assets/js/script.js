document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Auto-resize textarea
    const textarea = document.querySelector('textarea');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Handle message sending
    const sendButton = document.querySelector('.chat-input-container .btn');
    sendButton.addEventListener('click', sendMessage);
    textarea.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const messageText = textarea.value.trim();
        if (messageText) {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message user-message';
            messageDiv.innerHTML = `
                <div class="message-content">
                    <i class="bi bi-person"></i>
                    <div class="message-text">${messageText}</div>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
            textarea.value = '';
            textarea.style.height = 'auto';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }
});