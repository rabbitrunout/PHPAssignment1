<?php
    session_start();
    require("database.php");
    $queryStudents = 'SELECT * FROM students';
    $statement1 = $db->prepare($queryStudents);
    $statement1->execute();
    $students = $statement1->fetchALL();

    $statement1->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students Directory</title>
    <link rel="stylesheet" type="txt/css" href="css/main.css"/>
</head>
<body>
    <?php include ("header.php");  ?>

    <main>
        <h2>Students List</h2>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Course</th>
                <th>Attendance</th>
                <th>Scheduale</th>
                <th>Start Date of the Course</th>
            </tr>

            <?php foreach ($students as $student):  ?>
                <tr>
                    <td><?php echo $student['firstName'] ?></td>
                    <td><?php echo $student['lastName'] ?></td>
                    <td><?php echo $student['course'] ?></td>
                    <td><?php echo $student['attendance'] ?></td>
                    <td><?php echo $student['scheduale'] ?></td>
                    <td><?php echo $student['stdCourse'] ?></td>
                </tr>

            <?php endforeach; ?>    
        </table>
    </main>

    <?php include ("footer.php"); ?>
    
</body>
</html>