<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Bot Manager-----------</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    

    <div class="container">
        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="header">
                <h1>Messages</h1>
                <div class="subtitle">Telegram Bot Manager</div>
                <button id="send">yes </button>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Rechercher une conversation...">
            </div>

            <div class="conversations" id="conversationsList">
                <!-- Les conversations seront ajoutées ici dynamiquement -->
            </div>
        </div>

        <!-- MAIN CHAT AREA -->
        <div class="chat-area" id="chatArea">
            <!-- Bouton retour mobile -->
            <button class="mobile-back-btn" id="mobileBackBtn" onclick="goBackToConversations()">
                ← Retour
            </button>

            <div id="emptyState" class="empty-state">
                <div class="empty-state-icon">💬</div>
                <h3>Aucune conversation sélectionnée</h3>
                <p>Sélectionnez une conversation pour commencer à répondre</p>
            </div>

            <div id="chatContainer" style="display: none;">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <h2 id="chatUserName">Nom de l'utilisateur</h2>
                        <div class="chat-user-status">En ligne</div>
                    </div>
                    <div class="chat-actions">
                        <button onclick="archiveChat()" class="action-btn">📁</button>
                        <button onclick="blockUser()" class="action-btn">🚫</button>
                    </div>
                </div>

                <div class="messages-container" id="messagesContainer">
                    <!-- Les messages seront ajoutés ici dynamiquement -->
                </div>

                <div class="input-area">
                    <!-- Emoji Picker -->
                    <div class="emoji-picker" id="emojiPicker" style="display: none;">
                        <div class="emoji-grid" id="emojiGrid"></div>
                    </div>

                    <!-- Image Preview -->
                    <div class="image-preview-container" id="imagePreviewContainer" style="display: none;">
                        <div class="image-preview-wrapper">
                            <img id="imagePreview" src="" alt="Preview">
                            <button class="image-preview-close" onclick="cancelImageUpload()">✕</button>
                            <input type="text" id="imageCaption" placeholder="Ajouter une légende..." class="image-caption-input">
                        </div>
                    </div>

                    <div class="input-wrapper">
                        <div class="input-controls">
                            <button onclick="document.getElementById('fileInput').click()" title="Joindre une image">
                                📎
                            </button>
                            <button onclick="toggleEmojiPicker()" title="Ajouter un emoji">
                                😊
                            </button>
                        </div>
                        <div class="message-input-wrapper">
                            <textarea 
                                id="messageInput" 
                                class="message-input" 
                                placeholder="Tapez votre message..."
                                rows="1"
                            ></textarea>
                        </div>
                        <button class="send-button" onclick="sendMessage()">
                            ➤
                        </button>
                    </div>
                    <input type="file" id="fileInput" accept="image/*" onchange="handleImageUpload(event)" multiple>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox pour visualiser les images -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <button class="lightbox-close">✕</button>
        <img id="lightboxImage" src="" alt="Image">
        <div class="lightbox-controls">
            <button onclick="downloadImage(event)">⬇ Télécharger</button>
        </div>
    </div>

    
   


    <script src="js.js"></script>
</body>
</html>