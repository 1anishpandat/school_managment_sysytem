<?php
require_once 'db_connect.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST['teacher_id'];
    $teacher_name = $conn->real_escape_string($_POST['teacher_name']);
    $date = $_POST['date'];
    $status = $_POST['status'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $remarks = $conn->real_escape_string($_POST['remarks']);
    
    // Calculate working hours if both check-in/out exist
    $working_hours = 0;
    if ($check_in && $check_out && $status == 'present') {
        $start = new DateTime($check_in);
        $end = new DateTime($check_out);
        $diff = $start->diff($end);
        $working_hours = $diff->h + ($diff->i / 60);
    }
    
    // Extract month/year from date
    $month = date('F', strtotime($date));
    $year = date('Y', strtotime($date));
    
    // Insert attendance record
    $sql = "INSERT INTO teacher_attendance 
            (teacher_id, teacher_name, date, status, check_in, check_out, working_hours, remarks, month, year) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssdssi", $teacher_id, $teacher_name, $date, $status, $check_in, $check_out, $working_hours, $remarks, $month, $year);
    
    if ($stmt->execute()) {
        $success = "Attendance recorded successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetch teachers for dropdown
$teachers = $conn->query("SELECT id, name FROM teachers ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teacher Attendance</h1>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="teacher_attendance_form.php">
            <div class="form-group">
                <label for="teacher_id">Teacher:</label>
                <select id="teacher_id" name="teacher_id" required>
                    <option value="">Select Teacher</option>
                    <?php while($teacher = $teachers->fetch_assoc()): ?>
                        <option value="<?php echo $teacher['id']; ?>">
                            <?php echo htmlspecialchars($teacher['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="teacher_name">Teacher Name:</label>
                <input type="text" id="teacher_name" name="teacher_name" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="leave">Leave</option>
                        <option value="holiday">Holiday</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row" id="time-fields">
                <div class="form-group">
                    <label for="check_in">Check In:</label>
                    <input type="time" id="check_in" name="check_in">
                </div>
                
                <div class="form-group">
                    <label for="check_out">Check Out:</label>
                    <input type="time" id="check_out" name="check_out">
                </div>
            </div>
            
            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" rows="3"></textarea>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
    <button type="submit">Submit Attendance</button>
    <a href="teacher_attendance_report.php" class="btn" style="background-color: #ffffff;">Generate Report</a>
    <a href="teachers_list.php" class="btn btn-secondary">Cancel</a>

</div>
        </form>
    </div>

    <script>
        // Show/hide time fields based on status
        document.getElementById('status').addEventListener('change', function() {
            const timeFields = document.getElementById('time-fields');
            if (this.value === 'present') {
                timeFields.style.display = 'flex';
            } else {
                timeFields.style.display = 'none';
            }
        });
        
        // Auto-fill teacher name when ID is selected
        document.getElementById('teacher_id').addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                document.getElementById('teacher_name').value = selectedOption.text;
            }
        });
    </script>
</body>
</html>