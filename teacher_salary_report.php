<?php
ob_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = (int)$_POST['month'];
    $year = (int)$_POST['year'];
    $format = $_POST['format'];
    
    // Validate input
    if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
        die("Invalid month or year specified");
    }
    
    // Process bonuses if submitted
    $bonuses = [];
    if (isset($_POST['bonuses']) && is_array($_POST['bonuses'])) {
        foreach ($_POST['bonuses'] as $teacher_id => $amount) {
            $bonuses[$teacher_id] = (float)$amount;
        }
    }

    // Calculate salary with deductions and bonuses
    $sql = "INSERT INTO teacher_salary (teacher_id, teacher_name, base_salary, month, year, 
            days_present, days_absent, days_leave, total_working_hours, deductions, bonuses, net_salary)
            SELECT 
                t.id as teacher_id,
                t.name as teacher_name,
                t.base_salary,
                ? as month,
                ? as year,
                SUM(CASE WHEN ta.status = 'present' THEN 1 ELSE 0 END) as days_present,
                SUM(CASE WHEN ta.status = 'absent' THEN 1 ELSE 0 END) as days_absent,
                SUM(CASE WHEN ta.status = 'leave' THEN 1 ELSE 0 END) as days_leave,
                SUM(ta.working_hours) as total_working_hours,
                SUM(CASE WHEN ta.status = 'leave' THEN 200 ELSE 0 END) as deductions,
                ? as bonuses,
                t.base_salary + ? - SUM(CASE WHEN ta.status = 'leave' THEN 200 ELSE 0 END) as net_salary
            FROM teachers t
            LEFT JOIN teacher_attendance ta ON t.id = ta.teacher_id 
                AND MONTH(ta.date) = ? 
                AND YEAR(ta.date) = ?
            WHERE t.id = ?
            GROUP BY t.id
            ON DUPLICATE KEY UPDATE 
                days_present = VALUES(days_present),
                days_absent = VALUES(days_absent),
                days_leave = VALUES(days_leave),
                total_working_hours = VALUES(total_working_hours),
                deductions = VALUES(deductions),
                bonuses = VALUES(bonuses),
                net_salary = VALUES(net_salary)";
    
    // First get all teachers to process individually
    $teachers = $conn->query("SELECT id FROM teachers WHERE status = 'active'");
    while ($teacher = $teachers->fetch_assoc()) {
        $teacher_id = $teacher['id'];
        $bonus_amount = $bonuses[$teacher_id] ?? 0;
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiddii", 
            $month, $year, 
            $bonus_amount, $bonus_amount,
            $month, $year,
            $teacher_id
        );
        $stmt->execute();
    }
    
    // Fetch salary data for report
    $salary_sql = "SELECT * FROM teacher_salary WHERE month = ? AND year = ?";
    $stmt = $conn->prepare($salary_sql);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $salary_data = $result->fetch_all(MYSQLI_ASSOC);
    
    // Generate report
    if ($format == 'pdf') {
        ob_end_clean();
        require_once 'tcpdf/tcpdf.php';
        
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // Changed to landscape
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('School Management System');
        $pdf->SetTitle("Teacher Salary Report - $month $year");
        $pdf->SetFont('dejavusans', '', 10); 
        $pdf->SetHeaderData('', 0, "Teacher Salary Report", "Month: " . date('F', mktime(0, 0, 0, $month, 1)) . " $year");
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, "Teacher Salary Report - " . date('F Y', mktime(0, 0, 0, $month, 1, $year)), 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        
        $html = '<style>
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        td { text-align: center; padding: 5px; }
        .left-align { text-align: left; }
        .right-align { text-align: right; }
    </style>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th width="18%">Teacher</th>
                <th width="12%">Base Salary</th>
                <th width="8%">Present</th>
                <th width="8%">Absent</th>
                <th width="8%">Leave</th>
                <th width="12%">Working Hours</th>
                <th width="12%">Deductions (₹200/leave)</th>
                <th width="12%">Bonuses</th>
                <th width="12%">Net Salary</th>
            </tr>
        </thead>
        <tbody>';
        
    $total_salary = 0;
    foreach($salary_data as $row) {
        $html .= '<tr>
            <td class="left-align">'.$row['teacher_name'].'</td>
            <td class="right-align">'.number_format($row['base_salary'], 2).'</td>
            <td>'.$row['days_present'].'</td>
            <td>'.$row['days_absent'].'</td>
            <td>'.$row['days_leave'].'</td>
            <td class="right-align">'.$row['total_working_hours'].'</td>
            <td class="right-align">'.number_format($row['deductions'], 2).'</td>
            <td class="right-align">'.number_format($row['bonuses'], 2).'</td>
            <td class="right-align">'.number_format($row['net_salary'], 2).'</td>
        </tr>';
        $total_salary += $row['net_salary'];
    }
    
    $html .= '<tr style="font-weight:bold;">
        <td colspan="8" class="right-align">Total Salary:</td>
        <td class="right-align">'.number_format($total_salary, 2).'</td>
    </tr></tbody></table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output("teacher_salary_{$month}_{$year}.pdf", 'D');
        exit;
        
    } elseif ($format == 'excel') {
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="teacher_salary_'.$month.'_'.$year.'.xls"');
        
        echo '<table border="1">
            <tr>
                <th>Teacher</th>
                <th>Base Salary</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Leave</th>
                <th>Working Hours</th>
                <th>Deductions (₹200/leave)</th>
                <th>Bonuses</th>
                <th>Net Salary</th>
            </tr>';
        
        foreach($salary_data as $row) {
            echo '<tr>
                <td>'.$row['teacher_name'].'</td>
                <td>₹'.number_format($row['base_salary'], 2).'</td>
                <td>'.$row['days_present'].'</td>
                <td>'.$row['days_absent'].'</td>
                <td>'.$row['days_leave'].'</td>
                <td>'.$row['total_working_hours'].'</td>
                <td>₹'.number_format($row['deductions'], 2).'</td>
                <td>₹'.number_format($row['bonuses'], 2).'</td>
                <td>₹'.number_format($row['net_salary'], 2).'</td>
            </tr>';
        }
        
        echo '</table>';
        exit;
    }
}

