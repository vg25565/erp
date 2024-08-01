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

// Check if faculty is logged in
if (!isset($_SESSION['faculty_id'])) {
    die("Please log in first.");
}

$faculty_id = $_SESSION['faculty_id'];
$date = $_POST['date'];
$students = $_POST['students'] ?? [];

// Insert attendance records
foreach ($students as $student_id) {
    $stmt = $conn->prepare("INSERT INTO attendance (faculty_id, student_id, date, status) VALUES (?, ?, ?, 'Present')");
    $stmt->bind_param("iss", $faculty_id, $student_id, $date);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to timetable page
header("Location: timetable.php");
exit;

$conn->close();
?>
