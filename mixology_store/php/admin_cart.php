<?php
require_once 'cfg.php';
function AddToCart($product_id, $quantity) {
	// Upewniamy się, że koszyk jest zainicjowany w sesji
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = [];
	}

	// Sprawdzamy, czy produkt już istnieje w koszyku
	if (isset($_SESSION['cart'][$product_id])) {
		// Jeśli produkt już istnieje, dodajemy do istniejącej ilości
		$_SESSION['cart'][$product_id] += $quantity;
	} else {
		// Jeśli produkt nie istnieje, dodajemy go do koszyka
		$_SESSION['cart'][$product_id] = $quantity;
	}
}



function RemoveFromCart($product_id) {
	// Sprawdzamy, czy produkt istnieje w koszyku
	if (isset($_SESSION['cart'][$product_id])) {
		unset($_SESSION['cart'][$product_id]); // Usuwamy produkt z koszyka
	}
}

	function UpdateCart($product_id,$quantity){
		if (isset($_SESSION['cart'][$product_id])){
			if($quantity > 0) {
				$_SESSION['cart'][$product_id] = $quantity;
			} else {
				RemoveFromCart($product_id); //usuwa produkt jeśli ilość to 0
			}
		}
	}

function CalculateTotalPrice($conn) {
	$total = 0;
	// Sprawdź, czy koszyk nie jest pusty
	if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
		foreach ($_SESSION['cart'] as $product_id => $quantity) {
			$sql = "SELECT price_netto, vat FROM products WHERE product_id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();

			if ($result) {
				// Uwzględnij ilość ($quantity) w obliczeniach
				$price_per_item = $result['price_netto'] * (1 + $result['vat'] / 100);
				$total += $price_per_item * $quantity; // Dodaj całkowity koszt dla tego produktu
			}
		}
	}
	return $total; // Zwróć łączną cenę
}


function ShowCart($conn) {
	if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
		echo "<h2>Twój koszyk:</h2>";
		echo "<table border='1'><tr><th>Produkt</th><th>Ilość</th><th>Cena</th><th>Usuń</th></tr>";

		foreach ($_SESSION['cart'] as $product_id => $quantity) {
			// Pobieramy dane o produkcie
			$sql = "SELECT product_name, price_netto, vat FROM products WHERE product_id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();

			if ($result) {
				// Obliczamy cenę brutto produktu
				$total_price = $result['price_netto'] * (1 + $result['vat'] / 100);

				// Wyświetlamy dane o produkcie
				echo "<tr>";
				echo "<td>" . htmlspecialchars($result['product_name']) . "</td>";
				echo "<td>
                        <form action='cart.php' method='POST'>
                            <input type='number' name='quantity' value='" . htmlspecialchars($quantity) . "' min='0' />
                            <input type='hidden' name='product_id' value='" . htmlspecialchars($product_id) . "' />
                            <button type='submit' name='update_quantity'>Zaktualizuj ilość</button>
                        </form>
                      </td>";
				echo "<td>" . number_format($total_price * $quantity, 2) . " PLN</td>";

				// Dodajemy przycisk usuwania produktu
				echo "<td>
                        <form action='cart.php' method='POST'>
                            <input type='hidden' name='remove_product_id' value='" . htmlspecialchars($product_id) . "' />
                            <button type='submit'>Usuń</button>
                        </form>
                      </td>";
				echo "</tr>";
			}
		}

		echo "</table>";

		// Wyświetlamy łączną cenę koszyka
		echo "<h3>Łączna cena: " . number_format(CalculateTotalPrice($conn), 2) . " PLN</h3>";
	} else {
		echo "<p>Twój koszyk jest pusty.</p>";
	}
}

