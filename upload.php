<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection settings
$servername = "localhost"; // Replace with your database server
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "students";      // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $file = $_FILES['file'];

    // Check if file was uploaded without errors
    if ($file['error'] == 0) {
        $filename = $file['name'];
        $filetype = $file['type'];
        $filesize = $file['size'];

        // Check the file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext == "xlsx" || $ext == "xls") {
            // Specify the path to save the file
            $destination = 'uploads/' . $filename;

            // Create uploads directory if not exists
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // Move the uploaded file to the destination
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Load the Excel file
                $spreadsheet = IOFactory::load($destination);
                $worksheet = $spreadsheet->getActiveSheet();

                // Iterate through each row of the worksheet in turn
                foreach ($worksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
                                                                       // even if a cell value is not set.
                                                                       // By default, only cells that have a value
                                                                       // set will be iterated.

                    $data = [];
                    foreach ($cellIterator as $cell) {
                        $data[] = $cell->getValue();
                    }

                    // Assuming columns: student_id, name, email
                    if (count($data) >= 3) {
                        $student_id = $data[0];
                        $name = $data[1];
                        $email = $data[2];

                        // Insert data into the database
                        $stmt = $conn->prepare("INSERT INTO student_data (branch, year, student_id, name, email) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $branch, $year, $student_id, $name, $email);
                        $stmt->execute();
                    }
                }

                echo "File uploaded and data saved successfully.";
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Error: Please upload an Excel file (.xlsx or .xls).";
        }
    } else {
        echo "Error: " . $file['error'];
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
