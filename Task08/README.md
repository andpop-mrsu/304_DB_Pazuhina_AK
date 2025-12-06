# Лабораторная работа 8: CRUD-приложение для автомойки

## Описание
Веб-приложение для управления мастерами автомойки с полным CRUD-функционалом: список мастеров, график работы, выполненные услуги.

## Структура проекта
Task08/
├── data/
│ └── db.sqlite # База данных SQLite
├── public/
│ ├── index.php # Список мастеров
│ ├── employee_form.php # Добавление/редактирование мастера
│ ├── employee_delete.php
│ ├── schedule.php # График работы мастера
│ ├── schedule_form.php # Добавление/редактирование графика
│ ├── schedule_delete.php
│ ├── appointments.php # Выполненные работы
│ ├── appointment_form.php
│ └── appointment_delete.php
├── db_init.sql # SQL-скрипт инициализации БД
└── README.md # Этот файл

## Установка и запуск

1. **Инициализация БД:** php init_db.php
2. **Запуск веб-сервера:** php -S localhost:3000 -t public
3. **Открыть в браузере:** http://localhost:3000/
 