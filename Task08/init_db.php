<?php

if (!is_dir('./data')) {
    mkdir('./data', 0777, true);
}

try {
    $pdo = new PDO('sqlite:./data/db.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = file_get_contents('./db_init.sql');
    
    $pdo->exec($sql);
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS EmployeeSchedule (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        employee_id INTEGER NOT NULL,
        work_date DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        FOREIGN KEY (employee_id) REFERENCES Employees(id) ON DELETE CASCADE
    )
    ");
    echo "База данных успешно инициализирована!\n";
    echo "Создан файл: " . realpath('./data/db.sqlite') . "\n";
    
} catch (PDOException $e) {
    die("Ошибка: " . $e->getMessage() . "\n");
}
