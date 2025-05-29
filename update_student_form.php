<?php
    require_once('database.php');
    // get the data from the form
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

    // select the contact from the database
    $query = 'SELECT * FROM students WHERE studentID = :student_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':student_id', $student_id);        

        $statement->execute();
        $student = $statement->fetch();
        $statement->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Student Manager - Update Student</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Update Student</h2>

            <form action="update_student.php" method="post" enctype="multipart/form-data">

                <div id="data">
                   <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>" />
                   
                    <label>First Name:</label>
                    <input type="text" name="first_name" value="<?php echo $student['firstName']; ?>" /><br />
                    
                    <label>Last Name:</label>
                    <input type="text" name="last_name"
                        value="<?php echo $student['lastName']; ?>" /><br />

                    <label>Course:</label>
                    <input type="text" name="course"
                        value="<?php echo $student['course']; ?>" /><br />

                    <label>Attendance::</label>
                    <input type="text" name="attendance"
                        value="<?php echo $student['attendance']; ?>" /><br />

                    <label>Schedule:</label>
                    <input type="radio" name="schedule" value="morning"
                      <?php echo ($student['schedule'] == 'morning') ? 'checked' : ''; ?> /> Morning<br />
                    <input type="radio" name="schedule" value="evening"
                      <?php echo ($student['schedule'] == 'evening') ? 'checked' : ''; ?> /> Evening<br />

                    <label>Start Date:</label>
                    <input type="date" name="start_date"
                        value="<?php echo $student['startDate']; ?>" /><br />
                </div>
                <div id="buttons">
                    <label>&nbsp;</label>
                    <input type="submit" value="Update Student" /><br />
                </div>
            </form>
            <p><a href="index.php">View Students List</a></p>
        </main>
        <?php include("footer.php"); ?>
    </body>
</html>