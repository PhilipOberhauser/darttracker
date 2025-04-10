<?php
session_start();
if (!isset($_SESSION['benutzername'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php'; // Ensure this file contains the database connection logic

$benutzername = $_SESSION['benutzername']; // Use the correct session variable
$sql = "SELECT id, datum, punkte FROM spiel WHERE benutzer_id = :benutzer_id"; // Adjust table and column names
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':benutzer_id', $benutzername, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vergangene Spiele</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <header>
        <button onclick="location.href='startseite.php'">Zurück zur Startseite</button>
    </header>

    <h1>Vergangene Spiele</h1>

    <?php if (count($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Spiel-ID</th>
                    <th>Datum</th>
                    <th>Punkte</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['datum']); ?></td>
                        <td><?php echo htmlspecialchars($row['punkte']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Keine vergangenen Spiele gefunden.</p>
    <?php endif; ?>
</body>
</html>