<?php
require 'config.php';
$db = new Database('../data/db.sqlite');
$pdo = $db->getConnection();

$id = $_GET['id'] ?? null;
$employee_id = $_GET['employee_id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM EmployeeSchedule WHERE id = ?');
    $stmt->execute([$id]);
}

header("Location: schedule.php?employee_id=$employee_id");
