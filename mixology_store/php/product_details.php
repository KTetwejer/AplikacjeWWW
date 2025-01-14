<?php
session_start();
require_once "cfg.php";
require_once "admin_cart.php";

// Sprawdzamy, czy produkt_id zostało przekazane w URL
if (isset($_GET['product_id'])) {
	$product_id = $_GET['product_id'];

	// Pobieramy dane produktu z bazy danych
	$stmt = $conn->prepare("SELECT p.*, c.category_name 
                            FROM products p 
                            JOIN categories c ON p.category_id = c.category_id 
                            WHERE p.product_id = ?");
	$stmt->bind_param("i", $product_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		?>
<!DOCTYPE html>
<head>
	<title>Szczegóły produktu</title>
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
	</ul>
</nav>
<?php
		// Wyświetlamy szczegóły produktu
		echo "<h1>" . htmlspecialchars($row['product_name']) . "</h1>";

		// Wyświetlanie wszystkich informacji o produkcie
		echo "<p><strong>Opis:</strong> " . nl2br(htmlspecialchars($row['description'])) . "</p>";
		echo "<p><strong>Data modyfikacji:</strong> " . htmlspecialchars($row['modification_date']) . "</p>";
		echo "<p><strong>Data ważności:</strong> " . htmlspecialchars($row['expiration_date']) . "</p>";
		echo "<p><strong>Cena netto:</strong> " . number_format($row['price_netto'], 2) . " PLN</p>";
		echo "<p><strong>Cena brutto:</strong> " . number_format($row['price_netto'] * (1 + $row['vat'] / 100), 2) . " PLN</p>";
		echo "<p><strong>VAT:</strong> " . htmlspecialchars($row['vat']) . "%</p>";
		echo "<p><strong>Dostępnych sztuk:</strong> " . htmlspecialchars($row['stock_quantity']) . "</p>";
		echo "<p><strong>Status dostępności:</strong> " . ($row['availability_status'] == 1 ? 'Dostępny' : 'Niedostępny') . "</p>";

		echo "<p><strong>Kategoria:</strong> " . htmlspecialchars($row['category_name']) . "</p>";
		echo "<p><strong>Rozmiar:</strong> " . htmlspecialchars($row['size']) . "</p>";
		echo "<p><strong>Obraz produktu:</strong><br><img src='" . htmlspecialchars($row['image_url']) . "' alt='Zdjęcie produktu' width='300'></p>";

		// Formularz dodania produktu do koszyka
		echo "<form method='post' action='shop.php'>
                <input type='hidden' name='product_id' value='" . $row['product_id'] . "' />
                <input type='number' name='quantity' value='1' min='1' max='" . $row['stock_quantity'] . "' />
                <button type='submit' name='add_to_cart'>Dodaj do koszyka</button>
              </form>";
	} else {
		echo "<p>Produkt o podanym ID nie istnieje.</p>";
	}

	$stmt->close();
} else {
	echo "<p>Brak produktu do wyświetlenia.</p>";
}
?>
</body>