<?php
require 'vendor/autoload.php';  // Include PhpSpreadsheet autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ini_set('SMTP', 'localhost'); // Replace with your SMTP server address
ini_set('smtp_port', 25); // Replace with the appropriate port


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $dob = $_POST["dob"];
    $education = $_POST["education"];
    $feedback = $_POST["feedback"];

    // Create Excel spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Date of Birth');
    $sheet->setCellValue('D1', 'Education');
    $sheet->setCellValue('E1', 'Feedback');

    // Set data
    $sheet->setCellValue('A2', $name);
    $sheet->setCellValue('B2', $email);
    $sheet->setCellValue('C2', $dob);
    $sheet->setCellValue('D2', $education);
    $sheet->setCellValue('E2', $feedback);

    // Save Excel file
    $excelFileName = "survey_data_" . date("Y-m-d_H-i-s") . ".xlsx";
    $writer = new Xlsx($spreadsheet);
    $writer->save($excelFileName);

    // Send email with Excel attachment
    $to = $email;  // Use provided email address
    $subject = "Survey Data Submission";
    $message = "Please find attached the survey data submitted by $name.";
    $headers = "From: maximuslegend2004@gmail.com";  // Replace with your email address
    $boundary = md5(time());

    // Attach Excel file
    $attachment = chunk_split(base64_encode(file_get_contents($excelFileName)));
    $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"";
    $message = "This is a multi-part message in MIME format.\r\n\r\n--$boundary\r\n" .
               "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n" .
               "Content-Transfer-Encoding: 7bit\r\n\r\n" .
               $message . "\r\n\r\n--$boundary\r\n" .
               "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; name=\"$excelFileName\"\r\n" .
               "Content-Transfer-Encoding: base64\r\n" .
               "Content-Disposition: attachment; filename=\"$excelFileName\"\r\n\r\n" .
               $attachment . "\r\n\r\n--$boundary--";

    mail($to, $subject, $message, $headers);

    // Clear stored form data from localStorage
    echo "<script>window.localStorage.clear();</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submission Result</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Additional styling for submission result */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 100px 0;
        }

        .result-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h2>Thank you for your submission!</h2>
        <p>Your responses have been recorded:</p>
    </div>
</body>
</html>