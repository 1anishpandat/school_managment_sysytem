<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .options {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .btn {
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .attendance-btn {
            background-color: #2ecc71;
        }
        .attendance-btn:hover {
            background-color: #27ae60;
        }
        .info-btn {
            background-color: #e74c3c;
        }
        .info-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class="options">
    <a href="student_info_form.php" class="btn info-btn">Student Information</a>
    <a href="attendance_form.php" class="btn attendance-btn">Student Attendance</a>
    <a href="teachers_list.php" class="btn btn-primary">Go to Teachers</a>
   
</div>
        
    </div>
</body>
</html>