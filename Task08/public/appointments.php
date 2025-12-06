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
    'SELECT a.*, s.name as service_name, cc.name as car_type
     FROM Appointments a
     JOIN ServiceDetails sd ON a.service_detail_id = sd.id
     JOIN Services s ON sd.service_id = s.id
     JOIN CarCategories cc ON sd.car_category_id = cc.id
     WHERE a.employee_id = ? AND a.status = "completed"
     ORDER BY a.appointment_start DESC'
);
$stmt->execute([$employee_id]);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Выполненные работы - <?= htmlspecialchars($employee['name']) ?></title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #9C27B0; color: white; }
        .actions { white-space: nowrap; }
        a { margin: 2px; padding: 5px 10px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; }
        a.delete { background-color: #f44336; }
        a.add { background-color: #4CAF50; padding: 10px 20px; display: inline-block; }
    </style>
</head>
<body>
    <h1>Выполненные работы: <?= htmlspecialchars($employee['name']) ?></h1>
    
    <table>
        <tr>
            <th>Дата</th>
            <th>Услуга</th>
            <th>Тип авто</th>
            <th>Клиент</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($appointments as $apt): ?>
        <tr>
            <td><?= date('d.m.Y H:i', strtotime($apt['appointment_start'])) ?></td>
            <td><?= htmlspecialchars($apt['service_name']) ?></td>
            <td><?= htmlspecialchars($apt['car_type']) ?></td>
            <td><?= htmlspecialchars($apt['customer_name']) ?></td>
            <td><?= number_format($apt['appointment_price'], 2) ?> руб.</td>
            <td class="actions">
                <a href="appointment_form.php?id=<?= $apt['id'] ?>&employee_id=<?= $employee_id ?>">Редактировать</a>
                <a href="appointment_delete.php?id=<?= $apt['id'] ?>&employee_id=<?= $employee_id ?>" class="delete" onclick="return confirm('Удалить работу?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <a href="appointment_form.php?employee_id=<?= $employee_id ?>" class="add">+ Добавить работу</a>
    <br><br>
    <a href="index.php">← Вернуться к списку мастеров</a>
</body>
</html>
