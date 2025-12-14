// EMOJIS DISPONIBLES
const emojis = [
    '😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃',
    '😉', '😊', '😇', '🥰', '😍', '🤩', '😘', '😗', '😚', '😙',
    '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔',
    '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥',
    '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮',
    '🤧', '🥵', '🥶', '😶‍🌫️', '😵', '🤯', '🤠', '🥳', '😎', '🤓',
    '🧐', '😕', '😟', '🙁', '☹️', '😮', '😯', '😲', '😳', '🥺',
    '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣',
    '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '👍',
    '👎', '👏', '🙌', '👋', '🤝', '🙏', '💪', '✌️', '🤞', '🤟',
    '🤘', '👌', '🤏', '👈', '👉', '👆', '👇', '☝️', '✋', '🤚',
    '💖', '💕', '💗', '💓', '💝', '💘', '💞', '💟', '❤️', '🧡',
    '💛', '💚', '💙', '💜', '🤎', '🖤', '🤍', '❤️‍🔥', '❤️‍🩹', '💔',
    '⭐', '🌟', '✨', '💫', '🔥', '💯', '✅', '❌', '⚠️', '🎉',
    '🎊', '🎈', '🎁', '🏆', '🥇', '🥈', '🥉', '🎯', '💰', '💸'
];

// DONNÉES DES CONVERSATIONS (chargées depuis l'API)
let conversations = [];
let currentConversation = null;
let pendingImages = [];

// INITIALISATION
function init() {
    loadConversations(); // Charger les conversations au démarrage
    setupEventListeners();
    initEmojiPicker();
}

// CHARGER LES CONVERSATIONS DEPUIS L'API
async function loadConversations() {
    try {
        const response = await fetch('http://bot.fiacrekpanoutrade.com/process', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: 'a' })
        });

        if (!response.ok) {
            console.error('Erreur API:', response.status);
            return;
        }

        const data = await response.json();
        
        // Le backend retourne directement un tableau
        conversations = Array.isArray(data) ? data : [];
        
        // Adapter les messages pour avoir le bon format de type
        conversations.forEach(conv => {
            if (conv.messages && Array.isArray(conv.messages)) {
                conv.messages = conv.messages.map(msg => ({
                    ...msg,
                    type: msg.type === 'TEXT' || msg.type === 'text' ? 'received' : msg.type
                }));
            }
            // Définir le dernier message si null
            if (!conv.lastMessage && conv.messages && conv.messages.length > 0) {
                conv.lastMessage = conv.messages[conv.messages.length - 1].text;
            }
        });

        renderConversations();
    } catch (error) {
        console.error('Erreur lors du chargement des conversations:', error);
    }
}

// INITIALISER LE PICKER D'EMOJIS
function initEmojiPicker() {
    const emojiGrid = document.getElementById('emojiGrid');
    emojis.forEach(emoji => {
        const emojiItem = document.createElement('div');
        emojiItem.className = 'emoji-item';
        emojiItem.textContent = emoji;
        emojiItem.onclick = () => insertEmoji(emoji);
        emojiGrid.appendChild(emojiItem);
    });
}

// TOGGLE EMOJI PICKER
function toggleEmojiPicker() {
    const emojiPicker = document.getElementById('emojiPicker');
    if (emojiPicker.style.display === 'none' || !emojiPicker.style.display) {
        emojiPicker.style.display = 'block';
        // Fermer au clic en dehors
        setTimeout(() => {
            document.addEventListener('click', closeEmojiPickerOnClickOutside);
        }, 100);
    } else {
        emojiPicker.style.display = 'none';
    }
}

function closeEmojiPickerOnClickOutside(e) {
    const emojiPicker = document.getElementById('emojiPicker');
    if (!emojiPicker.contains(e.target) && !e.target.closest('.input-controls button[title*="emoji"]')) {
        emojiPicker.style.display = 'none';
        document.removeEventListener('click', closeEmojiPickerOnClickOutside);
    }
}

// INSÉRER UN EMOJI
function insertEmoji(emoji) {
    const input = document.getElementById('messageInput');
    const cursorPos = input.selectionStart;
    const textBefore = input.value.substring(0, cursorPos);
    const textAfter = input.value.substring(cursorPos);
    input.value = textBefore + emoji + textAfter;
    input.focus();
    input.selectionStart = input.selectionEnd = cursorPos + emoji.length;
}

