<!DOCTYPE html>
<html lang="pl">

<head>
    <title>Miksologia - moja pasja</title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Konrad Tetwejer" />
    <link rel="stylesheet" href="css/style.css"/>
</head>

<body>
    <nav class="menu">
        <ul class="menu">
            <li><a href="index.php" class="active">Strona Główna</a></li>
            <li><a href="html/recipes.html">Przepisy</a></li>
            <li><a href="html/history.html">Drinki i ich historie</a></li>
            <li><a href="html/accesories.html">Sprzęt</a></li>
            <li><a href="html/contact.html">Kontakt</a></li>
            <li><a href="html/javatest.html">Skrypty</a></li>
        </ul>
    </nav>

    <div class="backgroundTitle">
        <h1 class="overlay">Miksologia - od historii po praktykę</h1>
    </div>
    <img src="img/sevencocktails.jpg" class="imageResponsive" alt="Ups! Nie znaleźliśmy obrazu :c"/>
    <div class="textBold" id="mainPage" style="background-color: rgb(185, 232, 247);">
        <b>Witam na stronie poświęconej miksologii! Znajdziesz tu między innymi:</b>
            <ul>
                <li>Przepisy, od klasyki po te najbardziej nietypowe;</li>
                <li>Historię drinków, samej miksologii oraz ciekawostki, dzięki którym umilisz klientowi czas oczekiwania na drinka;</li>
                <li>Artykuły o sprzęcie, rodzaje szkła do drinków i wszystko, co powinieneś wiedzieć o akcesoriach;</li>
            </ul>

        <p class="textHighlited" style="text-align: center;">
            <a href="#" style="text-decoration: none; color: black;">
                <i>Użyj menu na górze strony, aby odnaleźć interesujący cię temat</i></a>
        </p>
    </div>

    <?php
        $nr_indeksu = '169371';
        $nr_grupy = 'ISI3';

        echo 'Autor: Konrad Tetwejer '.$nr_indeksu.' grupa '.$nr_grupy;
    ?>

</body>

</html>
