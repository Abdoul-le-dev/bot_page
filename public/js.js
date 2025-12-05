// DONNÉES EXEMPLE (À remplacer par vos vraies données depuis la base)

let conversations = [
    {
        id: 1,
        name: "Jean Dupont",
        userId: "123456",
        lastMessage: "Bonjour, j'aimerais avoir des informations sur vos services",
        time: "14:23",
        unread: 2,
        messages: [
            { id: 1, text: "Bonjour !", type: "received", time: "14:20" },
            { id: 2, text: "J'aimerais avoir des informations sur vos services", type: "received", time: "14:23" }
        ]
    },
    {
        id: 2,
        name: "Marie Martin",
        userId: "789012",
        lastMessage: "Merci beaucoup pour votre aide !",
        time: "13:45",
        unread: 0,
        messages: [
            { id: 1, text: "Bonjour, j'ai une question", type: "received", time: "13:30" },
            { id: 2, text: "Bonjour Marie ! Je vous écoute", type: "sent", time: "13:32" },
            { id: 3, text: "Merci beaucoup pour votre aide !", type: "received", time: "13:45" }
        ]
    },
    {
        id: 3,
        name: "Pierre Leroy",
        userId: "345678",
        lastMessage: "D'accord, je comprends",
        time: "12:15",
        unread: 0,
        messages: [
            { id: 1, text: "Voici les informations que vous avez demandées", type: "sent", time: "12:10" },
            { id: 2, text: "D'accord, je comprends", type: "received", time: "12:15" }
        ]
    }
];

let currentConversation = null;

// INITIALISATION
function init() {
    renderConversations();
    setupEventListeners();
}

// AFFICHER LES CONVERSATIONS
function renderConversations() {
    const list = document.getElementById('conversationsList');
    list.innerHTML = '';

    conversations.forEach((conv, index) => {
        const item = document.createElement('div');
        item.className = 'conversation-item';
        if (currentConversation && currentConversation.id === conv.id) {
            item.classList.add('active');
        }
        
        // Animation échelonnée
        item.style.animationDelay = `${index * 0.05}s`;

        item.innerHTML = `
            <div class="conv-header">
                <div class="conv-name">${conv.name}</div>
                <div class="conv-time">${conv.time}</div>
            </div>
            <div class="conv-preview">${conv.lastMessage}</div>
            ${conv.unread > 0 ? `<span class="unread-badge">${conv.unread} nouveau${conv.unread > 1 ? 'x' : ''}</span>` : ''}
        `;

        item.onclick = () => selectConversation(conv.id);
        list.appendChild(item);
    });
}

// SÉLECTIONNER UNE CONVERSATION
function selectConversation(id) {
    currentConversation = conversations.find(c => c.id === id);
    if (!currentConversation) return;

    // Marquer comme lu
    currentConversation.unread = 0;

    // Afficher le chat
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('chatContainer').style.display = 'flex';
    document.getElementById('chatUserName').textContent = currentConversation.name;

    renderMessages();
    renderConversations();
}

// AFFICHER LES MESSAGES
function renderMessages() {
    const container = document.getElementById('messagesContainer');
    container.innerHTML = '';

    currentConversation.messages.forEach((msg, index) => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${msg.type}`;
        messageDiv.style.animationDelay = `${index * 0.05}s`;

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    ${msg.image ? `<img src="${msg.image}" class="message-image" alt="Image">` : ''}
                    ${msg.text}
                </div>
                <div class="message-time">${msg.time}</div>
            </div>
        `;

        container.appendChild(messageDiv);
    });

    // Scroll vers le bas
    container.scrollTop = container.scrollHeight;
}

// ENVOYER UN MESSAGE
function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();

    if (!text || !currentConversation) return;

    const now = new Date();
    const time = `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;

    const newMessage = {
        id: Date.now(),
        text: text,
        type: 'sent',
        time: time
    };

    currentConversation.messages.push(newMessage);
    currentConversation.lastMessage = text;
    currentConversation.time = time;

    input.value = '';
    input.style.height = 'auto';

    renderMessages();
    renderConversations();

    // ICI: Envoyer le message à votre API/Bot Telegram
    sendToTelegram(currentConversation.userId, text);
}

// GÉRER L'UPLOAD D'IMAGE
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file || !currentConversation) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const now = new Date();
        const time = `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;

        const newMessage = {
            id: Date.now(),
            text: '📷 Photo',
            image: e.target.result,
            type: 'sent',
            time: time
        };

        currentConversation.messages.push(newMessage);
        currentConversation.lastMessage = '📷 Photo';
        currentConversation.time = time;

        renderMessages();
        renderConversations();

        // ICI: Envoyer l'image à votre API/Bot Telegram
        sendImageToTelegram(currentConversation.userId, file);
    };
    reader.readAsDataURL(file);

    // Reset input
    event.target.value = '';
}

// FONCTIONS À INTÉGRER AVEC VOTRE BACKEND
function sendToTelegram(userId, text) {
    // TODO: Appeler votre API pour envoyer le message via Telegram Bot
    console.log('Envoi à Telegram:', { userId, text });
    
    // Exemple d'appel API:
    /*
    fetch('/api/send-message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId, text })
    });
    */
}

function sendImageToTelegram(userId, file) {
    // TODO: Appeler votre API pour envoyer l'image via Telegram Bot
    console.log('Envoi image à Telegram:', { userId, file });
    
    // Exemple d'appel API:
    /*
    const formData = new FormData();
    formData.append('userId', userId);
    formData.append('image', file);
    
    fetch('/api/send-image', {
        method: 'POST',
        body: formData
    });
    */
}

// AUTRES ACTIONS
function addEmoji() {
    const input = document.getElementById('messageInput');
    input.value += '😊';
    input.focus();
}

function archiveChat() {
    alert('Fonction archiver à implémenter');
}

function blockUser() {
    if (confirm('Voulez-vous vraiment bloquer cet utilisateur ?')) {
        alert('Fonction bloquer à implémenter');
    }
}

function setupEventListeners() {
    // RECHERCHE
    document.getElementById('searchInput').addEventListener('input', (e) => {
        const search = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.conversation-item');

        items.forEach(item => {
            const name = item.querySelector('.conv-name').textContent.toLowerCase();
            const preview = item.querySelector('.conv-preview').textContent.toLowerCase();
            
            if (name.includes(search) || preview.includes(search)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // AUTO-RESIZE TEXTAREA
    const messageInput = document.getElementById('messageInput');
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // ENVOYER AVEC ENTER
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Vous pouvez ajouter des WebSockets ici pour recevoir les messages en temps réel
    /*
    const socket = new WebSocket('ws://votre-serveur.com');
    socket.onmessage = (event) => {
        const data = JSON.parse(event.data);
        // Mettre à jour les conversations avec les nouveaux messages
    };
    */
}

// DÉMARRER L'APPLICATION
init();