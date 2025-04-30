<?php
include('./conn/conn.php');

$username = 'admin';
$password = 'istdadmin'; // Replace this with the actual password you want to use

// Generate the hashed password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hashed_password]);

echo "User created successfully";
?>