<?php
session_start();
require_once 'db.php';

// Get game mode and out mode from URL
$startScore = isset($_GET['modus']) ? (int)$_GET['modus'] : 501;
$outMode = isset($_GET['outmode']) ? $_GET['outmode'] : 'single';

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
        'spielmodus' => $startScore,
        'outMode' => $outMode
    ];
}

// Verarbeitung der Punkte-Eingabe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['punkte'])) {
    $punkte = (int) $_POST['punkte'];
    $wurfTyp = $_POST['wurftyp'];
    $aktueller = &$_SESSION['spiel']['aktuellerSpieler'];
    $spieler = &$_SESSION['spiel']['spieler'][$aktueller];

    // Speichere jeden Wurf in der Datenbank
    if ($aktueller === $benutzername) { // Nur Würfe des eingeloggten Spielers speichern
        try {
            $stmt = $pdo->prepare("INSERT INTO wuerfe (wurf_id, benutzer_id, spiel_id, wurfwert) VALUES (NULL, :benutzer_id, :spiel_id, :wurfwert)");
            $stmt->execute([
                ':benutzer_id' => $benutzer_id,
                ':spiel_id' => $_SESSION['spiel']['current_game_id'] ?? 0,
                ':wurfwert' => $punkte
            ]);
        } catch (PDOException $e) {
            error_log("Fehler beim Speichern des Wurfs: " . $e->getMessage());
        }
    }

    $neuerPunktestand = $spieler - $punkte;

    if ($neuerPunktestand === 0) {
        if ($_SESSION['spiel']['outMode'] === 'double' && $wurfTyp !== 'double') {
            // Bei Double Out muss mit einem Double beendet werden
            $neuerPunktestand = $spieler; // Wurf ungültig
        } else {
            $_SESSION['spiel']['gewinner'] = $aktueller;

            // Punkte die der Benutzer erzielt hat
            if ($aktueller === $benutzername) {
                try {
                    // Das Startspiel minus die Würfe die benötigt wurden
                    $spielzuege = $_SESSION['spiel']['wurfCount'] + 
                                (floor($_SESSION['spiel']['wurfCount'] / 3) * 3);
                    
                    $stmt = $pdo->prepare("INSERT INTO spiele (id, datum, punkte) VALUES (:id, NOW(), :punkte)");
                    $stmt->execute([
                        ':id' => $benutzer_id,
                        ':punkte' => $spielzuege
                    ]);
                } catch (PDOException $e) {
                    error_log("Datenbankfehler: " . $e->getMessage());
                }
            }
        }
    }

    if ($neuerPunktestand < 0) {
        $neuerPunktestand = $spieler; // Wurf ungültig
    }

    $spieler = $neuerPunktestand;

    $_SESSION['spiel']['wurfCount']++;
    // Spielerwechsel nach 3 Würfen
    if ($_SESSION['spiel']['wurfCount'] >= 3) {
        $_SESSION['spiel']['aktuellerSpieler'] = $_SESSION['spiel']['aktuellerSpieler'] === $benutzername ? 'Spieler 2' : $benutzername;
        $_SESSION['spiel']['wurfCount'] = 0;
    }
}

// Reset - preserve game mode and out mode
if (isset($_GET['reset'])) {
    $currentModus = $_SESSION['spiel']['spielmodus'];
    $currentOutMode = $_SESSION['spiel']['outMode'];
    unset($_SESSION['spiel']);
    header("Location: spiel.php?modus=$currentModus&outmode=$currentOutMode");
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
    <?php require_once 'header.php'; ?>
    <main>
        <h2>🎯 Dart Spiel</h2>
        <?php if (isset($_SESSION['spiel']['gewinner']) && $_SESSION['spiel']['gewinner'] !== null): ?>
            <div class="dropdown-block">
                <h3>🎉 <?= htmlspecialchars($_SESSION['spiel']['gewinner']) ?> hat das Spiel gewonnen! 🎉</h3>
                <div style="margin-top: 20px;">
                    <a href="spiel.php?reset=true&modus=<?= $_SESSION['spiel']['spielmodus'] ?>&outmode=<?= $_SESSION['spiel']['outMode'] ?>" class="btn">🔄 Neues Spiel starten</a>
                    <a href="startseite.php" class="btn">🏠 Zurück zur Startseite</a>
                </div>
            </div>
        <?php else: ?>
            <p><strong>Aktueller Spieler:</strong> <?= $_SESSION['spiel']['aktuellerSpieler'] ?></p>

            <div class="dropdown-block">
                <form method="post">
                    <label for="punkte">Punkte:</label>
                    <select name="punkte" id="punkte" required>
                        <option value="0" data-type="single">Miss</option>
                        <optgroup label="Single">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>" data-type="single">S<?= $i ?></option>
                            <?php endfor; ?>
                        </optgroup>
                        <optgroup label="Double">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i * 2 ?>" data-type="double">D<?= $i ?></option>
                            <?php endfor; ?>
                        </optgroup>
                        <optgroup label="Triple">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i * 3 ?>" data-type="triple">T<?= $i ?></option>
                            <?php endfor; ?>
                        </optgroup>
                        <option value="25" data-type="single">Bull (25)</option>
                        <option value="50" data-type="double">Bullseye (50)</option>
                    </select>
                    <input type="hidden" name="wurftyp" id="wurftyp" value="single">
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
            <a href="spiel.php?reset=true&modus=<?= $_SESSION['spiel']['spielmodus'] ?>&outmode=<?= $_SESSION['spiel']['outMode'] ?>" class="btn">🔄 Neues Spiel starten</a>
            <a href="startseite.php" class="btn">🏠 Zurück zur Startseite</a>
        </div>
    </main>

    <script>
        document.getElementById('punkte').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('wurftyp').value = selectedOption.dataset.type;
        });
    </script>
</body>
</html>