ob_end_flush();

// Fetch available months/years for reports
$months_result = $conn->query("
    SELECT DISTINCT MONTH(date) as month, YEAR(date) as year 
    FROM teacher_attendance 
    ORDER BY year DESC, month DESC
");
$months = [];
while($row = $months_result->fetch_assoc()) {
    $months[] = $row;
}

// Fetch active teachers for bonus input
$teachers_result = $conn->query("SELECT id, name FROM teachers WHERE status = 'active' ORDER BY name");
$teachers = [];
while($row = $teachers_result->fetch_assoc()) {
    $teachers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Salary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        select, input, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s;
            padding: 12px 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 1em;
        }
        .bonus-section {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .bonus-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .bonus-row label {
            flex: 0 0 200px;
            margin: 0;
        }
        .bonus-row input {
            flex: 0 0 150px;
            margin-left: 20px;
        }
        .note {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teacher Salary Report</h1>
        
        <form method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="month">Month:</label>
                    <select id="month" name="month" required>
                        <option value="">Select Month</option>
                        <?php foreach($months as $m): 
                            $monthName = date("F", mktime(0, 0, 0, $m['month'], 1));
                        ?>
                            <option value="<?= $m['month'] ?>">
                                <?= "$monthName {$m['year']}" ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" id="year" name="year" min="2000" max="2100" required 
                           value="<?= date('Y') ?>">
                </div>
                
                <div class="form-group">
                    <label for="format">Report Format:</label>
                    <select id="format" name="format" required>
                        <option value="pdf">PDF Document</option>
                        <option value="excel">Excel Spreadsheet</option>
                    </select>
                </div>
            </div>
            
            <div class="bonus-section">
                <h3>Bonus Amounts</h3>
                <p class="note">Enter bonus amounts for each teacher (leave blank for no bonus)</p>
                
                <?php foreach($teachers as $teacher): ?>
                <div class="bonus-row">
                    <label for="bonus_<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['name']) ?>:</label>
                    <input type="number" step="0.01" min="0" 
                           id="bonus_<?= $teacher['id'] ?>" 
                           name="bonuses[<?= $teacher['id'] ?>]" 
                           placeholder="Enter bonus amount">
                    <span style="margin-left: 10px;">₹</span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="note" style="margin-top: 20px;">
                <strong>Note:</strong> ₹200 will be deducted for each leave day automatically.
            </div>
            
            <button type="submit">Generate Salary Report</button>
            <a href="teachers_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>