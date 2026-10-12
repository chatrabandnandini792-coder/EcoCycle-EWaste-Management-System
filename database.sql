CREATE DATABASE IF NOT EXISTS ewaste_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ewaste_management;

CREATE TABLE IF NOT EXISTS pickup_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address TEXT NOT NULL,
    waste_type VARCHAR(100) NOT NULL,
    quantity_kg DECIMAL(10,2) NOT NULL,
    pickup_date DATE NOT NULL,
    notes TEXT NULL,
    status ENUM('Pending','Approved','Collected','Recycled','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_pickup_date (pickup_date)
) ENGINE=InnoDB;
