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

let conversation = 
[
       
    {
        id: 1,
        name: "Jean Dupont",
        userId: "123456",
        lastMessage: "Voici quelquess photos de mon projet 📸",
        time: "14:23",
        unread: 2,
        messages: [
            { 
                id: 1, 
                text: "Bonjour ! 👋 Comment allez-vous ?", 
                type: "received", 
                time: "14:10" 
            },
            { 
                id: 2, 
                text: "Bonjour Jean ! Je vais très bien merci 😊 Et vous ?", 
                type: "sent", 
                time: "14:11" 
            },
            { 
                id: 3, 
                text: "Très bien aussi ! J'aimerais avoir des informations sur vos services", 
                type: "received", 
                time: "14:12" 
            },
            { 
                id: 4, 
                text: "Bien sûr ! Je serais ravi de vous aider. Quel type de service vous intéresse particulièrement ? 🤔", 
                type: "sent", 
                time: "14:13" 
            },
            { 
                id: 5, 
                text: "Voici quelques photos de mon projet 📸", 
                type: "received", 
                time: "14:23",
                images: [
                    "https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=500&h=300&fit=crop",
                    "https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=500&h=300&fit=crop"
                ]
            }
        ]
    },
    {
        id: 2,
        name: "Marie Martin",
        userId: "789012",
        lastMessage: "Merci beaucoup pour votre aide ! 🙏",
        time: "13:45",
        unread: 0,
        messages: [
            { 
                id: 1, 
                text: "Bonjour, j'ai une question concernant la facturation 💰", 
                type: "received", 
                time: "13:30" 
            },
            { 
                id: 2, 
                text: "Bonjour Marie ! 😊 Je vous écoute, quelle est votre question ?", 
                type: "sent", 
                time: "13:32" 
            },
            { 
                id: 3, 
                text: "Est-ce que je peux payer en plusieurs fois ?", 
                type: "received", 
                time: "13:35" 
            },
            { 
                id: 4, 
                text: "Oui bien sûr ! Nous proposons des paiements en 2, 3 ou 4 fois sans frais 💳", 
                type: "sent", 
                time: "13:37" 
            },
            { 
                id: 5, 
                text: "Voici un exemple de notre interface de paiement", 
                type: "sent", 
                time: "13:38",
                images: [
                    "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500&h=300&fit=crop"
                ]
            },
            { 
                id: 6, 
                text: "Parfait ! C'est exactement ce que je cherchais 😍", 
                type: "received", 
                time: "13:42" 
            },
            { 
                id: 7, 
                text: "Merci beaucoup pour votre aide ! 🙏", 
                type: "received", 
                time: "13:45" 
            }
        ]
    },
    {
        id: 3,
        name: "Pierre Leroy",
        userId: "345678",
        lastMessage: "Voilà les documents demandés 📄",
        time: "12:15",
        unread: 0,
        messages: [
            { 
                id: 1, 
                text: "Bonjour ! J'ai besoin de quelques documents pour finaliser mon dossier 📋", 
                type: "received", 
                time: "12:00" 
            },
            { 
                id: 2, 
                text: "Pas de problème Pierre ! Quels documents vous faut-il exactement ?", 
                type: "sent", 
                time: "12:02" 
            },
            { 
                id: 3, 
                text: "J'aurais besoin du contrat et de la facture pro forma", 
                type: "received", 
                time: "12:05" 
            },
            { 
                id: 4, 
                text: "Voici les informations que vous avez demandées ✅", 
                type: "sent", 
                time: "12:10" 
            },
            { 
                id: 5, 
                text: "Voilà les documents demandés 📄", 
                type: "received", 
                time: "12:15",
                images: [
                    "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=500&h=300&fit=crop",
                    "https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=500&h=300&fit=crop",
                    "https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=500&h=300&fit=crop"
                ]
            }
        ]
    },
    {
        id: 4,
        name: "Sophie Dubois",
        userId: "901234",
        lastMessage: "Super ! J'adore ce design 🎨",
        time: "11:30",
        unread: 1,
        messages: [
            { 
                id: 1, 
                text: "Salut ! J'ai terminé les maquettes du site web 🎨", 
                type: "received", 
                time: "11:20" 
            },
            { 
                id: 2, 
                text: "Génial Sophie ! Montre-moi ça 👀", 
                type: "sent", 
                time: "11:22" 
            },
            { 
                id: 3, 
                text: "Voilà les designs ! Qu'est-ce que tu en penses ?", 
                type: "received", 
                time: "11:25",
                images: [
                    "https://images.unsplash.com/photo-1547658719-da2b51169166?w=500&h=300&fit=crop",
                    "https://images.unsplash.com/photo-1559028012-481c04fa702d?w=500&h=300&fit=crop"
                ]
            },
            { 
                id: 4, 
                text: "Wow ! C'est magnifique 😍 Les couleurs sont parfaites !", 
                type: "sent", 
                time: "11:27" 
            },
            { 
                id: 5, 
                text: "Super ! J'adore ce design 🎨", 
                type: "received", 
                time: "11:30" 
            }
        ]
    },
    {
        id: 5,
        name: "Thomas Bernard",
        userId: "567890",
        lastMessage: "Ok merci pour l'info 👍",
        time: "10:45",
        unread: 0,
        messages: [
            { 
                id: 1, 
                text: "Hello ! Une petite question rapide ⚡", 
                type: "received", 
                time: "10:30" 
            },
            { 
                id: 2, 
                text: "Salut Thomas ! Je t'écoute 😊", 
                type: "sent", 
                time: "10:32" 
            },
            { 
                id: 3, 
                text: "C'est quoi les horaires d'ouverture de votre support ? 🕐", 
                type: "received", 
                time: "10:35" 
            },
            { 
                id: 4, 
                text: "Nous sommes disponibles du lundi au vendredi de 9h à 18h 📅\nEt le samedi de 10h à 16h !", 
                type: "sent", 
                time: "10:37" 
            },
            { 
                id: 5, 
                text: "Parfait ! Et pour les urgences le weekend ? 🆘", 
                type: "received", 
                time: "10:40" 
            },
            { 
                id: 6, 
                text: "Pour les urgences, nous avons une hotline disponible 24/7 au 01 23 45 67 89 ☎️", 
                type: "sent", 
                time: "10:42" 
            },
            { 
                id: 7, 
                text: "Ok merci pour l'info 👍", 
                type: "received", 
                time: "10:45" 
            }
        ]
    },
    {
        id: 6,
        name: "Isabelle Petit",
        userId: "234567",
        lastMessage: "J'attends ta réponse 😊",
        time: "09:15",
        unread: 3,
        messages: [
            { 
                id: 1, 
                text: "Coucou ! J'ai une proposition de collaboration à te faire 🤝", 
                type: "received", 
                time: "09:00" 
            },
            { 
                id: 2, 
                text: "Regarde ce projet, je pense que ça pourrait t'intéresser 💡", 
                type: "received", 
                time: "09:10",
                images: [
                    "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=500&h=300&fit=crop"
                ]
            },
            { 
                id: 3, 
                text: "J'attends ta réponse 😊", 
                type: "received", 
                time: "09:15" 
            }
        ]
    }
];

