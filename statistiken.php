<?php
session_start();
require_once 'db.php'; // nutzt $pdo aus deiner db.php

// Prüfen, ob Benutzer eingeloggt ist
if (!isset($_SESSION['benutzer_id'])) {
    echo "Bitte zuerst einloggen.";
    exit;
}

$spieler_id = $_SESSION['benutzer_id']; // entspricht der spieler_id in deiner Tabelle

try {
    // Statistiken gesamt (ohne zeitliche Begrenzung)
    $stmt = $pdo->prepare("
        SELECT AVG(punkte) AS durchschnitt, COUNT(*) AS wurfanzahl
        FROM wuerfe
        WHERE spieler_id = :spieler_id
    ");
    $stmt->bindParam(':spieler_id', $spieler_id, PDO::PARAM_INT);
    $stmt->execute();
    $daten = $stmt->fetch(PDO::FETCH_ASSOC);

    $durchschnitt = $daten['durchschnitt'] !== null ? number_format($daten['durchschnitt'], 2) : "Keine Daten";
    $wurfanzahl = $daten['wurfanzahl'] ?? 0;

} catch (PDOException $e) {
    echo "Fehler beim Abrufen der Statistiken: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Statistiken</title>
</head>
<header>
    <img src="darttracker_logo.png" alt="DartTracker Logo">
</header>
<body>
    <h1>Deine Wurf-Statistiken (Gesamt)</h1>
    <p><strong>Durchschnittliche Punkte:</strong> <?= $durchschnitt ?></p>
    <p><strong>Anzahl der Würfe:</strong> <?= $wurfanzahl ?></p>
    <p><a href="startseite.php">Zurück zur Startseite</a></p>
</body>
</html>
