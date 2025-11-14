
CREATE DATABASE IF NOT EXISTS carwash;
USE carwash;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE CarCategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE ServiceDetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    car_category_id INT NOT NULL,
    duration_minutes INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    UNIQUE (service_id, car_category_id),
    CONSTRAINT fk_sd_service FOREIGN KEY (service_id)
        REFERENCES Services(id) ON DELETE RESTRICT,
    CONSTRAINT fk_sd_category FOREIGN KEY (car_category_id)
        REFERENCES CarCategories(id) ON DELETE RESTRICT,
    CHECK (duration_minutes > 0),
    CHECK (price >= 0)
) ENGINE=InnoDB;

CREATE TABLE Employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    hire_date DATE NOT NULL,
    dismissal_date DATE DEFAULT NULL,
    salary_percentage DECIMAL(5,2) NOT NULL,
    CHECK (salary_percentage BETWEEN 0 AND 100)
) ENGINE=InnoDB;

CREATE TABLE Bays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;


CREATE TABLE Appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    appointment_start DATETIME NOT NULL,

    appointment_duration_minutes INT NOT NULL,
    appointment_price DECIMAL(10,2) NOT NULL,
    appointment_end DATETIME NOT NULL,

    bay_id INT NOT NULL,
    employee_id INT NOT NULL,
    service_detail_id INT NOT NULL,

    status ENUM('planned', 'completed', 'cancelled') DEFAULT 'planned',
    actual_end DATETIME DEFAULT NULL,

    CONSTRAINT fk_app_bay FOREIGN KEY (bay_id) REFERENCES Bays(id) ON DELETE RESTRICT,
    CONSTRAINT fk_app_employee FOREIGN KEY (employee_id) REFERENCES Employees(id) ON DELETE RESTRICT,
    CONSTRAINT fk_app_servicedetail FOREIGN KEY (service_detail_id) REFERENCES ServiceDetails(id) ON DELETE RESTRICT,

    CHECK (appointment_duration_minutes > 0),
    CHECK (appointment_price >= 0)
) ENGINE=InnoDB;


CREATE INDEX idx_appointments_bay_start ON Appointments(bay_id, appointment_start);
CREATE INDEX idx_appointments_employee_start ON Appointments(employee_id, appointment_start);
CREATE INDEX idx_appointments_status ON Appointments(status);

INSERT INTO CarCategories (name)
VALUES ('Sedan'), ('SUV');

INSERT INTO Services (name)
VALUES ('Wash'), ('Polish');

INSERT INTO ServiceDetails (service_id, car_category_id, duration_minutes, price)
VALUES 
    (1, 1, 30, 20.00),
    (1, 2, 45, 30.00),
    (2, 1, 60, 50.00);

INSERT INTO Employees (name, hire_date, salary_percentage)
VALUES 
    ('John Doe', '2025-01-01', 20.0),
    ('Jane Smith', '2025-01-01', 25.0);

INSERT INTO Bays (name)
VALUES ('Bay 1'), ('Bay 2');

INSERT INTO Appointments (
    customer_name,
    appointment_start,
    appointment_duration_minutes,
    appointment_price,
    appointment_end,
    bay_id,
    employee_id,
    service_detail_id,
    status
) VALUES
    ('Client A', '2025-11-15 10:00:00', 30, 20.00, '2025-11-15 10:30:00', 1, 1, 1, 'completed'),
    ('Client B', '2025-11-15 11:00:00', 45, 30.00, '2025-11-15 11:45:00', 2, 2, 2, 'planned');

SET FOREIGN_KEY_CHECKS = 1;
