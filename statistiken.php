<?php
session_start();
require_once "db.php"; // Verbindungsdatei

$titel = "Statistiken";

// Nur eingeloggte Benutzer dürfen die Seite sehen
if (!isset($_SESSION['benutzername'])) {
    header("Location: login.php");
    exit();
}

$benutzername = $_SESSION['benutzername'];

// SQL-Abfrage: Spiele samt Datum, Average per Set und Leg
$sql = "
    SELECT 
        s.id AS spiel_id,
        s.datum,
        u.benutzername,
        ROUND(SUM(w1.punkte) / COUNT(DISTINCT l.id), 2) AS average_leg,
        ROUND(SUM(w1.punkte) / COUNT(DISTINCT se.id), 2) AS average_set
    FROM spiele s
    JOIN benutzer u ON u.id = s.benutzer_id
    JOIN legs l ON l.spiel_id = s.id
    JOIN sets se ON se.spiel_id = s.id
    JOIN wuerfe w1 ON w1.leg_id = l.id
    WHERE u.benutzername = ?
    GROUP BY s.id
    ORDER BY s.datum DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $benutzername);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titel; ?></title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>

<h1><?php echo $titel; ?></h1>

<table>
    <thead>
        <tr>
            <th>Benutzer</th>
            <th>Datum</th>
            <th>Average pro Leg</th>
            <th>Average pro Set</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['benutzername']); ?></td>
            <td><?php echo htmlspecialchars($row['datum']); ?></td>
            <td><?php echo htmlspecialchars($row['average_leg']); ?></td>
            <td><?php echo htmlspecialchars($row['average_set']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
