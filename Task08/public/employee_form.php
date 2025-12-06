<?php
require 'config.php';
$db = new Database('../data/db.sqlite');
$pdo = $db->getConnection();

$employee = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM Employees WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $employee = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && $_POST['id']) {
        $stmt = $pdo->prepare(
            'UPDATE Employees SET hire_date = ?, salary_percentage = ? WHERE id = ?'
        );
        $stmt->execute([$_POST['hire_date'], $_POST['salary_percentage'], $_POST['id']]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO Employees (name, hire_date, salary_percentage) VALUES (?, ?, ?)'
        );
        $stmt->execute([$_POST['name'], $_POST['hire_date'], $_POST['salary_percentage']]);
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $employee ? 'Редактировать' : 'Добавить' ?> мастера</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        input[readonly] { background-color: #f0f0f0; cursor: not-allowed; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        a { margin-left: 10px; padding: 10px 20px; background-color: #999; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <h1><?= $employee ? 'Редактировать' : 'Добавить' ?> мастера</h1>
    
    <form method="POST">
        <?php if ($employee): ?>
            <input type="hidden" name="id" value="<?= $employee['id'] ?>">
        <?php endif; ?>
        
        <label>Имя:
            <?php if ($employee): ?>
                <input type="text" value="<?= htmlspecialchars($employee['name']) ?>" readonly>

            <?php else: ?>
                <input type="text" name="name" value="" required>
            <?php endif; ?>
        </label>
        
        <label>Дата найма:
            <input type="date" name="hire_date" value="<?= $employee['hire_date'] ?? '' ?>" required>
        </label>
        
        <label>Процент зарплаты:
            <input type="number" name="salary_percentage" min="0" max="100" step="0.1" value="<?= $employee['salary_percentage'] ?? '' ?>" required>
        </label>
        
        <button type="submit">Сохранить</button>
        <a href="index.php">Отмена</a>
    </form>
</body>
</html>
