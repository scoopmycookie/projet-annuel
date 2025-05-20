<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement des Packs Business Care</title>
  <script 
    src="https://www.paypal.com/sdk/js?client-id=BAA8yKyeGIqZSF3pPQh_uq2S9xNS-ALeiQMq01xds7qF5DLhdk1vEwPrcIy0NaZQT9Zdj1zP_FkBYIHlsQ&components=hosted-buttons&disable-funding=venmo&currency=EUR">
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
    <h3>Pack Starter</h3>
    <p>180€ / salarié</p>
    <div id="paypal-container-starter" class="paypal-button"></div>
  </div>

  <div class="pack">
    <h3>Pack Basic</h3>
    <p>150€ / salarié</p>
    <div id="paypal-container-basic" class="paypal-button"></div>
  </div>

  <div class="pack">
    <h3>Pack Premium</h3>
    <p>100€ / salarié</p>
    <div id="paypal-container-premium" class="paypal-button"></div>
  </div>

  <script>
    paypal.HostedButtons({
      hostedButtonId: "CLDQG9Z9VXT3E"
    }).render("#paypal-container-starter");

    paypal.HostedButtons({
      hostedButtonId: "VHZSXHSFSD2SL"
    }).render("#paypal-container-basic");

    paypal.HostedButtons({
      hostedButtonId: "CNLMRUWS8B2XL"
    }).render("#paypal-container-premium");
  </script>
</body>
</html>
