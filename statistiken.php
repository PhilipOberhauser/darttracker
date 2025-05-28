<?php
session_start();
require_once 'db.php'; // nutzt $pdo aus deiner db.php

if (!isset($_SESSION['benutzer_id'])) {
    echo "Bitte zuerst einloggen.";
    exit;
}

$spieler_id = $_SESSION['benutzer_id'];

try {
    // All-Time Statistik
    $stmt_all = $pdo->prepare("
        SELECT AVG(wurfwert) AS durchschnitt, COUNT(*) AS wurfanzahl
        FROM wuerfe
        WHERE benutzer_id = :spieler_id
    ");
    $stmt_all->execute(['spieler_id' => $spieler_id]);
    $alltime = $stmt_all->fetch(PDO::FETCH_ASSOC);

    $durchschnitt_all = $alltime['durchschnitt'] !== null ? number_format($alltime['durchschnitt'], 2) : "Keine Daten";
    $anzahl_all = $alltime['wurfanzahl'] ?? 0;

    // Letzte 30 Tage
    $stmt_30 = $pdo->prepare("
        SELECT AVG(wurfwert) AS durchschnitt, COUNT(*) AS wurfanzahl
        FROM wuerfe
        WHERE benutzer_id = :spieler_id
    ");
    $stmt_30->execute(['spieler_id' => $spieler_id]);
    $last30 = $stmt_30->fetch(PDO::FETCH_ASSOC);

    $durchschnitt_30 = $last30['durchschnitt'] !== null ? number_format($last30['durchschnitt'], 2) : "Keine Daten";
    $anzahl_30 = $last30['wurfanzahl'] ?? 0;

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
<body>
    <?php require_once 'header.php'; ?>
    <h1>Deine Wurf-Statistiken</h1>

    <main>
        <h2>⏱️ Letzte 30 Tage</h2>
        <p><strong>Durchschnittliche Punkte:</strong> <?= $durchschnitt_30 ?></p>
        <p><strong>Anzahl der Würfe:</strong> <?= $anzahl_30 ?></p>

        <h2>📊 Gesamt</h2>
        <p><strong>Durchschnittliche Punkte:</strong> <?= $durchschnitt_all ?></p>
        <p><strong>Anzahl der Würfe:</strong> <?= $anzahl_all ?></p>

        <p><a class="btn" href="startseite.php">Zurück zur Startseite</a></p>
    </main>
</body>
</html>
