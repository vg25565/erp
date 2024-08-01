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

// Get the timetable for the logged-in faculty
$timetable_stmt = $conn->prepare("SELECT day_of_week, period, subject FROM timetables WHERE faculty_id = ?");
$timetable_stmt->bind_param("i", $faculty_id);
$timetable_stmt->execute();
$timetable_result = $timetable_stmt->get_result();

// Get the student details
$students_stmt = $conn->prepare("SELECT student_id, name, year FROM students");
$students_stmt->execute();
$students_result = $students_stmt->get_result();

// Calculate average attendance
$attendance_stmt = $conn->prepare("
    SELECT 
        student_id,
        COUNT(*) AS total_lectures,
        SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) AS present_count
    FROM attendance
    WHERE faculty_id = ?
    GROUP BY student_id
");
$attendance_stmt->bind_param("i", $faculty_id);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable and Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-4xl mt-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Your Timetable</h1>
        <table class="w-full border border-gray-300 mb-6">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Day</th>
                    <th class="border border-gray-300 px-4 py-2">Period</th>
                    <th class="border border-gray-300 px-4 py-2">Subject</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $timetable_result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['period']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['subject']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2 class="text-xl font-bold mb-4 text-center">Mark Attendance</h2>
        <form action="mark_attendance.php" method="post">
            <table class="w-full border border-gray-300 mb-6">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Student ID</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Year</th>
                        <th class="border border-gray-300 px-4 py-2">Present</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students_result->fetch_assoc()): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['year']); ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <input type="checkbox" name="students[]" value="<?php echo htmlspecialchars($row['student_id']); ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <input type="hidden" name="faculty_id" value="<?php echo htmlspecialchars($faculty_id); ?>">
            <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
            <div class="text-center">
                <input type="submit" value="Mark Attendance" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600">
            </div>
        </form>

        <h2 class="text-xl font-bold mt-8 mb-4 text-center">Attendance Summary</h2>
        <table class="w-full border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Student ID</th>
                    <th class="border border-gray-300 px-4 py-2">Total Lectures</th>
                    <th class="border border-gray-300 px-4 py-2">Present</th>
                    <th class="border border-gray-300 px-4 py-2">Average Attendance (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $attendance_result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['total_lectures']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['present_count']); ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php
                            $average = ($row['total_lectures'] > 0) ? ($row['present_count'] / $row['total_lectures']) * 100 : 0;
                            echo number_format($average, 2) . '%';
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$timetable_stmt->close();
$students_stmt->close();
$attendance_stmt->close();
$conn->close();
?>
