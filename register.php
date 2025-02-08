<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo "Invalid CSRF token.";
        exit;
    }

   
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $type = htmlspecialchars($_POST['type']);

   
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($type)) {
        echo "All fields are required.";
        exit;
    }

  
    $stmt = $conn->prepare("INSERT INTO registrations (name, email, phone, address, type) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }
    $stmt->bind_param("sssss", $name, $email, $phone, $address, $type);

   
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

