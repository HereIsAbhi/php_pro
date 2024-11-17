<?php

// Include database connection file
include 'db.php';

// SQL to create 'users' table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    dob DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) !== TRUE) {
    echo "Error creating 'users' table: " . $conn->error . "<br>";
}

// SQL to create 'health_goals' table
$sql_goals = "CREATE TABLE IF NOT EXISTS health_goals (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    goal_type VARCHAR(50) NOT NULL,
    goal_title VARCHAR(100) NOT NULL,
    target TEXT NOT NULL,
    timeline DATE NOT NULL,
    reminders TEXT,
    notes TEXT,
    progress INT(3) DEFAULT 0,
    status VARCHAR(50) DEFAULT 'Not Started',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_goals) !== TRUE) {
    echo "Error creating 'health_goals' table: " . $conn->error . "<br>";
}

// SQL to create 'reports' table
$sql_reports = "CREATE TABLE IF NOT EXISTS reports (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    doctor VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_reports) !== TRUE) {
    echo "Error creating 'reports' table: " . $conn->error . "<br>";
}

// SQL to create 'appointments' table
$sql_appointments = "CREATE TABLE IF NOT EXISTS appointments (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    notes TEXT,
    status VARCHAR(50) DEFAULT 'Scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_appointments) !== TRUE) {
    echo "Error creating 'appointments' table: " . $conn->error . "<br>";
}

// SQL to create 'prescriptions' table
$sql_prescriptions = "CREATE TABLE IF NOT EXISTS prescriptions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_prescriptions) !== TRUE) {
    echo "Error creating 'prescriptions' table: " . $conn->error . "<br>";
}
