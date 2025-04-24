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
            const toggleBtn = document.getElementById("theme-toggle");
            toggleBtn?.addEventListener("click", () => {
                document.body.classList.toggle("light-mode");
                toggleBtn.innerText = document.body.classList.contains("light-mode") ? "🌞" : "🌙";
            });

            const dropdown = document.getElementById("spielmodi");
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
                <?php foreach ([101, 201, 301, 401, 501, 601, 701, 801, 901, 1001] as $modus): ?>
                    <option value="<?php echo $modus ?>"><?php echo $modus ?></option>
                <?php endforeach; ?>
            </select>
            <div>
                <input type="checkbox" id="favorite-checkbox">
                <label for="favorite-checkbox">Favorisieren</label>
            </div>
        </div>
    </main>
</body>
</html>
