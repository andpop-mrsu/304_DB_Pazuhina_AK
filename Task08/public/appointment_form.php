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

$services = $pdo->query(
    'SELECT sd.id, s.name as service_name, cc.name as car_type, sd.price 
     FROM ServiceDetails sd
     JOIN Services s ON sd.service_id = s.id
     JOIN CarCategories cc ON sd.car_category_id = cc.id
     ORDER BY s.name, cc.name'
)->fetchAll();

$apt = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM Appointments WHERE id = ?');
    $stmt->execute([$id]);
    $apt = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['appointment_start'];
    $duration = $_POST['appointment_duration_minutes'];
    $end = date('Y-m-d H:i:s', strtotime($start . " + $duration minutes"));

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare(
            'UPDATE Appointments 
             SET appointment_start = ?, 
                 appointment_duration_minutes = ?, 
                 appointment_price = ?, 
                 appointment_end = ?, 
                 service_detail_id = ? 
             WHERE id = ?'
        );
        $stmt->execute([
            $start,
            $duration,
            $_POST['appointment_price'],
            $end,
            $_POST['service_detail_id'],
            $_POST['id']
        ]);
    } else {

        $stmt = $pdo->prepare(
            'INSERT INTO Appointments (
                 customer_name, 
                 appointment_start, 
                 appointment_duration_minutes, 
                 appointment_price, 
                 appointment_end, 
                 bay_id, 
                 employee_id, 
                 service_detail_id, 
                 status, 
                 actual_end
             ) VALUES (
                 ?, ?, ?, ?, ?, 1, ?, ?, "completed", ?
             )'
        );
        $stmt->execute([
            $_POST['customer_name'],
            $start,
            $duration,
            $_POST['appointment_price'],
            $end,
            $employee_id,
            $_POST['service_detail_id'],
            $end
        ]);
    }

    header("Location: appointments.php?employee_id=$employee_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $apt ? 'Редактировать' : 'Добавить' ?> работу</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 500px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        input[readonly] { background-color: #f0f0f0; cursor: not-allowed; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #9C27B0; color: white; border: none; cursor: pointer; }
        a { margin-left: 10px; padding: 10px 20px; background-color: #999; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <h1><?= $apt ? 'Редактировать' : 'Добавить' ?> работу</h1>

    <form method="POST">
        <?php if ($apt): ?>
            <input type="hidden" name="id" value="<?= $apt['id'] ?>">
        <?php endif; ?>

        <label>Мастер:
            <input type="text" value="<?= htmlspecialchars($employee['name'] ?? '') ?>" readonly>
        </label>

        <label>Клиент:
            <?php if ($apt): ?>
                <input type="text" value="<?= htmlspecialchars($apt['customer_name'] ?? '') ?>" readonly>
            <?php else: ?>
                <input type="text" name="customer_name" value="" required>
            <?php endif; ?>
        </label>

        <label>Дата и время начала:
            <input type="datetime-local" name="appointment_start"
                   value="<?= $apt ? str_replace(' ', 'T', $apt['appointment_start']) : '' ?>" required>
        </label>

        <label>Услуга:
            <select name="service_detail_id" required>
                <?php foreach ($services as $srv): ?>
                    <option value="<?= $srv['id'] ?>"
                        <?= ($apt && $apt['service_detail_id'] == $srv['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($srv['service_name']) ?> - 
                        <?= htmlspecialchars($srv['car_type']) ?> 
                        (<?= $srv['price'] ?> руб.)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Длительность (мин):
            <input type="number" name="appointment_duration_minutes"
                   value="<?= $apt['appointment_duration_minutes'] ?? 30 ?>" min="1" required>
        </label>

        <label>Цена:
            <input type="number" name="appointment_price" step="0.01"
                   value="<?= $apt['appointment_price'] ?? '' ?>" required>
        </label>

        <button type="submit">Сохранить</button>
        <a href="appointments.php?employee_id=<?= $employee_id ?>">Отмена</a>
    </form>
</body>
</html>
