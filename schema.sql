CREATE DATABASE IF NOT EXISTS raspberry_iot;
USE raspberry_iot;

-- Table for sensor data
CREATE TABLE IF NOT EXISTS measurements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature FLOAT NOT NULL,
    humidity FLOAT NOT NULL,
    status ENUM('cold', 'normal', 'hot') NOT NULL,
    distance_cm FLOAT DEFAULT NULL, -- Bonus: HC-SR04P data
    light_level INT DEFAULT NULL,   -- Bonus: Light sensor data
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table for system configuration
CREATE TABLE IF NOT EXISTS settings (
    config_key VARCHAR(50) PRIMARY KEY,
    config_value VARCHAR(50) NOT NULL
);

-- Default system values
INSERT INTO settings (config_key, config_value) VALUES 
('temp_cold', '18'),
('temp_hot', '28'),
('frequency_min', '10'),
('force_read', '0');
