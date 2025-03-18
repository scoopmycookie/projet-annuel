<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $to = "rayan.ghossen@gmail.com"; 
    $subject = "Nouveau message de $name";
    $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

    $body = "Nom: $name\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Votre message a bien été envoyé !'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de l’envoi du message.'); window.location.href='contact.php';</script>";
    }
} else {
    header("Location: contact.php");
    exit;
}
?>
