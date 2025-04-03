<?php
require 'db.php'; // Externe Datenbankverbindung

$meldung = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $passwort = $_POST['passwort'] ?? '';

    if ($name && $email && $passwort) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
        $erstellt_am = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("INSERT INTO benutzer (name, email, passwort, erstellt_am) 
                               VALUES (:name, :email, :passwort, :erstellt_am)");
        try {
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'passwort' => $passwort_hash,
                'erstellt_am' => $erstellt_am
            ]);
            // Weiterleitung zum Login
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $meldung = "❌ Fehler beim Eintragen: " . $e->getMessage();
        }
    } else {
        $meldung = "⚠️ Bitte alle Felder ausfüllen.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrierung – DartTracker</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Benutzerkonto erstellen</h2>
    <?php if ($meldung): ?>
        <p><strong><?= htmlspecialchars($meldung) ?></strong></p>
    <?php endif; ?>
    <form method="post">
        <label for="name">Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label for="email">E-Mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="passwort">Passwort:</label><br>
        <input type="password" name="passwort" required><br><br>

        <input type="submit" value="Registrieren">
    </form>
</body>
</html>
