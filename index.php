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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Students Directory</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>🍍 Bikini Bottom Student List 🍍</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Course</th>
                <th>Attendance</th>
                <th>Schedule</th>
                <th>Start Date</th>
                <th>Type</th>
                <th>Photo</th>
                <th colspan="2">Actions</th>
            </tr>

            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($student['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($student['course']); ?></td>
                    <td><?php echo htmlspecialchars($student['attendance']); ?></td>
                    <td><?php echo htmlspecialchars($student['schedule']); ?></td>
                    <td><?php echo htmlspecialchars($student['startDate']); ?></td>
                    <td><?php echo htmlspecialchars($student['studentType']); ?></td>
                    <td>
                        <img src="<?php echo './images/' . htmlspecialchars($student['imageName']); ?>" 
                             alt="Student Image" />
                    </td>
                    <td>
                        <form action="update_student_form.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>" />
                            <input type="submit" value="Update" />
                        </form>
                    </td>
                    <td>
                        <form action="delete_student.php" method="post" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

        <p><a href="add_student_form.php">➕ Add Student</a></p>
        <p><a href="logout.php">🚪 Logout</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