let currentConversation = null;
let pendingImages = [];
let conversations ={}

window.addEventListener('load', async () =>  {
    const text = 'a';

    const response = await fetch('http://bot.fiacrekpanoutrade.com/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text })
    });

    const data = await response.json();
    conversations = JSON.stringify(data, null, 2);
});


function get_data()
{
    // connect server 

    
}
// INITIALISATION
function init() {
    renderConversations();
    setupEventListeners();
    initEmojiPicker();
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
async function renderConversations() {
    const list = document.getElementById('conversationsList');
    list.innerHTML = '';

    

    const text = 'a';

    const response = await fetch('http://bot.fiacrekpanoutrade.com/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text })
    });

    const data = await response.json();
    conversations = JSON.parse(data);

    

    conversations.forEach((conv, index) => {
        const item = document.createElement('div');
        item.className = 'conversation-item';
        if (currentConversation && currentConversation.id === conv.id) {
            item.classList.add('active');
        }
        
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
async function selectConversations(id) {

    userId =id 
   

    const response = await fetch('http://bot.fiacrekpanoutrade.com/user', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId })
    });

    const data = await response.json();

    alert(data)

    conversations = data

    currentConversation = conversations.find(c => c.id === id);
    if (!currentConversation) return;

    currentConversation.unread = 0;

    // Mobile: Cacher la sidebar et afficher le chat
    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.add('hidden');
        document.getElementById('chatArea').classList.remove('hidden');
    }

    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('chatContainer').style.display = 'flex';
    document.getElementById('chatUserName').textContent = currentConversation.name;

    renderMessages();
    renderConversations();
}
// SÉLECTIONNER UNE CONVERSATION
async function selectConversation(id) {
  const userId = id;

  let response;
  try {
    response = await fetch('https://bot.fiacrekpanoutrade.com/user', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId })
    });
  } catch (e) {
    console.log("Fetch failed:", e);
    alert("Erreur réseau: impossible de joindre l'API");
    return;
  }

  if (!response.ok) {
    const txt = await response.text();
    console.log("Backend error:", response.status, txt);
    alert("Erreur API: " + response.status);
    return;
  }

  const data = await response.json();

  // ✅ Debug propre (au lieu de alert(data))
  console.log("API data:", data);
  alert(JSON.stringify(data, null, 2)); // si tu veux vraiment voir

  // ✅ Garantir un tableau
  const conversations = Array.isArray(data) ? data : (data?.conversations ?? []);

  // ✅ Comparaison robuste (string vs number)
  const currentConversation = conversations.find(c => String(c.id) === String(id));
  if (!currentConversation) return;

  currentConversation.unread = 0;

  // Mobile: Cacher la sidebar et afficher le chat
  if (window.innerWidth <= 768) {
    document.getElementById('sidebar').classList.add('hidden');
    document.getElementById('chatArea').classList.remove('hidden');
  }

  document.getElementById('emptyState').style.display = 'none';
  document.getElementById('chatContainer').style.display = 'flex';
  document.getElementById('chatUserName').textContent = currentConversation.name;

  renderMessages();
  renderConversations();
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
                    ${msg.text}
                </div>
                <div class="message-time">${msg.time}</div>
            </div>
        `;

        container.appendChild(messageDiv);
    });

    container.scrollTop = container.scrollHeight;
}

// ENVOYER UN MESSAGE
function sendMessage() {
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
        sendImagesToTelegram(currentConversation.userId, newMessage.images, newMessage.text);
    } else {
        sendToTelegram(currentConversation.userId, text);
    }
}

// GÉRER L'UPLOAD D'IMAGE(S)
function handleImageUpload(event) {
    const files = event.target.files;
    if (!files || files.length === 0 || !currentConversation) return;

    pendingImages = [];
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    // Pour la démo, on affiche seulement la première image en preview
    // Mais on garde toutes les images dans pendingImages
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
    // Sauvegarder l'URL pour le téléchargement
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
function sendToTelegram(userId, text) {
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

function sendImagesToTelegram(userId, images, caption) {
    console.log('Envoi images à Telegram:', { userId, images, caption });
    
    // Exemple d'appel API:
    /*
    const formData = new FormData();
    formData.append('userId', userId);
    formData.append('caption', caption || '');
    
    images.forEach((img, index) => {
        // Convertir base64 en blob si nécessaire
        fetch(img)
            .then(res => res.blob())
            .then(blob => {
                formData.append(`image${index}`, blob);
            });
    });
    
    fetch('/api/send-images', {
        method: 'POST',
        body: formData
    });
    */
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

    // WebSocket pour messages en temps réel (à implémenter)
    /*
    const socket = new WebSocket('ws://votre-serveur.com');
    socket.onmessage = (event) => {
        const data = JSON.parse(event.data);
        // Ajouter le nouveau message à la conversation appropriée
        const conv = conversations.find(c => c.userId === data.userId);
        if (conv) {
            conv.messages.push({
                id: Date.now(),
                text: data.text,
                type: 'received',
                time: data.time,
                images: data.images
            });
            conv.lastMessage = data.text || '📷 Photo';
            conv.time = data.time;
            conv.unread += 1;
            
            if (currentConversation && currentConversation.id === conv.id) {
                renderMessages();
                conv.unread = 0;
            }
            renderConversations();
        }
    };
    */
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