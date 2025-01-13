<?php
    require_once "cfg.php";
    require_once "admin_category.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
        if ($_POST["action"] == "add category") {
			$category_name = $_POST['category_name'];
			$mother = $_POST['mother'];

			$message = AddCategory($category_name, $mother, $conn);
            if ($message == null) {
				header("Location: " . $_SERVER['REQUEST_URI']);
				exit;
			}
		}
        else if ($_POST["action"] == "remove category") {
            $category_id = $_POST['category_id'];

            $message = RemoveCategory($category_id, $conn);
            if ($message == null) {
				header("Location: " . $_SERVER['REQUEST_URI']);
				exit;
			}
        }
		else if ($_POST["action"] == "edit category") {
			$category_id = $_POST['category_id'];
            $new_category_name = $_POST['new_category_name'];
            $new_mother = $_POST['new_mother'];

            $message = EditCategory($category_id, $new_category_name, $new_mother, $conn);
            if ($message == null) {
				header("Location: " . $_SERVER['REQUEST_URI']);
				exit;
			}
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

                    <button type="submit" name="action" value="add category">Dodaj Kategorię</button>
                </form>
            </div>

            <div>
                <h3>Usuń kategorię</h3>
                <form method="POST">
                    <label for="category_name">Nazwa kategorii:</label>
                    <select id="category_id" name="category_id">
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

            <div>
                <h3>Edytuj kategorię</h3>
                <form method="POST">
                    <label for="category_id">Nazwa kategorii:</label>
                    <select id="category_id" name="category_id" required>
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

                    <label for="new_category_name">Nowa nazwa kategorii:</label>
                    <input type="text" id="new_category_name" name="new_category_name">
                    <?php  ?>
                    <br><br>

                    <label for="new_mother">Nowa kategoria nadrzędna:</label>
                    <select id="new_mother" name="new_mother">
                        <option value="0">Brak (główna kategoria)</option>
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

                    <button type="submit" name="action" value="edit category">Edytuj Kategorię</button>
                </form>
            </div>
        </div>
	</div>

    <!-- Modal for Errors -->
    <div id="modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; border: 1px solid #ccc; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); z-index: 1000;">
        <p id="message"></p>
        <button onclick="closeModal()">Zamknij</button>
    </div>

    <script>
        function showMessage(message) {
            document.getElementById('message').textContent = message;
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>

	<?php
	if ($message !== null) {
		echo "<script>showMessage('" . addslashes($message) . "');</script>";
	}
	?>
</body>

</html>