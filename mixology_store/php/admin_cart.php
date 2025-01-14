<?php

function AddToCart($product_id, $quantity){
	if (!isset($_SESSION['cart'])){
		$_SESSION['cart'] = [];
	}

	if (!isset($_SESSION['cart'][$product_id])){
		$_SESSION['cart'][$product_id] += $quantity;
	} else {
		$_SESSION['cart'][$product_id] = $quantity;
	}

	function RemoveFromCart($product_id){
		if (isset($_SESSION['cart'][$product_id])){
			unset($_SESSION['cart'][$product_id]);
		}
	}

	function UpdateCart($product_id,$quantity){
		if (isset($_SESSION['cart'][$product_id])){
			if(quantity > 0) {
				$_SESSION['cart'][$product_id] = $quantity;
			} else {
				RemoveFromCart($product_id); //usuwa produkt jeśli ilość to 0
			}
		}
	}

	function CalculateTotalPrice($conn) {
		$total = 0;
		foreach ($_SESSION['cart'] as $product_id => $quantity) {
			$sql = "SELECT price_netto, vat FROM products WHERE id = $product_id";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();
			if ($result) {
				$total += $result['price_netto'] * ($result['vat'] / 100 + 1);
			}
		}
		return $total;
	}
}
