<?php
require_once 'db_connect.php';

// Function to generate PDF report
function generatePDFReport($data, $month, $year) {
    require_once('tcpdf/tcpdf.php');
    
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Teacher Attendance Report - '.$month.' '.$year, 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    
    $html = '<table border="1" cellpadding="5">
        <tr>
            <th>Teacher ID</th>
            <th>Teacher Name</th>
            <th>Present Days</th>
            <th>Absent Days</th>
            <th>Leave Days</th>
            <th>Total Working Hours</th>
        </tr>';
    
    foreach($data as $row) {
        $html .= '<tr>
            <td>'.$row['teacher_id'].'</td>
            <td>'.$row['teacher_name'].'</td>
            <td>'.$row['present_days'].'</td>
            <td>'.$row['absent_days'].'</td>
            <td>'.$row['leave_days'].'</td>
            <td>'.$row['total_hours'].'</td>
        </tr>';
    }
    
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('teacher_attendance_'.$month.'_'.$year.'.pdf', 'D');
}

// Function to generate Excel report
function generateExcelReport($data, $month, $year) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="teacher_attendance_'.$month.'_'.$year.'.xls"');
    
    echo '<table border="1">
        <tr>
            <th colspan="6" style="text-align:center">Teacher Attendance Report - '.$month.' '.$year.'</th>
        </tr>
        <tr>
            <th>Teacher ID</th>
            <th>Teacher Name</th>
            <th>Present Days</th>
            <th>Absent Days</th>
            <th>Leave Days</th>
            <th>Total Working Hours</th>
        </tr>';
    
    foreach($data as $row) {
        echo '<tr>
            <td>'.$row['teacher_id'].'</td>
            <td>'.$row['teacher_name'].'</td>
            <td>'.$row['present_days'].'</td>
            <td>'.$row['absent_days'].'</td>
            <td>'.$row['leave_days'].'</td>
            <td>'.$row['total_hours'].'</td>
        </tr>';
    }
    
    echo '</table>';
    exit;
}

// Main logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $format = $_POST['format'];
    
    $sql = "SELECT 
                teacher_id,
                teacher_name,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days,
                ROUND(SUM(working_hours), 2) as total_hours
            FROM teacher_attendance
            WHERE month = ? AND year = ?
            GROUP BY teacher_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    if ($format == 'pdf') {
        generatePDFReport($data, $month, $year);
    } elseif ($format == 'excel') {
        generateExcelReport($data, $month, $year);
    }
}

// Fetch available months/years
$months = $conn->query("SELECT DISTINCT month, year FROM teacher_attendance ORDER BY year DESC, month DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Attendance Report</title>
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
        select, button {
            padding: 8px 12px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teacher Attendance Report</h1>
        
        <form method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="month">Month:</label>
                    <select id="month" name="month" required>
                        <option value="">Select Month</option>
                        <?php while($m = $months->fetch_assoc()): ?>
                            <option value="<?php echo $m['month']; ?>">
                                <?php echo $m['month']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="year">Year:</label>
                    <select id="year" name="year" required>
                        <option value="">Select Year</option>
                        <?php 
                        $current_year = date('Y');
                        for($y = $current_year; $y >= $current_year-5; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $current_year) ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="format">Format:</label>
                    <select id="format" name="format" required>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
            </div>
            
            <button type="submit">Generate Report</button>
 
            <a href="teachers_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>