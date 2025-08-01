<?php 
session_start();
$titel = "Dart-Tracker";
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo $titel ?></title>    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Code for handling game mode selection
        const dropdown = document.getElementById("spielmodi");
        const outmodus = document.getElementById("outmodus");
        const spielStartenLink = document.getElementById("spiel-starten");
        
        function updateGameLink() {
            const selectedValue = dropdown.value;
            const selectedOutMode = outmodus.value;
            spielStartenLink.href = `spiel.php?modus=${selectedValue}&outmode=${selectedOutMode}`;
        }
        
        dropdown?.addEventListener("change", updateGameLink);
        outmodus?.addEventListener("change", updateGameLink);
        
        // Set initial link
        if (dropdown && spielStartenLink) {
            updateGameLink();
        }

        // Existing favorite checkbox code
        const favoriteCheckbox = document.getElementById("favorite-checkbox");
        dropdown?.addEventListener("change", () => {
            const selectedOption = dropdown.options[dropdown.selectedIndex];
            favoriteCheckbox.checked = selectedOption.classList.contains("favorite-option");
        });
        favoriteCheckbox?.addEventListener("change", () => {
            const selectedOption = dropdown.options[dropdown.selectedIndex];
            if (favoriteCheckbox.checked) {
                selectedOption.classList.add("favorite-option");
            } else {
                selectedOption.classList.remove("favorite-option");
            }
        });
    });
    </script>
</head>

<body>
    <?php require_once 'header.php'; ?>
    <header>
        <img src="darttracker_logo.png" alt="DartTracker Logo">
    </header>

    <nav>
        <div class="nav-links">
            <a href="statistiken.php">Statistiken</a>
            <a href="vergangene_spiele.php">Vergangene Spiele</a>
            
        </div>
        <div class="auth-section">
            <?php if (isset($_SESSION['benutzername'])): ?>            <span class="user-name"><?php echo htmlspecialchars($_SESSION['benutzername']); ?></span>
            <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
            <a href="registrierung.php" class="btn">Registrieren</a>
            <a href="login.php" class="btn">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <h1 style="text-align: center;"><?php echo $titel ?></h1>

        <div class="dropdown-block">
            <label for="spielmodi">Spielmodus:</label>
            <select id="spielmodi" name="spielmodi">
                <?php 
                $spielmodi = [
                    101 => 101,
                    201 => 201,
                    301 => 301,
                    401 => 401,
                    501 => 501,
                    601 => 601,
                    701 => 701,
                    801 => 801,
                    901 => 901,
                    1001 => 1001
                ];
                foreach ($spielmodi as $value => $display): ?>
                    <option value="<?php echo $value ?>"><?php echo $display ?></option>
                <?php endforeach; ?>
            </select>

            <label for="outmodus">Out-Modus:</label>
            <select id="outmodus" name="outmodus">
                <option value="single">Single Out</option>
                <option value="double">Double Out</option>
            </select>

            <div>
                <input type="checkbox" id="favorite-checkbox">
                <label for="favorite-checkbox">Favorisieren</label>
            </div>
            <div style="margin-top: 30px; text-align: center;">
                <a href="spiel.php" class="btn" id="spiel-starten">🎯 Spiel starten</a>
            </div>
        </div>
    </main>
</body>

</html>