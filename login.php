<?php
session_start();
require 'db.php';

$meldung = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $passwort = $_POST['passwort'] ?? '';

    if ($email && $passwort) {
        $stmt = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $benutzer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($benutzer && password_verify($passwort, $benutzer['passwort'])) {
            $_SESSION['benutzer_id'] = $benutzer['id'];
            $_SESSION['benutzer_name'] = $benutzer['name'];
            header("Location: startseite.php");
            exit;
        } else {
            $meldung = "❌ Falsche E-Mail oder Passwort.";
        }
    } else {
        $meldung = "⚠️ Bitte alle Felder ausfüllen.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login – DartTracker</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Login</h2>
    <?php if ($meldung): ?>
        <p><strong><?= htmlspecialchars($meldung) ?></strong></p>
    <?php endif; ?>
    <form method="post">
        <label for="email">E-Mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="passwort">Passwort:</label><br>
        <input type="password" name="passwort" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
