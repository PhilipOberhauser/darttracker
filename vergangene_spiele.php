<?php
session_start();
if (!isset($_SESSION['benutzername'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$benutzer_id = $_SESSION['benutzer_id'] ?? null; // Get the user ID from session
if ($benutzer_id === null) {
    die("Benutzer-ID nicht gefunden");
}

// Updated SQL query with correct column names
$sql = "SELECT spiele.id, spiele.id as benutzer_id, datum 
        FROM spiele 
        WHERE spiele.id = :benutzer_id 
        ORDER BY spiele.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':benutzer_id', $benutzer_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vergangene Spiele</title>
</head>
<body>    <?php require_once 'header.php'; ?>

    <div class="page-nav">
        <button onclick="location.href='startseite.php'" class="btn">Zurück zur Startseite</button>
    </div>

    <h1>Vergangene Spiele</h1>

    <?php if (count($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Spiel-ID</th>
                    <th>Benutzer-ID</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['benutzer_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['datum']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>#
        </table>
    <?php else: ?>
        <p>Keine vergangenen Spiele gefunden.</p>
    <?php endif; ?>
</body>
</html>