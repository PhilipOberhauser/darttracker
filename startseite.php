<?php
session_start();
$titel = "Dart-Tracker";
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titel ?></title>
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #f4f4f4;
            border-bottom: 1px solid #ccc;
        }
        .nav-buttons {
            display: flex;
            gap: 10px;
        }
        .nav-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .nav-buttons button:hover {
            background-color: #218838;
        }
        .auth-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .auth-buttons a {
            text-decoration: none;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .auth-buttons a:hover {
            background-color: #0056b3;
        }
        .dropdown-block {
            margin: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .favorite-option {
            font-weight: bold;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropdown = document.getElementById("spielmodi");
            const favoriteCheckbox = document.getElementById("favorite-checkbox");

            dropdown.addEventListener("change", () => {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                favoriteCheckbox.checked = selectedOption.classList.contains("favorite-option");
            });

            favoriteCheckbox.addEventListener("change", () => {
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
        <div class="nav-buttons">
            <button onclick="location.href='statistiken.php'">Statistiken</button>
            <button onclick="location.href='vergangene_spiele.php'">vergangene Spiele</button>
            <button onclick="location.href='#'">Seite 3</button>
            <button onclick="location.href='#'">Seite 4</button>
            <button onclick="location.href='#'">Seite 5</button>
        </div>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['benutzername'])): ?>
                <span><?php echo htmlspecialchars($_SESSION['benutzername']); ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="registrierung.php">Registrieren</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </header>

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
</body>
</html>
