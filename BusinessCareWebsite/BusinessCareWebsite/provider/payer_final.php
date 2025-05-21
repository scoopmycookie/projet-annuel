<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement des Packs Business Care</title>
  <script 
    src="https://www.paypal.com/sdk/js?client-id=BAA8yKyeGIqZSF3pPQh_uq2S9xNS-ALeiQMq01xds7qF5DLhdk1vEwPrcIy0NaZQT9Zdj1zP_FkBYIHlsQ&currency=EUR">
  </script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      padding: 40px;
    }
    .pack {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .pack h3 {
      margin-top: 0;
    }
    .paypal-button {
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <h2>Sélection d’un Pack Business Care</h2>

  <div class="pack">
    <h3>Pack dynamique (Smart Button)</h3>
    <form id="smart-form">
      <label for="pack">Choisissez un pack :</label>
      <select id="pack">
        <option value="starter">Starter - 180€</option>
        <option value="basic">Basic - 150€</option>
        <option value="premium">Premium - 100€</option>
      </select>
      <label for="employees">Nombre de salariés :</label>
      <input type="number" id="employees" value="1" min="1">
    </form>
    <div id="paypal-button-smart" class="paypal-button"></div>
  </div>

  <script>
    const pricing = {
      starter: 180,
      basic: 150,
      premium: 100
    };

    function calculateTotal() {
      const pack = document.getElementById('pack').value;
      const employees = parseInt(document.getElementById('employees').value) || 1;
      return pricing[pack] * employees;
    }

    paypal.Buttons({
      createOrder: function(data, actions) {
        const total = calculateTotal();
        const pack = document.getElementById('pack').value;
        const employees = document.getElementById('employees').value;
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: total.toFixed(2)
            },
            description: `Pack ${pack} - ${employees} salarié(s)`
          }]
        });
      },
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          alert('Paiement réussi par ' + details.payer.name.given_name);
        });
      }
    }).render('#paypal-button-smart');
  </script>
</body>
</html>
