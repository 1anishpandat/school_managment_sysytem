<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
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
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
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
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto 0;
            width: 200px;
        }
        button:hover {
            background-color: #45a049;
        }
        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }
        .radio-group label {
            font-weight: normal;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .section-title {
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Attendance Form</h1>
        <form action="process_attendance.php" method="post">
            <h2 class="section-title">Student Information</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" id="student_name" name="student_name" required>
                </div>
                <div class="form-group">
                    <label for="roll_no">Roll Number</label>
                    <input type="number" id="roll_no" name="roll_no" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="standard">Standard</label>
                    <select id="standard" name="standard" required>
                        <option value="">Select Standard</option>
                        <option value="Nursery">Nursery</option>
                        <option value="LKG">LKG</option>
                        <option value="UKG">UKG</option>
                        <option value="1">1st</option>
                        <option value="2">2nd</option>
                        <option value="3">3rd</option>
                        <option value="4">4th</option>
                        <option value="5">5th</option>
                        <option value="6">6th</option>
                        <option value="7">7th</option>
                        <option value="8">8th</option>
                        <option value="9">9th</option>
                        <option value="10">10th</option>
                        <option value="11">11th</option>
                        <option value="12">12th</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="division">Division</label>
                    <select id="division" name="division" required>
                        <option value="">Select Division</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="school_name">School Name</label>
                    <input type="text" id="school_name" name="school_name" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="unique_id">Unique ID</label>
                <input type="number" id="unique_id" name="unique_id" required>
            </div>
            
            <h2 class="section-title">Attendance Details</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="day">Day</label>
                    <select id="day" name="day" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="month">Month</label>
                    <select id="month" name="month" required>
                        <option value="">Select Month</option>
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
                    <label for="year">Year</label>
                    <select id="year" name="year" required>
                        <option value="">Select Year</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Attendance Status</label>
                <div class="radio-group">
                    <label><input type="radio" name="attendance_status" value="present" checked> Present</label>
                    <label><input type="radio" name="attendance_status" value="absent"> Absent</label>
                    <label><input type="radio" name="attendance_status" value="excused"> Excused</label>
                    <label><input type="radio" name="attendance_status" value="holiday"> Holiday</label>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="days_present">Days Present</label>
                    <input type="number" id="days_present" name="days_present" min="0" required>
                </div>
                <div class="form-group">
                    <label for="days_absent">Days Absent</label>
                    <input type="number" id="days_absent" name="days_absent" min="0" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="total_attendance">Total Attendance</label>
                    <input type="number" id="total_attendance" name="total_attendance" min="0" required>
                </div>
                <div class="form-group">
                    <label for="total_lectures">Total Lectures</label>
                    <input type="number" id="total_lectures" name="total_lectures" min="0" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="excuse">Excuse Date (if applicable)</label>
                    <input type="date" id="excuse" name="excuse">
                </div>
                <div class="form-group">
                    <label for="holidays">Holidays</label>
                    <input type="number" id="holidays" name="holidays" min="0" required>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
    <button type="submit">Submit Attendance</button>
    <div style="text-align:; margin-top: 10px;margin-left: 100px;margin-right: 190px">
    <a href="index.php" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; text-decoration: none; color: white; background-color: #6c757d; border-radius: 4px; border: none; cursor: pointer;">
        Cancel
    </a>
</div>
    <a href="attendance_report.php" class="btn" style="background-color: #fffff;">View Total Attendance</a>
</div>
            
        </form>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const daysPresent = document.getElementById('days_present');
    const daysAbsent = document.getElementById('days_absent');
    const totalAttendance = document.getElementById('total_attendance');
    const totalLectures = document.getElementById('total_lectures');
    
    function calculateTotals() {
        const present = parseInt(daysPresent.value) || 0;
        const absent = parseInt(daysAbsent.value) || 0;
        
        totalAttendance.value = present;
        totalLectures.value = present + absent;
    }
    
    daysPresent.addEventListener('input', calculateTotals);
    daysAbsent.addEventListener('input', calculateTotals);
});
</script>
</body>
</html>