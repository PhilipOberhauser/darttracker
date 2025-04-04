<?php
session_start();
session_unset();     // Alle Session-Variablen löschen
session_destroy();   // Die Session selbst zerstören

header("Location: startseite.php"); // Zurück zur Startseite-Seite
exit();
?>
