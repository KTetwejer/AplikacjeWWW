<?php
require_once "cfg.php";

function DodajProdukt($title, $description, $expiration_date, $price_net, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url, $conn) {
	$stmt = $conn->prepare("INSERT INTO products (title, description, expiration_date, price_net, vat, stock_quantity, availability_status, category_id, size, image_url)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("sssddiiiss", $title, $description, $expiration_date, $price_net, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url);
	$stmt->execute();
	$stmt->close();
	return "Produkt został dodany.";
}

function UsunProdukt($id, $conn) {
	$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();
	return "Produkt został usunięty.";
}

function EdytujProdukt($id, $title, $description, $expiration_date, $price_net, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url, $conn) {
	$stmt = $conn->prepare("UPDATE products 
                            SET title = ?, description = ?, expiration_date = ?, price_net = ?, vat = ?, stock_quantity = ?, availability_status = ?, category_id = ?, size = ?, image_url = ? 
                            WHERE id = ?");
	$stmt->bind_param("sssddiiissi", $title, $description, $expiration_date, $price_net, $vat, $stock_quantity, $availability_status, $category_id, $size, $image_url, $id);
	$stmt->execute();
	$stmt->close();
	return "Produkt został zaktualizowany.";
}

function PokazProdukty($conn) {
	$result = $conn->query("SELECT p.*, c.category_name 
                            FROM products p 
                            JOIN categories c ON p.category_id = c.category_id");
	while ($row = $result->fetch_assoc()) {
		echo "<div>";
		echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
		echo "<p>" . htmlspecialchars($row['description']) . "</p>";
		echo "<p>Cena brutto: " . number_format($row['price_net'] * (1 + $row['vat'] / 100), 2) . " PLN</p>";
		echo "<p>Dostępnych sztuk: " . htmlspecialchars($row['stock_quantity']) . "</p>";
		echo "<p>Kategoria: " . htmlspecialchars($row['category_name']) . "</p>";
		echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='Zdjęcie produktu'>";
		echo "</div><hr>";
	}
}
?>
