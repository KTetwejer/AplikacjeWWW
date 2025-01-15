<?php
require_once 'cfg.php';
function AddToCart($product_id, $quantity) {
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = [];
	}

	if (isset($_SESSION['cart'][$product_id])) {
		$_SESSION['cart'][$product_id] += $quantity;
	} else {
		$_SESSION['cart'][$product_id] = $quantity;
	}
}



function RemoveFromCart($product_id) {
	if (isset($_SESSION['cart'][$product_id])) {
		unset($_SESSION['cart'][$product_id]);
	}
}

function UpdateCart($product_id,$quantity){
	if (isset($_SESSION['cart'][$product_id])){
		if($quantity > 0) {
			$_SESSION['cart'][$product_id] = $quantity;
		} else {
			RemoveFromCart($product_id);
		}
	}
}

function CalculateTotalPrice($conn) {
	$total = 0;
	if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
		foreach ($_SESSION['cart'] as $product_id => $quantity) {
			$sql = "SELECT price_netto, vat FROM products WHERE product_id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();

			if ($result) {
				$price_per_item = $result['price_netto'] * (1 + $result['vat'] / 100);
				$total += $price_per_item * $quantity;
			}
		}
	}
	return $total;
}


function ShowCart($conn) {
	if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
		echo "<h2>Twój koszyk:</h2>";
		echo "<table border='1'><tr><th>Produkt</th><th>Ilość</th><th>Cena</th><th>Usuń</th></tr>";

		foreach ($_SESSION['cart'] as $product_id => $quantity) {
			$sql = "SELECT product_name, price_netto, vat FROM products WHERE product_id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();

			if ($result) {
				$total_price = $result['price_netto'] * (1 + $result['vat'] / 100);

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

		echo "<h3>Łączna cena: " . number_format(CalculateTotalPrice($conn), 2) . " PLN</h3>";
	} else {
		echo "<p>Twój koszyk jest pusty.</p>";
	}
}

