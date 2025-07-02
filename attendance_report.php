<?php
ob_start();
require_once 'db_connect.php';

// Function to generate PDF report
function generatePDFReport($data, $month, $year) {
    ob_end_clean();
    
    require_once('tcpdf/tcpdf.php');
    
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('School Management System');
    $pdf->SetTitle("Attendance Report - $month $year");
    $pdf->SetHeaderData('', 0, "Attendance Report", "Month: $month $year");
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage();
    
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, "Attendance Report - $month $year", 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    
    $html = '<style>
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        td { text-align: center; padding: 4px; }
        .left-align { text-align: left; }
        .right-align { text-align: right; }
    </style>
    <table border="1" cellpadding="4">
        <tr>
            <th width="18%">Student Name</th>
            <th width="10%">Roll No</th>
            <th width="10%">Class</th>
            <th width="10%">Present</th>
            <th width="10%">Absent</th>
            <th width="10%">Total Lectures</th>
            <th width="10%">Final Attendance</th>
            <th width="12%">Attendance %</th>
        </tr>';
    
    foreach($data as $row) {
        $final_attendance = $row['total_lectures'] - $row['days_absent'];
        $attendance_percentage = ($row['total_lectures'] > 0) ? 
            round(($final_attendance / $row['total_lectures']) * 100, 2) : 0;
        
        $html .= '<tr>
            <td class="left-align">'.$row['student_name'].'</td>
            <td>'.$row['roll_no'].'</td>
            <td>'.$row['standard'].' '.$row['division'].'</td>
            <td>'.$row['days_present'].'</td>
            <td>'.$row['days_absent'].'</td>
            <td>'.$row['total_lectures'].'</td>
            <td>'.$final_attendance.'</td>
            <td>'.$attendance_percentage.'%</td>
        </tr>';
    }
    
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("attendance_report_{$month}_{$year}.pdf", 'D');
    exit;
}

// Function to generate Excel report
function generateExcelReport($data, $month, $year) {
    ob_end_clean();
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="attendance_report_'.$month.'_'.$year.'.xls"');
    
    echo '<table border="1">
        <tr>
            <th>Student Name</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Present</th>
            <th>Absent</th>
            <th>Total Lectures</th>
            <th>Final Attendance</th>
            <th>Attendance %</th>
        </tr>';
    
    foreach($data as $row) {
        $final_attendance = $row['total_lectures'] - $row['days_absent'];
        $attendance_percentage = ($row['total_lectures'] > 0) ? 
            round(($final_attendance / $row['total_lectures']) * 100, 2) : 0;
        
        echo '<tr>
            <td>'.$row['student_name'].'</td>
            <td>'.$row['roll_no'].'</td>
            <td>'.$row['standard'].' '.$row['division'].'</td>
            <td>'.$row['days_present'].'</td>
            <td>'.$row['days_absent'].'</td>
            <td>'.$row['total_lectures'].'</td>
            <td>'.$final_attendance.'</td>
            <td>'.$attendance_percentage.'%</td>
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
                student_name, 
                roll_no, 
                standard, 
                division,
                SUM(days_present) as days_present,
                SUM(days_absent) as days_absent,
                SUM(total_lectures) as total_lectures
            FROM attendance 
            WHERE month = ? AND year = ?
            GROUP BY student_name, roll_no, standard, division";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    if ($format == 'pdf') {
        generatePDFReport($data, $month, $year);
    } elseif ($format == 'excel') {
        generateExcelReport($data, $month, $year);
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Attendance Report</h1>
        <form method="post">
            <div class="form-group">
                <label for="month">Select Month:</label>
                <select id="month" name="month" required>
                    <option value="">-- Select Month --</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="year">Select Year:</label>
                <select id="year" name="year" required>
                    <option value="">-- Select Year --</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="format">Report Format:</label>
                <select id="format" name="format" required>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
            </div>
            
            <button type="submit">Generate Report</button>
            <div style="text-align: center; margin-top: 20px;">
    <a href="attendance_form.php" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; text-decoration: none; color: white; background-color: #6c757d; border-radius: 4px; border: none; cursor: pointer;">
        Cancel
    </a>
</div>
        </form>
        
        <?php if (isset($data) && !empty($data)): ?>
            <h2>Attendance Summary for <?= htmlspecialchars($month.' '.$year) ?></h2>
            <table>
                <tr>
                    <th>Student Name</th>
                    <th>Roll No</th>
                    <th>Class</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Total Lectures</th>
                    <th>Final Attendance</th>
                    <th>Attendance %</th>
                </tr>
                <?php foreach($data as $row): 
                    $final_attendance = $row['total_lectures'] - $row['days_absent'];
                    $attendance_percentage = ($row['total_lectures'] > 0) ? 
                        round(($final_attendance / $row['total_lectures']) * 100, 2) : 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= $row['roll_no'] ?></td>
                    <td><?= htmlspecialchars($row['standard'].' '.$row['division']) ?></td>
                    <td><?= $row['days_present'] ?></td>
                    <td><?= $row['days_absent'] ?></td>
                    <td><?= $row['total_lectures'] ?></td>
                    <td><?= $final_attendance ?></td>
                    <td><?= $attendance_percentage ?>%</td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif (isset($data)): ?>
            <p>No attendance records found for the selected month and year.</p>
        <?php endif; ?>
    </div>
</body>
</html>