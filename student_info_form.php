<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Form</title>
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
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
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
            margin: 30px auto 0;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Information Form</h1>
        <form action="process_student_info.php" method="post" enctype="multipart/form-data">
            <h2>Personal Information</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="student_name">Student Full Name</label>
                    <input type="text" id="student_name" name="student_name" required>
                </div>
                <div class="form-group">
                    <label for="sir_name">Sir Name</label>
                    <input type="text" id="sir_name" name="sir_name" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="3" max="25" required>
                </div>
                <div class="form-group">
                    <label for="blood_group">Blood Group</label>
                    <select id="blood_group" name="blood_group" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
            </div>
            
            <h2>Family Information</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="father_name">Father's Name</label>
                    <input type="text" id="father_name" name="father_name" required>
                </div>
                <div class="form-group">
                    <label for="mother_name">Mother's Name</label>
                    <input type="text" id="mother_name" name="mother_name" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="fathers_occupation">Father's Occupation</label>
                    <input type="text" id="fathers_occupation" name="fathers_occupation" required>
                </div>
                <div class="form-group">
                    <label for="mothers_occupation">Mother's Occupation</label>
                    <input type="text" id="mothers_occupation" name="mothers_occupation" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="guardian_name">Guardian Name (if different)</label>
                    <input type="text" id="guardian_name" name="guardian_name">
                </div>
                <div class="form-group">
                    <label for="guardian_phnno">Guardian Phone Number</label>
                    <input type="tel" id="guardian_phnno" name="guardian_phnno">
                </div>
            </div>
            
            <h2>Contact Information</h2>
            <div class="form-group">
                <label for="address">Full Address</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phn_no">Primary Phone Number</label>
                    <input type="tel" id="phn_no" name="phn_no" required>
                </div>
                <div class="form-group">
                    <label for="whatsapp_no">WhatsApp Number</label>
                    <input type="tel" id="whatsapp_no" name="whatsapp_no">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="number" id="postal_code" name="postal_code" required>
                </div>
                <div class="form-group">
                    <label for="religion">Religion</label>
                    <select id="religion" name="religion" required>
                        <option value="">Select Religion</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Muslim">Muslim</option>
                        <option value="Christian">Christian</option>
                        <option value="Sikh">Sikh</option>
                        <option value="Buddhist">Buddhist</option>
                        <option value="Jain">Jain</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            
            <h2>School Information</h2>
            <div class="form-group">
                <label for="school_name">School Name</label>
                <input type="text" id="school_name" name="school_name" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="standard">Standard/Class</label>
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
                    <label for="division">Division/Section</label>
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
                    <label for="house_color">House Color</label>
                    <select id="house_color" name="house_color" required>
                        <option value="">Select House</option>
                        <option value="Red">Red</option>
                        <option value="Blue">Blue</option>
                        <option value="Green">Green</option>
                        <option value="Yellow">Yellow</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="admission_date">Admission Date</label>
                    <input type="date" id="admission_date" name="admission_date" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="unique_id">Unique ID</label>
                <input type="text" id="unique_id" name="unique_id" required>
            </div>
            
            <h2>Medical Information</h2>
            <div class="form-group">
                <label for="medical_info">Any Medical Conditions/Allergies</label>
                <textarea id="medical_info" name="medical_info" rows="3"></textarea>
            </div>
            
            <h2>Student Photo</h2>
            <div class="form-group">
                <label for="photo">Upload Student Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>
            
            <button type="submit">Submit Student Information</button>
            <a href="index.php" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; text-decoration: none; color: white; background-color: #6c757d; border-radius: 4px; border: none; cursor: pointer;">
        Cancel
    </a>
        </form>
    </div>
</body>
</html>