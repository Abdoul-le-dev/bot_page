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

// CONFIGURATION
const TELEGRAM_BOT_TOKEN = '7786438913:AAF9uLZpsBGzMk_Ty5tH3tqG4qjsLN8jWEo';
const TELEGRAM_API = `https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}`;

// VARIABLES GLOBALES
let conversations = [];
let currentConversation = null;
let pendingImages = [];

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                         INITIALISATION
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

async function init() {
    await renderConversations();
    setupEventListeners();
    initEmojiPicker();
}

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

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                         GESTION EMOJI
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

function toggleEmojiPicker() {
    const emojiPicker = document.getElementById('emojiPicker');
    if (emojiPicker.style.display === 'none' || !emojiPicker.style.display) {
        emojiPicker.style.display = 'block';
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

function insertEmoji(emoji) {
    const input = document.getElementById('messageInput');
    const cursorPos = input.selectionStart;
    const textBefore = input.value.substring(0, cursorPos);
    const textAfter = input.value.substring(cursorPos);
    input.value = textBefore + emoji + textAfter;
    input.focus();
    input.selectionStart = input.selectionEnd = cursorPos + emoji.length;
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                    AFFICHAGE CONVERSATIONS
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

async function renderConversations() {
    const list = document.getElementById('conversationsList');
    
    if (!list) {
        console.error('Element conversationsList non trouvé dans le DOM!');
        return;
    }
    
    list.innerHTML = '';

    try {
        const response = await fetch('http://54.226.165.244:8000/process', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: 'a' })
        });

        if (!response.ok) {
            console.error('Erreur API:', response.status);
            list.innerHTML = '<div style="padding: 24px; text-align: center; color: #999;">Erreur de chargement</div>';
            return;
        }

        const data = await response.json();
        
        // Parser si string JSON
        if (typeof data === 'string') {
            conversations = JSON.parse(data);
        } else if (Array.isArray(data)) {
            conversations = data;
        } else {
            conversations = [];
        }

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
        
    } catch (error) {
        console.error('Erreur lors du chargement des conversations:', error);
        list.innerHTML = '<div style="padding: 24px; text-align: center; color: #999;">Erreur réseau</div>';
    }
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                   SÉLECTION CONVERSATION
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

async function selectConversation(id) {
    const userId = id;

    try {
        const response = await fetch('https://bot.fiacrekpanoutrade.com/user', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        });

        if (!response.ok) {
            console.error("Backend error:", response.status);
            alert("Erreur API: " + response.status);
            return;
        }

        let data = await response.json();
        
        // Parser si string JSON
        if (typeof data === 'string') {
            data = JSON.parse(data);
        }

        // Trouver la conversation
        if (data && data.id && String(data.id) === String(id)) {
            currentConversation = data;
        } 
        else if (Array.isArray(data)) {
            currentConversation = data.find(c => String(c.id) === String(id));
        }
        else if (data && data.conversations && Array.isArray(data.conversations)) {
            currentConversation = data.conversations.find(c => String(c.id) === String(id));
        }
        else {
            currentConversation = null;
        }
        
        if (!currentConversation) {
            alert("Conversation non trouvée");
            return;
        }

        // Adapter le format des messages (TEXT → received)
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

    } catch (e) {
        console.log("Fetch failed:", e);
        alert("Erreur réseau: impossible de joindre l'API");
    }
}

