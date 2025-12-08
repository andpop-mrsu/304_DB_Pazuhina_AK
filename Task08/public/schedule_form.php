<?php
require 'config.php';
$db = new Database('../data/db.sqlite');
$pdo = $db->getConnection();

$employee_id = $_GET['employee_id'] ?? null;
$id = $_GET['id'] ?? null;

if (!$employee_id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM Employees WHERE id = ?');
$stmt->execute([$employee_id]);
$employee = $stmt->fetch();

$item = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM EmployeeSchedule WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && $_POST['id']) {
        $stmt = $pdo->prepare(
            'UPDATE EmployeeSchedule SET work_date = ?, start_time = ?, end_time = ? WHERE id = ?'
        );
        $stmt->execute([$_POST['work_date'], $_POST['start_time'], $_POST['end_time'], $_POST['id']]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO EmployeeSchedule (employee_id, work_date, start_time, end_time) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$employee_id, $_POST['work_date'], $_POST['start_time'], $_POST['end_time']]);
    }
    header("Location: schedule.php?employee_id=$employee_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $item ? 'Редактировать' : 'Добавить' ?> запись в график</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #FF9800; color: white; border: none; cursor: pointer; }
        a { margin-left: 10px; padding: 10px 20px; background-color: #999; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <h1><?= $item ? 'Редактировать' : 'Добавить' ?> запись в график</h1>
    <h2>Мастер: <?= htmlspecialchars($employee['name']) ?></h2>
    
    <form method="POST">
        <?php if ($item): ?>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <?php endif; ?>
        
        <label>Дата работы:
            <input type="date" name="work_date" value="<?= $item['work_date'] ?? '' ?>" required>
        </label>
        
        <label>Время начала:
            <input type="time" name="start_time" value="<?= $item['start_time'] ?? '' ?>" required>
        </label>
        
        <label>Время окончания:
            <input type="time" name="end_time" value="<?= $item['end_time'] ?? '' ?>" required>
        </label>
        
        <button type="submit">Сохранить</button>
        <a href="schedule.php?employee_id=<?= $employee_id ?>">Отмена</a>
    </form>
</body>
</html>
