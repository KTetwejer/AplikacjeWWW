<?php
require_once "cfg.php";
require_once "admin_pages.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    if ($_POST["action"] == "add_page") {
        $page_name = $_POST["page_name"] ?? '';
        $page_content = $_POST["page_content"] ?? '';
        $message = AddPage($page_name, $page_content, $conn);
    }

    if ($_POST["action"] == "edit_page") {
        $page_id = $_POST["page_id"] ?? 0;
        $page_name = $_POST["page_name"] ?? '';
        $page_content = $_POST["page_content"] ?? '';
        $message = EditPage($page_id, $page_name, $page_content, $conn);
    }

    if ($_POST["action"] == "remove_page") {
        $page_id = $_POST["page_id"] ?? 0;
        $message = RemovePage($page_id, $conn);
    }
}
?>

<!DOCTYPE html>
<html lang = "pl">

<head>
	<title>DrinkMeister</title>
	<meta name="Author" content="Konrad Tetwejer" />
	<link rel="stylesheet" href="../css/style.css"/>
</head>

<body>
	<nav class="menu">
        <ul>
            <li><a href="index.php">Strona Główna</a></li>
            <li><a href="shop.php">Sklep</a> </li>
            <li><a href="cart.php">Koszyk</a></li>
            <li><a href="admin.php">Panel Administratora</a> </li>
            <li><a href="pages.php" class="active">Podstrony</a></li>
        </ul>
    </nav>

    <h2>Lista podstron</h2><br>
    <form method="GET" action="dynamic_page.php">
    <label for="page_title">Nazwa kategorii:</label>
    <select id="page_id" name="page_id">
        <?php
        $sql = "SELECT page_id, page_title FROM pages";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row["page_id"]) . '">' . htmlspecialchars($row["page_title"]) . '</option>';
            }
        }
        ?>
    </select>
    <br><br>
    <button type="submit">Przejdź do strony</button>
</form>


    <h2>Dodaj Nową Stronę</h2>
    <form method="POST">
        <label for="page_name">Tytuł strony:</label><br>
        <input type="text" id="page_name" name="page_name" required><br><br>
        <label for="page_content">Treść strony:</label><br>
        <textarea id="page_content" name="page_content" rows="5" cols="40" required></textarea><br><br>
        <button type="submit" name="action" value="add_page">Dodaj Stronę</button>
    </form>

    <h2>Edytuj Istniejącą Stronę</h2>
    <form method="POST">
        <label for="page_id">Wybierz stronę do edycji:</label><br>
        <select id="page_id" name="page_id" required>
            <option value="" disabled selected>-- Wybierz stronę --</option>
            <?php
            $result = $conn->query("SELECT page_id, page_title FROM pages");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['page_id']) . '">' . htmlspecialchars($row['page_title']) . '</option>';
                }
            }
            ?>
        </select><br><br>
        <label for="page_name">Nowy tytuł strony:</label><br>
        <input type="text" id="page_name" name="page_name" required><br><br>
        <label for="page_content">Nowa treść strony:</label><br>
        <textarea id="page_content" name="page_content" rows="5" cols="40" required></textarea><br><br>
        <button type="submit" name="action" value="edit_page">Edytuj Stronę</button>
    </form>

    <br><h2>Usuwanie podstron</h2>
    <form method="POST">
    <label for="page_title">Nazwa strony do usunięcia</label>
    <select id="page_id" name="page_id">
        <?php
        $sql = "SELECT page_id, page_title FROM pages";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row["page_id"]) . '">' . htmlspecialchars($row["page_title"]) . '</option>';
            }
        }
        ?>
    </select>
    <br><br>
    <button type="submit" name="action" value="remove_page">Usuń stronę</button>

</body>

</html>