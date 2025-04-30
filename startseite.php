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
    <title><?php echo $titel ?></title>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Existing theme toggle code
        const toggleBtn = document.getElementById("theme-toggle");
        toggleBtn?.addEventListener("click", () => {
            document.body.classList.toggle("light-mode");
            toggleBtn.innerText = document.body.classList.contains("light-mode") ? "🌞" : "🌙";
        });

        // New code for handling game mode selection
        const dropdown = document.getElementById("spielmodi");
        const spielStartenLink = document.getElementById("spiel-starten");
        
        // Update link when dropdown changes
        dropdown?.addEventListener("change", () => {
            const selectedValue = dropdown.value;
            spielStartenLink.href = `spiel.php?modus=${selectedValue}`;
        });

        // Set initial link value
        if (dropdown && spielStartenLink) {
            spielStartenLink.href = `spiel.php?modus=${dropdown.value}`;
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
    <header>
        <img src="darttracker_logo.png" alt="DartTracker Logo">
    </header>

    <nav>
        <div class="nav-links">
            <a href="statistiken.php">Statistiken</a>
            <a href="vergangene_spiele.php">Vergangene Spiele</a>
            <a href="#">Seite 3</a>
            <a href="#">Seite 4</a>
            <a href="#">Seite 5</a>
        </div>
        <div class="auth-section">
            <?php if (isset($_SESSION['benutzername'])): ?>
            <span class="user-name"><?php echo htmlspecialchars($_SESSION['benutzername']); ?></span>
            <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
            <a href="registrierung.php" class="btn">Registrieren</a>
            <a href="login.php" class="btn">Login</a>
            <?php endif; ?>
            <button id="theme-toggle" class="theme-icon-btn">🌙</button>
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