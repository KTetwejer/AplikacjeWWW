<?php

require_once 'connect.php';

function AddCategory($category_name, $mother, $conn) {
	$stmt = $conn->prepare("INSERT INTO categories (category_name, mother) Values (?, ?)");
	$stmt->bind_param("si", $category_name, $mother);
	if ($stmt->execute()) {
		echo "Kategoria '$category_name' dodana pomyślnie!<br>";
	} else {
		echo "Błąd podczas dodawania kategorii '$category_name'" . $stmt->error . "<br>";
	}
	$stmt->close();
}

function RemoveCategory($category_id, $conn) {
	//usuwanie podkategorii
	$stmt = $conn->prepare("DELETE FROM categories WHERE mother = ?");
	$stmt->bind_param("i", $category_id);
	$stmt->execute();
	$stmt->close();

	//usuwanie samej kategorii
	$stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
	$stmt->bind_param("i", $category_id);
	if ($stmt->execute()) {
		echo "Kategoria o ID '$category_id' została usunięta!<br>";
	} else {
		echo "Błąd przy usuwaniu kategorii o ID '$category_id'" . $stmt->error . "<br>";
	}
}

function GetCategoryTree($mother=0, $conn) {
	$sql = "SELECT category_id, category_name FROM categories WHERE mother = ? ORDER BY category_name";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $mother);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		echo '<ul>';
		while ($row = $result->fetch_assoc()) {
			echo '<li>' . htmlspecialchars($row['category_name']);
			GetCategoryTree($row["category_id"], $conn);
			echo '</li>';
		}
		echo '</ul>';
	}
}

function EditCategory($category_id, $category_name, $mother, $conn) {
	$stmt = $conn->prepare("UPDATE categories SET category_name = ?, mother = ? WHERE id = ?");
	$stmt->bind_param("sii", $category_name, $mother, $category_id);
	if ($stmt->execute()) {
		echo "Kategoria o ID '$category_id' została zaktualizowana!<br>";
	} else {
		echo "Błąd przy aktualizacji kategorii o ID '$category_id'" . $stmt->error . "<br>";
	}
}