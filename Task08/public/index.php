<?php
require 'config.php';
$db = new Database('../data/db.sqlite');
$pdo = $db->getConnection();

$employees = $pdo->query(
    "SELECT *, 
            substr(name, instr(name, ' ') + 1) as last_name 
     FROM Employees 
     ORDER BY last_name, name"
)->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Автомойка - Мастера</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .actions { white-space: nowrap; }
        a { margin: 2px; padding: 5px 10px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; }
        a.delete { background-color: #f44336; }
        a.schedule { background-color: #FF9800; }
        a.works { background-color: #9C27B0; }
        a.add { background-color: #4CAF50; display: inline-block; margin: 20px 0; padding: 10px 20px; }
    </style>
</head>
<body>
    <h1>Список мастеров (по фамилии)</h1>
    
    <table>
        <tr>
            <th>Мастер</th>
            <th>Дата найма</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($employees as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['name']) ?></td>
            <td><?= $emp['hire_date'] ?></td>
            <td class="actions">
                <a href="employee_form.php?id=<?= $emp['id'] ?>">Редактировать</a>
                <a href="employee_delete.php?id=<?= $emp['id'] ?>" class="delete" onclick="return confirm('Удалить мастера?')">Удалить</a>
                <a href="schedule.php?employee_id=<?= $emp['id'] ?>" class="schedule">График</a>
                <a href="appointments.php?employee_id=<?= $emp['id'] ?>" class="works">Выполненные работы</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <a href="employee_form.php" class="add">+ Добавить мастера</a>
</body>
</html>
