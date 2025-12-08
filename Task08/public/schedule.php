<?php
require 'config.php';
$db = new Database('../data/db.sqlite');
$pdo = $db->getConnection();

$employee_id = $_GET['employee_id'] ?? null;
if (!$employee_id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM Employees WHERE id = ?');
$stmt->execute([$employee_id]);
$employee = $stmt->fetch();

$stmt = $pdo->prepare(
    'SELECT * FROM EmployeeSchedule 
     WHERE employee_id = ? 
     ORDER BY work_date DESC, start_time'
);
$stmt->execute([$employee_id]);
$schedule = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>График работы - <?= htmlspecialchars($employee['name']) ?></title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #FF9800; color: white; }
        .actions { white-space: nowrap; }
        a { margin: 2px; padding: 5px 10px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; }
        a.delete { background-color: #f44336; }
        a.add { background-color: #4CAF50; padding: 10px 20px; display: inline-block; }
    </style>
</head>
<body>
    <h1>График работы: <?= htmlspecialchars($employee['name']) ?></h1>
    
    <table>
        <tr>
            <th>Дата</th>
            <th>Время начала</th>
            <th>Время окончания</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($schedule as $item): ?>
        <tr>
            <td><?= $item['work_date'] ?></td>
            <td><?= substr($item['start_time'], 0, 5) ?></td>
            <td><?= substr($item['end_time'], 0, 5) ?></td>
            <td class="actions">
                <a href="schedule_form.php?id=<?= $item['id'] ?>&employee_id=<?= $employee_id ?>">Редактировать</a>
                <a href="schedule_delete.php?id=<?= $item['id'] ?>&employee_id=<?= $employee_id ?>" class="delete" onclick="return confirm('Удалить запись?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <a href="schedule_form.php?employee_id=<?= $employee_id ?>" class="add">+ Добавить запись в график</a>
    <br><br>
    <a href="index.php">← Вернуться к списку мастеров</a>
</body>
</html>
