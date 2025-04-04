<?php
session_start();
require_once 'db.php'; // Datenbankverbindung

$fehler = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $passwort = $_POST['passwort'] ?? '';

    if ($email && $passwort) {
        $stmt = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($passwort, $user['passwort'])) {
            $_SESSION['benutzername'] = $user['name']; // <-- Feld "name" aus der Datenbank
            header("Location: startseite.php");
            exit();
        } else {
            $fehler = "❌ E-Mail oder Passwort ist falsch.";
        }
    } else {
        $fehler = "⚠️ Bitte fülle alle Felder aus.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($fehler): ?>
        <p style="color:red;"><?php echo $fehler; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>E-Mail: <input type="email" name="email" required></label><br>
        <label>Passwort: <input type="password" name="passwort" required></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
