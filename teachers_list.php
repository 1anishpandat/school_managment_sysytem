<?php
// Include the database connection file
require_once 'db_connect.php';

// Fetch all teachers
$query = "SELECT * FROM teachers ORDER BY name";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching teachers: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .badge-active { background-color: #28a745; }
        .badge-inactive { background-color: #6c757d; }
        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.875em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Teacher List</h2>
            <a href="teacher_form.php" class="btn btn-success">Add New Teacher</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($teacher = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($teacher['id']) ?></td>
                    <td><?= htmlspecialchars($teacher['name']) ?></td>
                    <td><?= htmlspecialchars($teacher['email']) ?></td>
                    <td><?= htmlspecialchars($teacher['phone']) ?></td>
                    <td><?= htmlspecialchars($teacher['subject']) ?></td>
                    <td>₹<?= number_format($teacher['base_salary'], 2) ?></td>
                    <td>
                        <span class="badge <?= $teacher['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                            <?= ucfirst($teacher['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="teacher_form.php?id=<?= $teacher['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="teacher_attendance_form.php?teacher_id=<?= $teacher['id'] ?>" class="btn btn-sm btn-info">Attendance</a>
                        <a href="teacher_salary_report.php?teacher_id=<?= $teacher['id'] ?>" class="btn btn-sm btn-warning">Salary</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="alert alert-info">No teachers found in the database.</div>
        <?php endif; ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
    <a href="index.php" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; text-decoration: none; color: white; background-color: #6c757d; border-radius: 4px; border: none; cursor: pointer;">
        Cancel
    </a>
</div>
    <?php $conn->close(); ?>
</body>
</html>