document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.querySelector('textarea');
    const sendButton = document.querySelector('.chat-input-container .btn');
    const chatMessages = document.getElementById('chatMessages');

    // Toggle Sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Auto-resize textarea
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.querySelector('.chat-input-container textarea');
    const sendButton = document.querySelector('.chat-input-container button');

    // Load chat history when page loads
    loadChatHistory();

    // Handle send button click
    sendButton.addEventListener('click', sendMessage);

    // Handle enter key
    messageInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function loadChatHistory() {
        fetch('load_chat_history.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Clear existing messages
                    chatMessages.innerHTML = '';

                    // Add messages in reverse order (oldest first)
                    data.messages.reverse().forEach(msg => {
                        appendMessage('user', msg.message);
                        appendMessage('ai', msg.response);
                    });

                    scrollToBottom();
                }
            })
            .catch(error => console.error('Error loading chat history:', error));
    }

    function sendMessage() {
        console.log("Triggered");

        const message = messageInput.value.trim();
        if (!message) return;

        // Disable input and button while processing
        messageInput.disabled = true;
        sendButton.disabled = true;

        // Show user message immediately
        appendMessage('user', message);
        scrollToBottom();

        // Clear input
        messageInput.value = '';

        // Send to server
        fetch('chat_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    appendMessage('ai', data.response);
                    console.log(data.response);
                    scrollToBottom();
                } else {
                    appendMessage('ai', 'Sorry, an error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                appendMessage('ai', 'Sorry, an error occurred. Please try again.');
            })
            .finally(() => {
                // Re-enable input and button
                messageInput.disabled = false;
                sendButton.disabled = false;
                messageInput.focus();
                console.log("Out");

            });
    }

    function appendMessage(type, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;

        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';

        const icon = document.createElement('i');
        icon.className = type === 'ai' ? 'bi bi-robot' : 'bi bi-person';

        const messageText = document.createElement('div');
        messageText.className = 'message-text';
        messageText.textContent = text;

        messageContent.appendChild(icon);
        messageContent.appendChild(messageText);
        messageDiv.appendChild(messageContent);
        chatMessages.appendChild(messageDiv);
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});