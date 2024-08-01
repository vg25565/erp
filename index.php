<?php
include "header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Student Data</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-center">Upload Student Data</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="branch" class="block text-gray-700 font-bold mb-2">Select Branch:</label>
                <select id="branch" name="branch" class="w-full px-3 py-2 border border-gray-300 rounded">
                    <option value="Computer Science">Computer Science</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Civil">Civil</option>
                    <!-- Add more branches as needed -->
                </select>
            </div>
            <div class="mb-4">
                <label for="year" class="block text-gray-700 font-bold mb-2">Select Year:</label>
                <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded">
                    <option value="FE-A">FE-A</option>
                    <option value="FE-B">FE-B</option>
                    <option value="FE-C">FE-C</option>
                    <option value="SE-A">SE-A</option>
                    <option value="SE-B">SE-B</option>
                    <option value="SE-C">SE-C</option>
                    <option value="TE-A">TE-A</option>
                    <option value="TE-B">TE-B</option>
                    <option value="TE-C">TE-C</option>
                    <!-- Add more years as needed -->
                </select>
            </div>
            <div class="mb-4">
                <label for="file" class="block text-gray-700 font-bold mb-2">Upload Excel File:</label>
                <input type="file" id="file" name="file" accept=".xlsx, .xls" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>
            <div class="text-center">
                <input type="submit" value="Upload" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600">
            </div>
        </form>
    </div>
</body>
</html>
