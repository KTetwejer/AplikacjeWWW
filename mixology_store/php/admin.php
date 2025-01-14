<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    require_once "cfg.php";
    require_once "admin_category.php";
    require_once "admin_products.php";

    $message = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
        if ($_POST["action"] == "logout") {
            session_unset();
            session_destroy();
            $message = "Wylogowano!";
            header("Location: login.php");
            exit;
        }

        if ($_POST["action"] == "add_category") {
			$category_name = $_POST['category_name'];
			$mother = $_POST['mother'];

			$message = AddCategory($category_name, $mother, $conn);
            echo $message;
            if ($message != null) {
				header("Location: " . $_SERVER['REQUEST_URI']);
				exit();
			}
		}
        else if ($_POST["action"] == "remove category") {
            $category_id = $_POST['category_id'];

            $message = RemoveCategory($category_id, $conn);
            if ($message != null) {
				header("Location: " . $_SERVER['REQUEST_URI']);
				exit;
			}
        }
		else if ($_POST["action"] == "edit category") {
			$category_id = $_POST['category_id'];
            $new_category_name = $_POST['new_category_name'];
            $new_mother = $_POST['new_mother'];

            $message = EditCategory($category_id, $new_category_name, $new_mother, $conn);
            if ($message != null) {
				header("Location: " . $_SERVER['REQUEST_URI']);
				exit;
			}
		}
        else if ($_POST["action"] == "add products") {
            $product_name = $_POST['product_name'];
            $description = $_POST['description'];
            $expiration_date = $_POST['expiration_date'];
            $price_netto = $_POST['price_netto'];
            $vat = $_POST['vat'];
            $stock_quantity = $_POST['stock_quantity'];
            $availability_status  = $_POST['availability_status'];
		    $category_id = $_POST['category_id'];
            $size  = $_POST['size'];
            $image_url = $_POST['image_url'];

            $message = AddProduct($product_name, $description, $expiration_date, $price_netto, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url, $conn);
            if ($message != null) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }
        }
        else if ($_POST["action"] == "remove products") {
            $product_id = $_POST['product_id'];

            $message = RemoveProduct($product_id, $conn);
            if ($message != null) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }
        }
		else if ($_POST["action"] == "edit_product") {
            $product_id = $_POST['product_id'];
			$product_name = $_POST['product_name'];
			$description = $_POST['description'];
			$expiration_date = $_POST['expiration_date'];
			$price_netto = $_POST['price_netto'];
			$vat = $_POST['vat'];
			$stock_quantity = $_POST['stock_quantity'];
			$availability_status  = $_POST['availability_status'];
			$category_id = $_POST['category_id'];
			$size  = $_POST['size'];
			$image_url = $_POST['image_url'];

			$message = EditProduct($product_id, $product_name, $description, $expiration_date, $price_netto, $vat,
            $stock_quantity, $availability_status, $category_id, $size, $image_url, $conn);
			if ($message != null) {
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
	<nav class="menu">
		<ul>
			<li><a href="index.php">Strona Główna</a></li>
			<li><a href="shop.php">Sklep</a> </li>
			<li><a href="cart.php">Koszyk</a></li>
			<li><a href="admin.php" class="active">Panel Administratora</a> </li>
            <li>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="action" value="logout">Wyloguj</button>
                </form>
            </li>
		</ul>
	</nav>

    <div style="margin: 2%">
        <h1 class="overlay" style="padding: 2%">Panel administratora</h1>
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

                    <button type="submit" name="action" value="add_category">Dodaj Kategorię</button>
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

        <!-- ZARZĄDZANIE PRODUKTAMI -->
        <div>
            <h2>Zarządzanie produktami</h2>
            <div>
                <h3>Dodaj produkt</h3>
                <form method="POST">
                    <label for="product_name">Nazwa produktu:</label>
                    <input type="text" id="product_name" name="product_name" required>
                    <br><br>

                    <label for="description">Opis produktu:</label>
                    <textarea id="description" name="description" required></textarea>
                    <br><br>

                    <label for="expiration_date">Data ważności:</label>
                    <input type="date" id="expiration_date" name="expiration_date">
                    <br><br>

                    <label for="price_netto">Cena netto:</label>
                    <input type="number" id="price_netto" name="price_netto" step="0.01" required>
                    <br><br>

                    <label for="vat">VAT:</label>
                    <input type="number" id="vat" name="vat" step="1" required>
                    <br><br>

                    <label for="stock_quantity">Ilość sztuk:</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" required>
                    <br><br>

                    <label for="availability_status">Dostępność</label>
                    <select id="availability_status" name="availability_status">
                        <option value=0>Niedostępny</option>
                        <option value=1>Dostępny</option>
                    </select>
                    <br><br>

                    <label for="category_id">Kategoria:</label>
                    <select id="category_id" name="category_id">
						<?php
						$sql = "SELECT category_id, category_name FROM categories";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								echo '<option value="' . $row["category_id"] . '">' . $row["category_name"] . '</option>';
							}
						}
						?>
                    </select>
                    <br><br>

                    <label for="size">Gabaryt:</label>
                    <input type="text" id="size" name="size">
                    <br><br>

                    <label for="image_url">URL zdjęcia:</label>
                    <input type="text" id="image_url" name="image_url">
                    <br><br>

                    <button type="submit" name="action" value="add products">Dodaj Produkt</button>
                </form>
            </div>

            <div>
                <h3>Usuwanie produktu</h3>
                <form method="POST">
                    <label for="product_id">ID produktu do usunięcia</label>
                    <input type="number" id="product_id" name="product_id">
                    <br><br>

                    <button type="submit" name="action" value="remove products">Usuń produkt</button>
                </form>
            </div>

            <div>
                <h3>Edytowanie produktu</h3>
                <form method="POST">
                    <label for="product_id">ID produktu</label>
                    <input type="text" id="product_id" name="product_id" required>
                    <br><br>

                    <label for="product_name">Nazwa produktu:</label>
                    <input type="text" id="product_name" name="product_name" required>
                    <br><br>

                    <label for="description">Opis produktu:</label>
                    <textarea id="description" name="description"></textarea>
                    <br><br>

                    <label for="expiration_date">Data ważności:</label>
                    <input type="date" id="expiration_date" name="expiration_date">
                    <br><br>

                    <label for="price_netto">Cena netto:</label>
                    <input type="number" id="price_netto" name="price_netto" step="0.01" required>
                    <br><br>

                    <label for="vat">VAT:</label>
                    <input type="number" id="vat" name="vat" step="1" required>
                    <br><br>

                    <label for="stock_quantity">Ilość sztuk:</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" required>
                    <br><br>

                    <label for="availability_status">Dostępność</label>
                    <select id="availability_status" name="availability_status">
                        <option value=1>Dostępny</option>
                        <option value=0>Niedostępny</option>
                    </select>
                    <br><br>

                    <label for="category_id">Kategoria:</label>
                    <select id="category_id" name="category_id">
						<?php
						$sql = "SELECT category_id, category_name FROM categories";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								echo '<option value="' . $row["category_id"] . '">' . $row["category_name"] . '</option>';
							}
						}
						?>
                    </select>
                    <br><br>

                    <label for="size">Gabaryt:</label>
                    <input type="text" id="size" name="size">
                    <br><br>

                    <label for="image_url">URL zdjęcia:</label>
                    <input type="text" id="image_url" name="image_url">
                    <br><br>

                    <button type="submit" name="action" value="edit_product">Edytuj Produkt</button>
                </form>
                <br><br>
            </div>
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
	if ($message != null) {
		echo "<script>showMessage('" . addslashes($message) . "');</script>";

	}
	?>
</body>

</html>