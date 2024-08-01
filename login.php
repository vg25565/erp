<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Check login credentials
$stmt = $conn->prepare("SELECT id FROM faculty WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['faculty_id'] = $row['id'];
    header("Location: timetable.php");
    exit;
} else {
    echo "Invalid login credentials.";
}

$stmt->close();
$conn->close();
?>
