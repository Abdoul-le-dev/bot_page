<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Bot Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
    <script ></script>
</head>
<body>
    <div class="container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="header">
                <h1>Messages</h1>
                <div class="subtitle">Telegram Bot Manager</div>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Rechercher une conversation...">
            </div>

            <div class="conversations" id="conversationsList">
                <!-- Les conversations seront ajoutées ici dynamiquement -->
            </div>
        </div>

        <!-- MAIN CHAT AREA -->
        <div class="chat-area">
            <div id="emptyState" class="empty-state">
                <div class="empty-state-icon">💬</div>
                <h3>Aucune conversation sélectionnée</h3>
                <p>Sélectionnez une conversation pour commencer à répondre</p>
            </div>

            <div id="chatContainer" style="display: none; height: 100%; display: flex; flex-direction: column;">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <h2 id="chatUserName">Nom de l'utilisateur</h2>
                        <div class="chat-user-status">En ligne</div>
                    </div>
                    <div class="chat-actions">
                        <button onclick="archiveChat()">📁 Archiver</button>
                        <button onclick="blockUser()">🚫 Bloquer</button>
                    </div>
                </div>

                <div class="messages-container" id="messagesContainer">
                    <!-- Les messages seront ajoutés ici dynamiquement -->
                </div>

                <div class="input-area">
                    <div class="input-wrapper">
                        <div class="input-controls">
                            <button onclick="document.getElementById('fileInput').click()" title="Joindre une image">
                                📎
                            </button>
                            <button onclick="addEmoji()" title="Ajouter un emoji">
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
                    <input type="file" id="fileInput" accept="image/*" onchange="handleImageUpload(event)">
                </div>
            </div>
        </div>
    </div>

    <script src="/js.js"></script>
</body>
</html>