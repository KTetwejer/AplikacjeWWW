<?php
session_start();

require_once 'cfg.php';
require_once 'admin_category.php';
require_once 'admin_products.php';
require_once 'admin_cart.php';

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Sklep</title>
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
		<h1>Nasza oferta</h1>
		<?php ShowProducts($conn) ?>
	</div>

</body>
</html>
