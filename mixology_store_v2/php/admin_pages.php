<?php

require_once "cfg.php";

function AddPage($page_name, $page_content, $conn) {
	$stmt = $conn->prepare("INSERT INTO pages (page_title, page_content)
                            VALUES (?, ?)");
	$stmt->bind_param("ss", $page_name, $page_content );
	$stmt->execute();
	$stmt->close();
	return "Strona dodana";
}

function RemovePage($page_id, $conn) {
	$stmt = $conn->prepare("DELETE FROM pages WHERE page_id = ?");
	$stmt->bind_param("i", $page_id);
	$stmt->execute();
	$stmt->close();
	return "Strona usunięta";
}

function EditPage($page_id, $page_name, $page_content, $conn) {
    
	$stmt = $conn->prepare("UPDATE pages 
    SET page_title = ?, page_content = ? WHERE page_id = ?");

$stmt->bind_param("ssi", $page_name, $page_content, $page_id);
$stmt->execute();
$stmt->close();
return "Strona zaktualizowana.";
}

function ShowPage($conn) {
	$result = $conn->query("SELECT * FROM pages");

	while ($row = $result->fetch_assoc()) {
		echo "<div>";
		echo "<h3><a href='dynamic_page.php?page_id=" . $row['page_id'] . "'>" . htmlspecialchars($row['page_title']) . "</a></h3>";
		echo "<p>" . htmlspecialchars($row['page_content']) . "</p>";
		echo "</div><hr>";
	}
}