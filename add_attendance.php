<?php
session_start();
require_once("database.php");

// get list students
$query = "SELECT studentID, firstName, lastName FROM students ORDER BY lastName";
$statement = $db->prepare($query);
$statement->execute();
$students = $statement->fetchAll();
$statement->closeCursor();

// If the student_id was passed from the student's card, save it for pre—selection.
$preselect_id = filter_input(INPUT_GET, 'student_id', FILTER_VALIDATE_INT);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Attendance</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Add Attendance Record</h2>
    <?php if (!empty($_SESSION['attendance_success'])): ?>
    <p style="color:green;">🎉 <?php echo $_SESSION['attendance_success']; unset($_SESSION['attendance_success']); ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['attendance_error'])): ?>
    <p style="color:red;">⚠️ <?php echo $_SESSION['attendance_error']; unset($_SESSION['attendance_error']); ?></p>
    <?php endif; ?>

    <form action="save_attendance.php" method="post">
        <label for="studentID">Select Student:</label>

        <select name="studentID" id="studentID" required>
    <option value="">-- Select --</option>
   <?php foreach ($students as $student): ?>
    <option value="<?php echo $student['studentID']; ?>" 
        <?php if ($preselect_id === $student['studentID']) echo 'selected'; ?>>
        <?php echo htmlspecialchars($student['lastName'] . ", " . $student['firstName']); ?>
    </option>
<?php endforeach; ?>
</select>


        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Late">Late</option>
        </select>

        <input type="submit" value="Add Record">
    </form>

    <p><a href="index.php">← Back to Student List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
