<?php
$uri = $_SERVER['REQUEST_URI'];
$inAdmin = strpos($uri, '/admin/') !== false;
$role = $_SESSION['user']['role'] ?? null;

if (!$inAdmin && $role === 'employee'):
?>
<style>
#chatbot-button {
    position: fixed;
    bottom: 25px;
    right: 25px;
    background-color: #1976d2;
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-size: 24px;
    cursor: pointer;
    z-index: 1000;
}

#chatbot-window {
    display: none;
    position: fixed;
    bottom: 100px;
    right: 25px;
    width: 300px;
    height: 400px;
    background: white;
    border: 1px solid #ccc;
    border-radius: 10px;
    z-index: 1000;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    flex-direction: column;
    overflow: hidden;
}

#chatbot-header {
    background: #1976d2;
    color: white;
    padding: 10px;
    font-weight: bold;
}

#chatbot-messages {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    font-size: 14px;
}

#chatbot-input {
    display: flex;
    border-top: 1px solid #ccc;
}

#chatbot-input input {
    flex: 1;
    border: none;
    padding: 10px;
}

#chatbot-input button {
    border: none;
    background: #1976d2;
    color: white;
    padding: 10px;
}
</style>

<div id="chatbot-button">💬</div>

<div id="chatbot-window">
    <div id="chatbot-header">Assistance</div>
    <div id="chatbot-messages">
        <p><strong>Bot :</strong> Bonjour ! Comment puis-je vous aider ?</p>
    </div>
    <div id="chatbot-input">
        <input type="text" id="chatbot-text" placeholder="Votre message...">
        <button onclick="sendChat()">Envoyer</button>
    </div>
</div>

<script>
document.getElementById('chatbot-button').addEventListener('click', () => {
    const win = document.getElementById('chatbot-window');
    win.style.display = win.style.display === 'none' ? 'flex' : 'none';
});

function sendChat() {
    const input = document.getElementById('chatbot-text');
    const msg = input.value.trim();
    if (!msg) return;

    const messages = document.getElementById('chatbot-messages');
    messages.innerHTML += `<p><strong>Vous :</strong> ${msg}</p>`;

    let response = "Désolé, je n'ai pas compris.";
    if (msg.toLowerCase().includes('horaire')) response = "Nos horaires sont de 9h à 18h.";
    if (msg.toLowerCase().includes('contact')) response = "Vous pouvez nous contacter à support@businesscare.fr";
    if (msg.toLowerCase().includes('rdv')) response = "Vous pouvez réserver un rendez-vous via votre espace personnel.";

    messages.innerHTML += `<p><strong>Bot :</strong> ${response}</p>`;
    messages.scrollTop = messages.scrollHeight;
    input.value = '';
}
</script>
<?php endif; ?>
