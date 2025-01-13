<?php
    require_once "connect.php";
    require_once "admin_category.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
        if ($_POST["action"] == "add category") {
			$category_name = $_POST['category_name'];
			$mother = $_POST['mother'];

			AddCategory($category_name, $mother, $conn);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
		}
        else if ($_POST["action"] == "remove category") {
            $category_id = $_POST['category_id'];

            RemoveCategory($category_id, $conn);
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit;
        }
    }
?>
<!DOCTYPE html>
<html lang = "pl">

<head>
	<title>Panel Administratora</title>
	<meta name="Author" content="Konrad Tetwejer" />
	<link rel="stylesheet" href="../css/style.css"/>
</head>

<body>
	<nav>
		<ul>
			<li><a href="index.php">Strona Główna</a></li>
			<li><a href="shop.php">Sklep</a> </li>
			<li><a href="cart.php">Koszyk</a></li>
			<li><a href="admin.php">Panel Administratora</a> </li>
		</ul>
	</nav>
	<div>
        <h1>Panel administratora</h1>
        <div>
            <h2>Zarządzanie kategoriami</h2>
            <?php GetCategoryTree(0, $conn); ?>
            <div>
                <h3>Dodaj kategorię</h3>
                <form method="POST">

                    <label for="category_name">Nazwa kategorii:</label>
                    <input type="text" id="category_name" name="category_name" required>
                    <br><br>

                    <label for="mother">Kategoria nadrzędna:</label>
                    <select id="mother" name="mother">
                        <option value="0">Brak (główna kategoria)</option>
                        <?php
                            $sql = "SELECT category_id, category_name FROM categories where mother = 0";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
                                }
                            }
                        ?>
                    </select>
                    <br><br>

                    <button type="submit" name="action" value="add category">Dodaj Kategorię</button>
                </form>
            </div>

            <div>
                <h3>Usuń kategorię</h3>
                <form method="POST">
                    <label for="category_name">Nazwa kategorii:</label>
                    <select id="mother" name="mother">
						<?php
						$sql = "SELECT category_id, category_name FROM categories";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								echo '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
							}
						}
						?>
                    </select>
                    <br><br>

                    <button type="submit" name="action" value="remove category">Usuń Kategorię</button>
                </form>
            </div>
        </div>
	</div>
</body>

</html>