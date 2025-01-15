<?php
    session_start();
    require_once 'cfg.php';
    require_once 'admin_cart.php';

    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        UpdateCart($product_id, $quantity);

        header("Location: cart.php");
        exit();
    }

    if (isset($_POST['remove_product_id'])) {
        $product_id_to_remove = $_POST['remove_product_id'];
        RemoveFromCart($product_id_to_remove);

        header("Location: cart.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Koszyk</title>
	<link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
    <nav class="menu">
        <ul>
            <li><a href="index.php">Strona Główna</a></li>
            <li><a href="shop.php">Sklep</a> </li>
            <li><a href="cart.php" class="active">Koszyk</a></li>
            <li><a href="admin.php">Panel Administratora</a></li>
        </ul>
    </nav>

    <div>
        <h1>Twój koszyk</h1>
        <?php
        ShowCart($conn);
        ?>
</div>
</body>
</html>
