<!DOCTYPE html>
<html>
<head>
    <title>Students Directory</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<?php
session_start();
require("database.php");

$query = 'SELECT s.*, t.studentType 
          FROM students s 
          LEFT JOIN types t ON s.typeID = t.typeID';
$statement = $db->prepare($query);
$statement->execute();
$students = $statement->fetchAll();
$statement->closeCursor();

// Attendance statistics
$total = count($students);
$present = 0;
$absent = 0;

foreach ($students as $student) {
    if (strtolower($student['attendance']) === 'present') {
        $present++;
    } elseif (strtolower($student['attendance']) === 'absent') {
        $absent++;
    }
}

$stats = [
    'total' => $total,
    'present' => $present,
    'absent' => $absent
];
?>

<?php include("header.php"); ?>

<main>
    <h2>🍍 Bikini Bottom Student List 🍍</h2>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Course</th>
                <th>Attendance</th>
                <th>Schedule</th>
                <th>Start Date</th>
                <th>Type</th>
                <th>Photo</th>
                <th colspan="3">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td data-label="First Name"><?php echo htmlspecialchars($student['firstName']); ?></td>
                    <td data-label="Last Name"><?php echo htmlspecialchars($student['lastName']); ?></td>
                    <td data-label="Course"><?php echo htmlspecialchars($student['course']); ?></td>
                    <td data-label="Attendance"><?php echo htmlspecialchars($student['attendance']); ?></td>
                    <td data-label="Schedule"><?php echo htmlspecialchars($student['schedule']); ?></td>
                    <td data-label="Start Date"><?php echo htmlspecialchars($student['startDate']); ?></td>
                    <td data-label="Type"><?php echo htmlspecialchars($student['studentType']); ?></td>
                    <td data-label="Photo">
                        <img src="<?php echo './images/' . htmlspecialchars($student['imageName']); ?>" alt="Student Image" />
                    </td>
                    <td data-label="Update">
                        <form action="update_student_form.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>" />
                            <input type="submit" value="Update" />
                        </form>
                    </td>
                    <td data-label="Delete">
                        <form action="delete_student.php" method="post" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                     <td>
                            <form action="student_details.php" method="get">
                                <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>" />
                                <input type="submit" value="View Details" />
                            </form>
                        </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="attendance-stats">
    <p>🧮 Total Students: <strong><?php echo $stats['total']; ?></strong></p>
    <p>✅ Present: <strong><?php echo $stats['present']; ?></strong></p>
    <p>❌ Absent: <strong><?php echo $stats['absent']; ?></strong></p>
</div>
    </div>

    <p><a href="add_student_form.php">➕ Add Student</a></p>
    <p><a href="logout.php">🚪 Logout</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
