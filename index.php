<?php
session_start();

// Зчитування поточного знака та діапазону
$sign = $_POST['sign'] ?? $_SESSION['sign'] ?? '+';
$max = isset($_POST['max']) ? (int)$_POST['max'] : ($_SESSION['max'] ?? 10);
$input = $_POST['input'] ?? '';
$message = '';

// Зберігаємо значення в сесію
$_SESSION['sign'] = $sign;
$_SESSION['max'] = $max;

// Логіка перевірки
if (isset($_POST['check']) && trim($input) !== '') {
	$operand1 = $_SESSION['operand1'] ?? 0;
	$operand2 = $_SESSION['operand2'] ?? 0;

	$correct = match ($sign) {
		'+' => $operand1 + $operand2,
		'-' => $operand1 - $operand2,
		'*' => $operand1 * $operand2,
		default => 0
	};

	if ((int)$input === $correct) {
		$message = "✅ Вірно!";
		// Генеруємо новий приклад для відображення одразу
		$_SESSION['operand1'] = rand(0, $max);
		$_SESSION['operand2'] = rand(0, $max);
	} else {
		$message = "❌ Невірно. Спробуй ще!";
		// Не змінюємо приклад — щоб користувач міг спробувати ще
	}
}

// Генерація нового прикладу на зміну налаштувань або кнопку "Нове завдання"
if (isset($_POST['sign']) || isset($_POST['max']) || isset($_POST['new'])) {
	$_SESSION['operand1'] = rand(0, $max);
	$_SESSION['operand2'] = rand(0, $max);
}

// Отримання прикладу для відображення
$operand1 = $_SESSION['operand1'] ?? rand(0, $max);
$operand2 = $_SESSION['operand2'] ?? rand(0, $max);
?>

<!DOCTYPE html>
<html lang="uk">

<head>
	<meta charset="UTF-8">
	<title>Математичний тест (PHP)</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<h1>Математичний тест (PHP)</h1>

	<form method="post">
		<div class="options">
			<label>Оберіть діапазон:</label><br>
			<input type="submit" name="max" value="10">
			<input type="submit" name="max" value="20">
			<input type="submit" name="max" value="100">
			<input type="submit" name="max" value="150">
		</div>

		<div class="options">
			<label>Оберіть операцію:</label><br>
			<input type="submit" name="sign" value="+">
			<input type="submit" name="sign" value="-">
			<input type="submit" name="sign" value="*">
		</div>

		<div class="task">
			<p><?= "$operand1 $sign $operand2 = ?" ?></p>
		</div>

		<input type="text" name="input" placeholder="Ваша відповідь">
		<input type="submit" name="check" value="Перевірити">
		<input type="submit" name="new" value="Нове завдання">
	</form>

	<?php if ($message): ?>
		<p class="result"><?= $message ?></p>
	<?php endif; ?>
</body>

</html>