function goBackToConversations() {
    document.getElementById('sidebar').classList.remove('hidden');
    document.getElementById('chatArea').classList.add('hidden');
    currentConversation = null;
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                      AFFICHAGE MESSAGES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

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
                msg.images.forEach(imgUrl => {
                    imagesHTML += `<img src="${imgUrl}" class="message-image" alt="Image" onclick="openLightbox('${imgUrl}')">`;
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

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                    ENVOI DE MESSAGES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

async function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    const caption = document.getElementById('imageCaption').value.trim();

    if ((!text && pendingImages.length === 0) || !currentConversation) return;

    const now = new Date();
    const time = `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;

    let messageType = 'text';
    let mediaUrls = [];
    
    // ÉTAPE 1 : Si images → Upload vers LARAVEL (stockage uniquement)
    if (pendingImages.length > 0) {
        try {
            mediaUrls = await uploadToLaravel(pendingImages, 'image');
            
            if (text || caption) {
                messageType = 'image_with_text';
            } else {
                messageType = 'image';
            }
        } catch (error) {
            console.error('❌ Erreur stockage Laravel:', error);
            alert('Erreur lors du stockage des images');
            return;
        }
    } else if (text) {
        if (isVideoUrl(text)) {
            messageType = 'video_url';
        } else {
            messageType = 'text';
        }
    }

    // Créer le message avec URLs
    const newMessage = {
        id: Date.now(),
        text: text || caption || '',
        type: 'sent',
        time: time,
        messageType: messageType
    };

    if (mediaUrls.length > 0) {
        newMessage.images = mediaUrls; // URLs Laravel
    }

    currentConversation.messages.push(newMessage);
    currentConversation.lastMessage = getMessagePreview(newMessage);
    currentConversation.time = time;

    input.value = '';
    input.style.height = 'auto';

    renderMessages();
    renderConversations();

    // ÉTAPE 2 : JavaScript envoie DIRECTEMENT au BOT Telegram
    await sendDirectlyToTelegramBot(
        currentConversation.userId,
        text || caption,
        mediaUrls,
        messageType
    );
    
    // Nettoyer
    pendingImages = [];
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('imageCaption').value = '';
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                  UPLOAD VERS LARAVEL (stockage)
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

async function uploadToLaravel(mediaFiles, mediaType = 'image') {
    const uploadedUrls = [];
    
    for (let i = 0; i < mediaFiles.length; i++) {
        const file = mediaFiles[i];
        
        try {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', mediaType);
            formData.append('userId', currentConversation.userId);
            
            console.log(`📤 Stockage Laravel: ${file.name} (${file.size} bytes)`);
            
            const response = await fetch('https://fiacrekpanoutrade.com/media', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(`Stockage failed: ${errorData.message || response.status}`);
            }
            
            const data = await response.json();
            
            if (data.url) {
                uploadedUrls.push(data.url);
                console.log(`✅ Stocké:`, data.url);
            } else {
                throw new Error('URL non retournée par Laravel');
            }
            
        } catch (error) {
            console.error(`❌ Erreur stockage:`, error);
            throw error;
        }
    }
    
    return uploadedUrls;
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//              ENVOI DIRECT AU BOT TELEGRAM (JavaScript)
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

async function sendDirectlyToTelegramBot(userId, text, mediaUrls = [], messageType = 'text') {
    try {
        console.log('📤 Envoi direct au bot Telegram...');
        console.log('Type:', messageType);
        console.log('URLs:', mediaUrls);
        
        if (messageType === 'text') {
            // Texte simple
            const response = await fetch(`${TELEGRAM_API}/sendMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    chat_id: userId,
                    text: text
                })
            });
            
            if (!response.ok) {
                throw new Error(`Telegram API error: ${response.status}`);
            }
            
            console.log('✅ Message texte envoyé');
        } 
        
        else if (messageType === 'image') {
            // Image(s) seule(s)
            for (const imageUrl of mediaUrls) {
                const response = await fetch(`${TELEGRAM_API}/sendPhoto`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        chat_id: userId,
                        photo: imageUrl
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`Telegram API error: ${response.status}`);
                }
            }
            console.log(`✅ ${mediaUrls.length} image(s) envoyée(s)`);
        }
        
        else if (messageType === 'image_with_text') {
            // Première image avec légende
            if (mediaUrls.length > 0) {
                const response = await fetch(`${TELEGRAM_API}/sendPhoto`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        chat_id: userId,
                        photo: mediaUrls[0],
                        caption: text
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`Telegram API error: ${response.status}`);
                }
                
                // Autres images sans légende
                for (let i = 1; i < mediaUrls.length; i++) {
                    const resp = await fetch(`${TELEGRAM_API}/sendPhoto`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            chat_id: userId,
                            photo: mediaUrls[i]
                        })
                    });
                    
                    if (!resp.ok) {
                        throw new Error(`Telegram API error: ${resp.status}`);
                    }
                }
            }
            console.log('✅ Image(s) avec texte envoyée(s)');
        }
        
        else if (messageType === 'video_url') {
            // URL vidéo
            const response = await fetch(`${TELEGRAM_API}/sendMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    chat_id: userId,
                    text: text
                })
            });
            
            if (!response.ok) {
                throw new Error(`Telegram API error: ${response.status}`);
            }
            
            console.log('✅ URL vidéo envoyée');
        }
        
    } catch (error) {
        console.error('❌ Erreur envoi Telegram:', error);
        alert('Erreur lors de l\'envoi au bot Telegram');
    }
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                      GESTION DES IMAGES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

function handleImageUpload(event) {
    const files = event.target.files;
    if (!files || files.length === 0 || !currentConversation) return;

    // Stocker les File objects (pas base64)
    pendingImages = Array.from(files);
    
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    // Preview de la première image
    if (pendingImages.length > 0) {
        const firstFile = pendingImages[0];
        const previewUrl = URL.createObjectURL(firstFile);
        imagePreview.src = previewUrl;
        imagePreviewContainer.style.display = 'block';
        imagePreview.onload = () => URL.revokeObjectURL(previewUrl);
    }

    event.target.value = '';
}

function cancelImageUpload() {
    pendingImages = [];
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('imageCaption').value = '';
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                        LIGHTBOX
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

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

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                      FONCTIONS UTILITAIRES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

function isVideoUrl(text) {
    const videoPatterns = [
        /youtube\.com\/watch\?v=/i,
        /youtu\.be\//i,
        /vimeo\.com\//i,
        /dailymotion\.com\//i,
        /\.mp4$/i,
        /\.avi$/i,
        /\.mov$/i,
        /\.webm$/i
    ];
    
    return videoPatterns.some(pattern => pattern.test(text));
}

function getMessagePreview(message) {
    switch (message.messageType) {
        case 'image':
            return '📷 Photo';
        case 'image_with_text':
            return `📷 ${message.text.substring(0, 30)}${message.text.length > 30 ? '...' : ''}`;
        case 'video':
        case 'video_url':
            return '🎥 Vidéo';
        case 'text':
        default:
            return message.text.substring(0, 50) + (message.text.length > 50 ? '...' : '');
    }
}

function archiveChat() {
    if (currentConversation) {
        alert('Archive: ' + currentConversation.name);
    }
}

function blockUser() {
    if (currentConversation) {
        if (confirm('Voulez-vous vraiment bloquer ' + currentConversation.name + ' ?')) {
            alert('Utilisateur bloqué');
        }
    }
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                    EVENT LISTENERS
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

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
        renderConversations();
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

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
//                      DÉMARRAGE
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

document.addEventListener('DOMContentLoaded', function() {
    init();
});