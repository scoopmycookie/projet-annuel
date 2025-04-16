<?php if (!$user['has_seen_tutorial']): ?>
    <div id="tutorial-banner" style="
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #1f1f1f;
        color: white;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        z-index: 999;
        max-width: 500px;
        text-align: center;
    ">
        <p style="margin-bottom: 10px;">
            👋 Bienvenue ! Voici un aperçu de votre espace. Besoin d’aide ? Consultez nos guides ou explorez le menu !
        </p>
        <button onclick="endTutorial()" style="
            padding: 8px 16px;
            background: #ff9800;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        ">C'est compris</button>
    </div>

    <script>
        function endTutorial() {
            fetch("mark_tutorial_seen.php")
                .then(() => {
                    const banner = document.getElementById("tutorial-banner");
                    if (banner) banner.style.display = "none";
                });
        }
    </script>
<?php endif; ?>
