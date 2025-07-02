<?php
// Include the database connection at the very top
require_once 'db_connect.php';

// Verify connection is working
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify POST data exists
    if (!isset($_POST['student_name'])) {
        die("Required form fields are missing");
    }

    // Collect and sanitize input data
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $roll_no = $conn->real_escape_string($_POST['roll_no']);
    $standard = $conn->real_escape_string($_POST['standard']);
    $day = $conn->real_escape_string($_POST['day']);
    $date = $conn->real_escape_string($_POST['date']);
    $month = $conn->real_escape_string($_POST['month']);
    $year = $conn->real_escape_string($_POST['year']);
    $days_present = $conn->real_escape_string($_POST['days_present']);
    $days_absent = $conn->real_escape_string($_POST['days_absent']);
    $unique_id = $conn->real_escape_string($_POST['unique_id']);
    $excuse = isset($_POST['excuse']) ? $conn->real_escape_string($_POST['excuse']) : '';
    $holidays = $conn->real_escape_string($_POST['holidays']);
    $school_name = $conn->real_escape_string($_POST['school_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $total_attendance = $conn->real_escape_string($_POST['total_attendance']);
    $total_lectures = $conn->real_escape_string($_POST['total_lectures']);
    $division = $conn->real_escape_string($_POST['division']);
    $attendance_status = $conn->real_escape_string($_POST['attendance_status']);

    // Insert into database
    $sql = "INSERT INTO attendance (
        student_name, roll_no, standard, day, date, 
        month, year, days_present, days_absent, unique_id, 
        excuse, holidays, school_name, gender, total_attendance, 
        total_lectures, division, attendance_status
    ) VALUES (
        '$student_name', '$roll_no', '$standard', '$day', '$date',
        '$month', '$year', '$days_present', '$days_absent', '$unique_id',
        '$excuse', '$holidays', '$school_name', '$gender', '$total_attendance',
        '$total_lectures', '$division', '$attendance_status'
    )";

    if ($conn->query($sql) === TRUE) {
        header("Location: attendance_form.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    header("Location: attendance_form.php");
    exit();
}
?>