<?php
session_start();
require_once "cfg.php";
require_once "admin_pages.php";


if (isset($_GET['page_id'])) {
	$product_id = $_GET['page_id'];

	$stmt = $conn->prepare("SELECT * FROM pages");
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		?>
<!DOCTYPE html>
<head>
	<title>Strona</title>
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
			<li><a href="pages.php">Podstrony</a></li>
        </ul>
    </nav>
    <?php
		echo "<h1>" . htmlspecialchars($row['page_title']) . "</h1>";

		echo "<p>" . nl2br(htmlspecialchars($row['page_content'])) . "</p>";

	} else {
		echo "<p>Strona o podanym ID nie istnieje.</p>";
	}

	$stmt->close();
    } else {
	echo "<p>Brak strony.</p>";
}
    ?>
</body>