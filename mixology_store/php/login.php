<?php
session_start();
require_once "cfg.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST["username"];
	$password = $_POST["password"];

	$sql = "SELECT * FROM users WHERE username = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$user = $result->fetch_assoc();

		// Jeśli hasło jest już zapisane w kolumnie `password_hash`
		if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			header('Location: admin.php');
			exit();
		}

		// Jeśli hasło jest zapisane w zwykłym tekście
		if ($password == $user['password']) {
			// migracja hasła do wersji hashowanej
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			$updateSql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
			$updateStmt = $conn->prepare($updateSql);
			$updateStmt->bind_param("si", $hashedPassword, $user['user_id']);
			$updateStmt->execute();

			// Logowanie użytkownika
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			header('Location: admin.php');
			exit();
		} else {
			$error_message = "Hasło nieprawidłowe";
		}
	} else {
		$error_message = "Nie znaleziono użytkownika";
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Logowanie do panelu administratora</title>
	<meta charset="UTF-8">
	<meta name="author" content="Konrad Tetwejer">
	<link rel="stylesheet" href="../css/style.css">
</head>
<body class="centerColumn">
<h1>Logowanie</h1>
<form method="POST" autocomplete="off">
	<label for="username">Nazwa użytkownika:</label>
	<input type="text" id="username" name="username" required>
	<br><br>

	<label for="password">Hasło:</label>
	<input type="password" id="password" name="password" required>
	<br><br>

	<button type="submit">Zaloguj się</button>
</form>

<?php
if (isset($error_message)) {
	echo "<p style='color: red;'>" . htmlspecialchars($error_message) . "</p>";
}
?>
</body>
</html>
