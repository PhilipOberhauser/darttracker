<?php
session_start();
require_once 'db.php';

// Get game mode from URL, default to 501 if not set
$startScore = isset($_GET['modus']) ? (int)$_GET['modus'] : 501;

// Eingeloggten Benutzer als Spielername
$benutzername = $_SESSION['benutzername'] ?? 'Spieler 1';
$benutzer_id = $_SESSION['benutzer_id'] ?? null;

// Wenn noch keine Spielsession besteht, initialisieren
if (!isset($_SESSION['spiel'])) {
    $_SESSION['spiel'] = [
        'spieler' => [
            $benutzername => $startScore,
            'Spieler 2' => $startScore,
        ],
        'aktuellerSpieler' => $benutzername,
        'wurfCount' => 0,
        'gewinner' => null,
        'startZeit' => time(),
        'spielmodus' => $startScore // Save the game mode for reference
    ];
}

// Verarbeitung der Punkte-Eingabe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['punkte'])) {
    $punkte = (int) $_POST['punkte'];
    $aktueller = &$_SESSION['spiel']['aktuellerSpieler'];
    $spieler = &$_SESSION['spiel']['spieler'][$aktueller];

    // Punktestand abziehen
    $spieler -= $punkte;

    // Prüfen ob der Spieler gewonnen hat (genau 0)
    if ($spieler === 0) {
        $_SESSION['spiel']['gewinner'] = $aktueller;

        // Punkte die der Benutzer erzielt hat (501 - übrig)
        $verbleibendePunkte = $_SESSION['spiel']['spieler'][$benutzername];
        $erspieltePunkte = $startScore - $verbleibendePunkte;

        // In die Tabelle "spiele" einfügen (benutzer_id, datum, punkte)
        if ($benutzer_id !== null) {
            $stmt = $conn->prepare("INSERT INTO spiele (benutzer_id, datum, punkte) VALUES (?, NOW(), ?)");
            $stmt->bind_param("ii", $benutzer_id, $erspieltePunkte);
            $stmt->execute();
        }
    } elseif ($spieler < 0) {
        // Bei Unterpunkten wird der Wurf ignoriert (zurücksetzen auf vorherigen Stand)
        $spieler += $punkte;
    } else {
        $_SESSION['spiel']['wurfCount']++;
        // Spielerwechsel nach 3 Würfen
        if ($_SESSION['spiel']['wurfCount'] >= 3) {
            $_SESSION['spiel']['aktuellerSpieler'] = $_SESSION['spiel']['aktuellerSpieler'] === $benutzername ? 'Spieler 2' : $benutzername;
            $_SESSION['spiel']['wurfCount'] = 0;
        }
    }
}

// Reset
if (isset($_GET['reset'])) {
    unset($_SESSION['spiel']);
    header("Location: spiel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>DartTracker Spiel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h2>🎯 Dart Spiel</h2>

        <?php if ($_SESSION['spiel']['gewinner'] !== null): ?>
            <div class="dropdown-block">
                <h3>🎉 <?= $_SESSION['spiel']['gewinner'] ?> hat das Spiel gewonnen! 🎉</h3>
            </div>
        <?php else: ?>
            <p><strong>Aktueller Spieler:</strong> <?= $_SESSION['spiel']['aktuellerSpieler'] ?></p>

            <div class="dropdown-block">
                <form method="post">
                    <label for="punkte">Punkte:</label>
                    <select name="punkte" id="punkte" required>
                        <option value="0">Miss</option>
                        <optgroup label="Single">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>">S<?= $i ?></option>
                            <?php endfor; ?>
                        </optgroup>
                        <optgroup label="Double">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i * 2 ?>">D<?= $i ?></option>
                            <?php endfor; ?>
                        </optgroup>
                        <optgroup label="Triple">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i * 3 ?>">T<?= $i ?></option>
                            <?php endfor; ?>
                        </optgroup>
                        <option value="25">Bull (25)</option>
                        <option value="50">Bullseye (50)</option>
                    </select>
                    <button type="submit">Wurf eintragen</button>
                </form>
            </div>
        <?php endif; ?>

        <h3>Spielstand</h3>
        <table>
            <thead>
                <tr>
                    <th>Spieler</th>
                    <th>Punkte</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['spiel']['spieler'] as $name => $punkte): ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td><?= max($punkte, 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <a href="spiel.php?reset=true" class="btn">🔄 Neues Spiel starten</a>
            <a href="startseite.php" class="btn">🏠 Zurück zur Startseite</a>
        </div>
    </main>
</body>
</html>
