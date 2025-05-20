document.addEventListener("DOMContentLoaded", () => {
  const steps = [
    {
      title: "Bienvenue !",
      text: "Vous êtes maintenant connecté à votre espace salarié sur Business Care.",
    },
    {
      title: "Vos prestations 📅",
      text: "Accédez à votre planning et réservez des services bien-être offerts par votre entreprise.",
    },
    {
      title: "Messages 💬",
      text: "Contactez vos prestataires ou recevez des rappels directement ici.",
    },
    {
      title: "Profil 🔒",
      text: "Mettez à jour vos informations personnelles à tout moment.",
    },
    {
      title: "C’est parti ! 🚀",
      text: "Vous pouvez maintenant utiliser le site. Bonne expérience !",
    },
  ];

  let currentStep = 0;

  const overlay = document.createElement("div");
  overlay.style.position = "fixed";
  overlay.style.top = 0;
  overlay.style.left = 0;
  overlay.style.width = "100vw";
  overlay.style.height = "100vh";
  overlay.style.background = "rgba(0,0,0,0.85)";
  overlay.style.zIndex = 9999;
  overlay.style.display = "flex";
  overlay.style.flexDirection = "column";
  overlay.style.justifyContent = "center";
  overlay.style.alignItems = "center";
  overlay.style.color = "#fff";
  overlay.style.padding = "20px";
  overlay.innerHTML = `
    <div id="tuto-box" style="background:#fff;color:#333;padding:30px;border-radius:12px;max-width:500px;text-align:center">
      <h2 id="tuto-title"></h2>
      <p id="tuto-text" style="margin: 15px 0;"></p>
      <button id="tuto-next" style="margin-top: 20px;padding: 10px 20px;border:none;border-radius:5px;background:#003566;color:white;cursor:pointer;">
        Suivant
      </button>
    </div>
  `;
  document.body.appendChild(overlay);

  const title = document.getElementById("tuto-title");
  const text = document.getElementById("tuto-text");
  const button = document.getElementById("tuto-next");

  function renderStep(i) {
    title.innerText = steps[i].title;
    text.innerText = steps[i].text;
    button.innerText = i === steps.length - 1 ? "Terminer" : "Suivant";
  }

  button.addEventListener("click", () => {
    if (currentStep < steps.length - 1) {
      currentStep++;
      renderStep(currentStep);
    } else {
      overlay.remove();

      fetch("../employee/end_tutorial.php", {
        method: "POST"
      });
    }
  });

  renderStep(currentStep);
});
