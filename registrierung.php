<?php
session_start();
require_once 'db.php';

$meldung = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $benutzername = $_POST['benutzername'] ?? '';
    $email = $_POST['email'] ?? '';
    $passwort = $_POST['passwort'] ?? '';

    if ($benutzername && $email && $passwort) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO benutzer (benutzername, email, passwort) VALUES (:benutzername, :email, :passwort)");
        
        try {
            $stmt->execute([
                'benutzername' => $benutzername,
                'email' => $email,
                'passwort' => $passwort_hash
            ]);
            $meldung = "✅ Registrierung erfolgreich. Du kannst dich jetzt <a href='login.php'>einloggen</a>.";
        } catch (PDOException $e) {
            $meldung = "❌ Fehler: E-Mail-Adresse bereits vergeben?";
        }
    } else {
        $meldung = "⚠️ Bitte alle Felder ausfüllen.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
</head>
<body>
    <h2>Registrierung</h2>
    <?php if ($meldung): ?>
        <p><?php echo $meldung; ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Benutzername: <input type="text" name="benutzername" required></label><br>
        <label>E-Mail: <input type="email" name="email" required></label><br>
        <label>Passwort: <input type="password" name="passwort" required></label><br>
        <button type="submit">Registrieren</button>
    </form>
</body>
</html>
