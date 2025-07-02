<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $school_name = $conn->real_escape_string($_POST['school_name']);
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $mother_name = $conn->real_escape_string($_POST['mother_name']);
    $sir_name = $conn->real_escape_string($_POST['sir_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phn_no = $conn->real_escape_string($_POST['phn_no']);
    $whatsapp_no = $conn->real_escape_string($_POST['whatsapp_no']);
    $standard = $conn->real_escape_string($_POST['standard']);
    $division = $conn->real_escape_string($_POST['division']);
    $house_color = $conn->real_escape_string($_POST['house_color']);
    $age = $conn->real_escape_string($_POST['age']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $guardian_name = $conn->real_escape_string($_POST['guardian_name']);
    $guardian_phnno = $conn->real_escape_string($_POST['guardian_phnno']);
    $medical_info = $conn->real_escape_string($_POST['medical_info']);
    $religion = $conn->real_escape_string($_POST['religion']);
    $fathers_occupation = $conn->real_escape_string($_POST['fathers_occupation']);
    $mothers_occupation = $conn->real_escape_string($_POST['mothers_occupation']);
    $unique_id = $conn->real_escape_string($_POST['unique_id']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $admission_date = $conn->real_escape_string($_POST['admission_date']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $postal_code = $conn->real_escape_string($_POST['postal_code']);

    // Handle file upload
    $photo = '';
    if (isset($_FILES['photo'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file;
        }
    }

    // Corrected SQL query - removed any potential stray semicolons
    $sql = "INSERT INTO student_info (
        school_name, student_name, father_name, mother_name, sir_name, 
        address, phn_no, whatsapp_no, standard, division, 
        house_color, age, dob, guardian_name, guardian_phnno, 
        medical_info, religion, fathers_occupation, mothers_occupation, 
        unique_id, photo, gender, admission_date, blood_group, postal_code
    ) VALUES (
        '".$school_name."', '".$student_name."', '".$father_name."', '".$mother_name."', '".$sir_name."',
        '".$address."', '".$phn_no."', '".$whatsapp_no."', '".$standard."', '".$division."',
        '".$house_color."', '".$age."', '".$dob."', '".$guardian_name."', '".$guardian_phnno."',
        '".$medical_info."', '".$religion."', '".$fathers_occupation."', '".$mothers_occupation."',
        '".$unique_id."', '".$photo."', '".$gender."', '".$admission_date."', '".$blood_group."', '".$postal_code."'
    )";

    if ($conn->query($sql) === TRUE) {
        header("Location: student_info_form.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    header("Location: student_info_form.php");
    exit();
}
?>