<?php
session_start();
require_once 'cfg.php';  // Dodajemy połączenie z bazą danych

require_once 'admin_cart.php';  // Wczytujemy funkcje koszyka

// Sprawdzamy, czy użytkownik chce usunąć produkt
if (isset($_POST['remove_product_id'])) {
	$product_id_to_remove = $_POST['remove_product_id'];
	RemoveFromCart($product_id_to_remove); // Usuwamy produkt z koszyka
	header("Location: cart.php"); // Przekierowujemy na stronę koszyka, aby zaktualizować widok
	exit();
}


ShowCart($conn);  // Przekazanie połączenia do funkcji
?>
