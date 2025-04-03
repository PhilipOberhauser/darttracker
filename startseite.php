<?php



$titel = "Dart-Tracker"

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
            padding: 10px 15px;
            cursor: pointer;
        }
        .dropdown {
            margin-left: auto;
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
            const toggleFavoriteButton = document.getElementById("toggle-favorite");

            // Update dropdown to toggle favorite status
            dropdown.addEventListener("change", () => {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                if (selectedOption.classList.contains("favorite-option")) {
                    toggleFavoriteButton.textContent = "Als Favorit markieren";
                } else {
                    toggleFavoriteButton.textContent = "Favorit entfernen";
                }
            });

            // Toggle favorite status on button click
            toggleFavoriteButton.addEventListener("click", () => {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                if (selectedOption.classList.contains("favorite-option")) {
                    selectedOption.classList.remove("favorite-option");
                    selectedOption.textContent = selectedOption.value;
                    toggleFavoriteButton.textContent = "Als Favorit markieren";
                } else {
                    selectedOption.classList.add("favorite-option");
                    selectedOption.textContent = `${selectedOption.value} ★`;
                    toggleFavoriteButton.textContent = "Favorit entfernen";
                }
            });
        });
    </script>
</head>
<body>
    <header>
        <div class="nav-buttons">
            <button onclick="location.href='#'">Seite 1</button>
            <button onclick="location.href='#'">Seite 2</button>
            <button onclick="location.href='#'">Seite 3</button>
            <button onclick="location.href='#'">Seite 4</button>
            <button onclick="location.href='#'">Seite 5</button>
        </div>
    </header>

    <h1> <?php echo $titel ?></h1>

    <div class="dropdown-block">
        <label for="spielmodi">Spielmodus:</label>
        <select id="spielmodi" name="spielmodi">
            <option value="101">101 </option>
            <option value="201">201</option>
            <option value="301" >301 </option>
            <option value="401">401</option>
            <option value="501" >501 </option>
            <option value="601">601</option>
            <option value="701">701</option>
            <option value="801">801</option>
            <option value="901">901</option>
            <option value="1001">1001</option>
        </select>
        <button id="toggle-favorite">Favorit entfernen</button>
    </div>
    
</body>
</html>
