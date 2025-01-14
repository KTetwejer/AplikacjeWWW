<?php
require_once "cfg.php";
require_once "admin_cart.php";

function AddProduct($product_name, $description, $expiration_date, $price_netto, $vat, $stock_quantity,
					$availability_status,
		   $category_id, $size, $image_url, $conn) {
	$stmt = $conn->prepare("INSERT INTO products (product_name, description, expiration_date, price_netto, vat, stock_quantity, availability_status, category_id, size, image_url)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("sssddiiiss", $product_name, $description, $expiration_date, $price_netto, $vat, $stock_quantity,
		$availability_status, $category_id, $size, $image_url);
	$stmt->execute();
	$stmt->close();
	return "Produkt został dodany.";
}

function RemoveProduct($product_id, $conn) {
	$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
	$stmt->bind_param("i", $product_id);
	$stmt->execute();
	$stmt->close();
	return "Produkt został usunięty.";
}

function EditProduct($product_id, $product_name, $description, $expiration_date, $price_net, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url, $conn) {

	//sprawdzenie, czy produkt o podanym ID istnieje
	$sql = "SELECT product_name FROM products WHERE product_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $product_id);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows == 0) {
		return "Produkt o ID " . $product_id . " nie istnieje.";
		exit();
	}

	$stmt = $conn->prepare("UPDATE products 
                            SET product_name = ?, description = ?, expiration_date = ?, price_netto = ?, vat = ?, stock_quantity = ?, availability_status = ?, category_id = ?, size = ?, image_url = ? 
                            WHERE product_id = ?");

	$stmt->bind_param("sssddiiissi", $product_name, $description, $expiration_date, $price_net, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url, $product_id);
	$stmt->execute();
	$stmt->close();
	return "Produkt został zaktualizowany.";
}

function ShowProducts($conn) {
	$result = $conn->query("SELECT p.*, c.category_name 
                            FROM products p 
                            JOIN categories c ON p.category_id = c.category_id");

	while ($row = $result->fetch_assoc()) {
		echo "<div>";
		// Zmieniamy nazwę produktu na link do strony szczegółów
		echo "<h3><a href='product_details.php?product_id=" . $row['product_id'] . "'>" . htmlspecialchars($row['product_name']) . "</a></h3>";
		echo "<p>" . htmlspecialchars($row['description']) . "</p>";
		echo "<p>Cena brutto: " . number_format($row['price_netto'] * (1 + $row['vat'] / 100), 2) . " PLN</p>";
		echo "<p>Dostępnych sztuk: " . htmlspecialchars($row['stock_quantity']) . "</p>";
		echo "<p>Kategoria: " . htmlspecialchars($row['category_name']) . "</p>";
		echo "<p><img src='" . htmlspecialchars($row['image_url']) . "' alt='Zdjęcie produktu' style='width: 13%; height: 13%'></p>";

		// Dodajemy formularz do dodania produktu do koszyka
		echo "<form method='post' action='shop.php'>
                <input type='hidden' name='product_id' value='" . $row['product_id'] . "' />
                <input type='number' name='quantity' value='1' min='1' max='" . $row['stock_quantity'] . "' />
                <button type='submit' name='add_to_cart'>Dodaj do koszyka</button>
              </form>";

		echo "</div><hr>";
	}
}