// AFFICHER LES CONVERSATIONS
function renderConversations() {
    const list = document.getElementById('conversationsList');
    list.innerHTML = '';

    if (conversations.length === 0) {
        list.innerHTML = '<div style="padding: 24px; text-align: center; color: #999;">Aucune conversation</div>';
        return;
    }

    conversations.forEach((conv, index) => {
        const item = document.createElement('div');
        item.className = 'conversation-item';
        if (currentConversation && String(currentConversation.id) === String(conv.id)) {
            item.classList.add('active');
        }
        
        item.style.animationDelay = `${index * 0.05}s`;

        item.innerHTML = `
            <div class="conv-header">
                <div class="conv-name">${conv.name || 'Utilisateur'}</div>
                <div class="conv-time">${conv.time || ''}</div>
            </div>
            <div class="conv-preview">${conv.lastMessage || 'Pas de message'}</div>
            ${conv.unread > 0 ? `<span class="unread-badge">${conv.unread} nouveau${conv.unread > 1 ? 'x' : ''}</span>` : ''}
        `;

        item.onclick = () => selectConversation(conv.id);
        list.appendChild(item);
    });
}

// SÉLECTIONNER UNE CONVERSATION
async function selectConversation(id) {
    try {
        const userId = id;

        const response = await fetch('http://bot.fiacrekpanoutrade.com/user', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        });

        if (!response.ok) {
            console.error('Erreur API:', response.status);
            alert('Erreur lors du chargement de la conversation');
            return;
        }

        const data = await response.json();
        
        // Le backend peut retourner soit un tableau, soit un objet avec conversations
        const conversationsList = Array.isArray(data) ? data : (data?.conversations ?? []);
        
        // Trouver la conversation (comparaison string pour éviter les problèmes de types)

        currentConversation = conversationsList.find(c => String(c.id) === String(id));
        
        if (!currentConversation) {
            console.error('Conversation non trouvée');
            return;
        }

        // Adapter les messages
        if (currentConversation.messages && Array.isArray(currentConversation.messages)) {
            currentConversation.messages = currentConversation.messages.map(msg => ({
                ...msg,
                type: msg.type === 'TEXT' || msg.type === 'text' ? 'received' : msg.type
            }));
        }

        currentConversation.unread = 0;

        // Mobile: Cacher la sidebar et afficher le chat
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.add('hidden');
            document.getElementById('chatArea').classList.remove('hidden');
        }

        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('chatContainer').style.display = 'flex';
        document.getElementById('chatUserName').textContent = currentConversation.name || 'Utilisateur';

        renderMessages();
        renderConversations();
        
    } catch (error) {
        console.error('Erreur lors de la sélection de la conversation:', error);
        alert('Erreur réseau');
    }
}

// RETOUR AUX CONVERSATIONS (MOBILE)
function goBackToConversations() {
    document.getElementById('sidebar').classList.remove('hidden');
    document.getElementById('chatArea').classList.add('hidden');
    currentConversation = null;
}

// AFFICHER LES MESSAGES
function renderMessages() {
    const container = document.getElementById('messagesContainer');
    container.innerHTML = '';

    if (!currentConversation || !currentConversation.messages) {
        return;
    }

    currentConversation.messages.forEach((msg, index) => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${msg.type}`;
        messageDiv.style.animationDelay = `${index * 0.05}s`;

        let imagesHTML = '';
        if (msg.images && msg.images.length > 0) {
            if (msg.images.length === 1) {
                imagesHTML = `<img src="${msg.images[0]}" class="message-image" alt="Image" onclick="openLightbox('${msg.images[0]}')">`;
            } else {
                imagesHTML = '<div class="message-images-grid">';
                msg.images.forEach(img => {
                    imagesHTML += `<img src="${img}" class="message-image" alt="Image" onclick="openLightbox('${img}')">`;
                });
                imagesHTML += '</div>';
            }
        }

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    ${imagesHTML}
                    ${msg.text || ''}
                </div>
                <div class="message-time">${msg.time || ''}</div>
            </div>
        `;

        container.appendChild(messageDiv);
    });

    container.scrollTop = container.scrollHeight;
}

