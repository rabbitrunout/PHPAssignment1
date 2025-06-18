<?php
    require_once('database.php');
    $queryTypes = 'SELECT * FROM types';
    $statement = $db->prepare($queryTypes);
    $statement->execute();
    $types = $statement->fetchAll();
    $statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Manager - Add Student</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />

</head>
<body>
    <?php include ("header.php"); ?>

    <main>
        <h2> 📝 Add Student</h2>
        
        <form action="add_student.php" method="post" enctype="multipart/form-data" >

            <div id="data">
                <label>First Name:</label>
                <input type="text" name="first_name" /> <br />

                <label>Last Name:</label>
                <input type="text" name="last_name" /> <br />
                
                <label>Course:</label>
                <input type="text" name="course" /> <br />

                <label>Attendance: </label>
                <input type="text" name="attendance" /> <br />   
                    
                <label>Schedule:</label>
                <div class="radio-group">
                    <label class="custom-radio">
                    <input type="radio" name="schedule" value="morning" />
                    <span class="bubble"></span>
                        🌞 Morning
                    </label>

                    <label class="custom-radio">
                    <input type="radio" name="schedule" value="evening" />
                    <span class="bubble"></span>
                        🌙 Evening
                    </label>
                </div>
                

                <label>Start Date of the Course:</label>
                <input type="date" name="start_date" /> <br />  


               <label>Student Type:</label>
                    <select name="type_id">
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['typeID']; ?>">
                                <?php echo $type['studentType']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br /> 

                <label>Upload Image:</label>
                <input type="file" name="file1" /> <br /> 

                <label for="teacherNote">Teacher Note:</label>
                <textarea name="teacherNote" id="teacherNote" rows="4" placeholder="Enter any important comments..."></textarea><br />
            </div>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Save Student" /> <br />
            </div>
        </form>

        <p><a href="index.php"> ⬅ Back to Student List </a></p>
    </main>

    <?php include ("footer.php"); ?>
</body>
</html>