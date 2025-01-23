<!DOCTYPE html>
<html lang = "pl">

<head>
	<title>DrinkMeister</title>
	<meta name="Author" content="Konrad Tetwejer" />
	<link rel="stylesheet" href="../css/style.css"/>
</head>

<body>
	<nav class="menu">
        <ul>
            <li><a href="index.php" class="active">Strona Główna</a></li>
            <li><a href="shop.php">Sklep</a> </li>
            <li><a href="cart.php">Koszyk</a></li>
            <li><a href="admin.php">Panel Administratora</a> </li>
            <li><a href="pages.php">Podstrony</a></li>
        </ul>
    </nav>

    <div class="titleBackground">
        <h1 class="overlay">Drinkmeister - wszystko czego potrzebujesz w swoim barze!</h1>
    </div>
    <div>
        <b style="font-weight: bolder; font-size: 26px; margin: 15%">Witam w sklepie
            poświęconym
            miksologii!
            Znajdziesz tu
            między
            innymi:</b>
        <ul class="container1">
            <li>Najlepszy sprzęt barmański</li>
            <li>Najwyższej jakości składniki</li>
            <li>Książki, pomagające poprawić twoje umiejętności</li>
         <li>Oraz wiele więcej!</li>
        </ul>
    </div>

	<?php
	$nr_indeksu = '169371';
	$nr_grupy = 'ISI3';

	echo 'Autor: Konrad Tetwejer '.$nr_indeksu.' grupa '.$nr_grupy;
	?>
</body>

</html>