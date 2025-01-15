<?php

require_once 'cfg.php';

function AddCategory($category_name, $mother, $conn) {
	$stmt = $conn->prepare("INSERT INTO categories (category_name, mother) Values (?, ?)");
	$stmt->bind_param("si", $category_name, $mother);
	if ($stmt->execute()) {
		return "Kategoria '$category_name' dodana pomyślnie!";
	} else {
		return "Błąd podczas dodawania kategorii '$category_name'" . $stmt->error;
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
		return "Kategoria o ID '$category_id' została usunięta!";
	} else {
		return "Błąd przy usuwaniu kategorii o ID '$category_id'" . $stmt->error;
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

function EditCategory($category_id, $new_name, $new_mother_id, $conn) {
	$sql = "SELECT category_name FROM categories WHERE category_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $category_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows == 0) {
		return "Kategoria o podanym ID nie istnieje.";
	}

	$row = $result->fetch_assoc();
	$current_name = $row['category_name'];

	if (empty($new_name)) {
		$new_name = $current_name;
	}

	if ($category_id == $new_mother_id) {
		return "Kategoria nie może być swoją własną podkategorią.";
	}

	$current_id = $new_mother_id;
	while ($current_id != 0) {
		$sql = "SELECT mother FROM categories WHERE category_id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $current_id);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows == 0) break;

		$row = $result->fetch_assoc();
		$current_id = $row['mother'];

		if ($current_id == $category_id) {
			return "Nie można ustawić tej kategorii jako podkategorii jej własnego dziecka.";
		}
	}

	$sql = "UPDATE categories SET category_name = ?, mother = ? WHERE category_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sii", $new_name, $new_mother_id, $category_id);
	$stmt->execute();

	if ($stmt->affected_rows > 0) {
		return "Kategoria została zaktualizowana.";
	} else {
		return "Nie wprowadzono żadnych zmian.";
	}


}