// ENVOYER UN MESSAGE
async function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    const caption = document.getElementById('imageCaption').value.trim();

    if ((!text && pendingImages.length === 0) || !currentConversation) return;

    const now = new Date();
    const time = `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;

    const newMessage = {
        id: Date.now(),
        text: text || caption,
        type: 'sent',
        time: time
    };

    // Ajouter les images si présentes
    if (pendingImages.length > 0) {
        newMessage.images = [...pendingImages];
        pendingImages = [];
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('imageCaption').value = '';
    }

    currentConversation.messages.push(newMessage);
    currentConversation.lastMessage = text || '📷 Photo';
    currentConversation.time = time;

    input.value = '';
    input.style.height = 'auto';

    renderMessages();
    renderConversations();

    // Envoyer à Telegram
    if (newMessage.images) {
        await sendImagesToTelegram(currentConversation.userId, newMessage.images, newMessage.text);
    } else {
        await sendToTelegram(currentConversation.userId, text);
    }
}

// GÉRER L'UPLOAD D'IMAGE(S)
function handleImageUpload(event) {
    const files = event.target.files;
    if (!files || files.length === 0 || !currentConversation) return;

    pendingImages = [];
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            pendingImages.push(e.target.result);
            if (pendingImages.length === 1) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    });

    event.target.value = '';
}

// ANNULER L'UPLOAD D'IMAGE
function cancelImageUpload() {
    pendingImages = [];
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('imageCaption').value = '';
}

// LIGHTBOX POUR VISUALISER LES IMAGES
function openLightbox(imageSrc) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    lightboxImage.src = imageSrc;
    lightbox.classList.add('active');
    lightbox.dataset.currentImage = imageSrc;
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.remove('active');
}

function downloadImage(event) {
    event.stopPropagation();
    const lightbox = document.getElementById('lightbox');
    const imageSrc = lightbox.dataset.currentImage;
    
    const link = document.createElement('a');
    link.href = imageSrc;
    link.download = 'image_' + Date.now() + '.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// FONCTIONS À INTÉGRER AVEC VOTRE BACKEND
async function sendToTelegram(userId, text) {
    try {
        const response = await fetch('http://bot.fiacrekpanoutrade.com/send-message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId, text })
        });

        if (!response.ok) {
            console.error('Erreur envoi message:', response.status);
        }
    } catch (error) {
        console.error('Erreur lors de l\'envoi du message:', error);
    }
}

async function sendImagesToTelegram(userId, images, caption) {
    try {
        const formData = new FormData();
        formData.append('userId', userId);
        formData.append('caption', caption || '');
        
        // Convertir les images base64 en blobs
        for (let i = 0; i < images.length; i++) {
            const response = await fetch(images[i]);
            const blob = await response.blob();
            formData.append(`image${i}`, blob, `image${i}.jpg`);
        }
        
        const response = await fetch('http://bot.fiacrekpanoutrade.com/send-images', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            console.error('Erreur envoi images:', response.status);
        }
    } catch (error) {
        console.error('Erreur lors de l\'envoi des images:', error);
    }
}

// AUTRES ACTIONS
function archiveChat() {
    if (currentConversation) {
        alert('Archive: ' + currentConversation.name);
        // TODO: Implémenter l'archivage
    }
}

function blockUser() {
    if (currentConversation) {
        if (confirm('Voulez-vous vraiment bloquer ' + currentConversation.name + ' ?')) {
            alert('Utilisateur bloqué');
            // TODO: Implémenter le blocage
        }
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

    // GESTION RESPONSIVE
    window.addEventListener('resize', handleResize);
    handleResize();

    // Fermer lightbox avec Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLightbox();
            const emojiPicker = document.getElementById('emojiPicker');
            if (emojiPicker.style.display === 'block') {
                emojiPicker.style.display = 'none';
            }
        }
    });

    // Actualiser les conversations toutes les 30 secondes
    setInterval(() => {
        loadConversations();
    }, 30000);
}

function handleResize() {
    if (window.innerWidth > 768) {
        document.getElementById('sidebar').classList.remove('hidden');
        document.getElementById('chatArea').classList.remove('hidden');
    } else {
        if (!currentConversation) {
            document.getElementById('sidebar').classList.remove('hidden');
            document.getElementById('chatArea').classList.add('hidden');
        } else {
            document.getElementById('sidebar').classList.add('hidden');
            document.getElementById('chatArea').classList.remove('hidden');
        }
    }
}

// DÉMARRER L'APPLICATION
init();