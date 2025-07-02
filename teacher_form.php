<?php
require_once 'db_connect.php';

// Initialize variables
$teacher = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'qualification' => '',
    'subject' => '',
    'base_salary' => '0.00',
    'joining_date' => date('Y-m-d'),
    'status' => 'active'
];
$isEdit = false;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $teacher['name'] = trim($_POST['name']);
    $teacher['email'] = trim($_POST['email']);
    $teacher['phone'] = trim($_POST['phone']);
    $teacher['address'] = trim($_POST['address']);
    $teacher['qualification'] = trim($_POST['qualification']);
    $teacher['subject'] = trim($_POST['subject']);
    $teacher['base_salary'] = (float)$_POST['base_salary'];
    $teacher['joining_date'] = $_POST['joining_date'];
    $teacher['status'] = $_POST['status'];
    
    // Validate required fields
    if (empty($teacher['name'])) {
        $error = 'Name is required';
    } elseif ($teacher['base_salary'] < 0) {
        $error = 'Salary cannot be negative';
    } else {
        // Prepare SQL
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            // Update existing teacher
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("UPDATE teachers SET 
                name=?, email=?, phone=?, address=?, qualification=?, 
                subject=?, base_salary=?, joining_date=?, status=? 
                WHERE id=?");
            $stmt->bind_param("ssssssdssi", 
                $teacher['name'], $teacher['email'], $teacher['phone'],
                $teacher['address'], $teacher['qualification'], $teacher['subject'],
                $teacher['base_salary'], $teacher['joining_date'], $teacher['status'],
                $id);
        } else {
            // Insert new teacher
            $stmt = $conn->prepare("INSERT INTO teachers 
                (name, email, phone, address, qualification, subject, 
                base_salary, joining_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssdss", 
                $teacher['name'], $teacher['email'], $teacher['phone'],
                $teacher['address'], $teacher['qualification'], $teacher['subject'],
                $teacher['base_salary'], $teacher['joining_date'], $teacher['status']);
        }
        
        // Execute and redirect
        if ($stmt->execute()) {
            header("Location: teachers_list.php");
            exit();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
} elseif (isset($_GET['id'])) {
    // Load teacher data for editing
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM teachers WHERE id=$id");
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        $isEdit = true;
    } else {
        header("Location: teachers_list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit' : 'Add' ?> Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2><?= $isEdit ? 'Edit' : 'Add' ?> Teacher</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
            <?php endif; ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name*</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= htmlspecialchars($teacher['name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($teacher['email']) ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           value="<?= htmlspecialchars($teacher['phone']) ?>">
                </div>
                <div class="col-md-6">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" 
                           value="<?= htmlspecialchars($teacher['subject']) ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($teacher['address']) ?></textarea>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="qualification" class="form-label">Qualification</label>
                    <input type="text" class="form-control" id="qualification" name="qualification" 
                           value="<?= htmlspecialchars($teacher['qualification']) ?>">
                </div>
                <div class="col-md-4">
                    <label for="base_salary" class="form-label">Base Salary*</label>
                    <input type="number" step="0.01" class="form-control" id="base_salary" name="base_salary" 
                           value="<?= htmlspecialchars($teacher['base_salary']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="joining_date" class="form-label">Joining Date*</label>
                    <input type="date" class="form-control" id="joining_date" name="joining_date" 
                           value="<?= htmlspecialchars($teacher['joining_date']) ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Status</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_active" 
                               value="active" <?= $teacher['status'] === 'active' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status_active">Active</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_inactive" 
                               value="inactive" <?= $teacher['status'] === 'inactive' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status_inactive">Inactive</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Save' ?> Teacher</button>
            <a href="teachers_list.php" class="btn btn-secondary">Cancel</a>
 
        </form>
    </div>
</body>
</html>