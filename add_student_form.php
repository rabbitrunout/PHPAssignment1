
<!DOCTYPE html>
<html>
<head>
    <title>Contact Manager - Add Student</title>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>
    <?php include ("header.php"); ?>

    <main>
        <h2>Add Student</h2>
        
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
                <input type="radio" name="schedule" value="morning"> Morning <br>
                <input type="radio" name="schedule" value="evening"> Evening <br>



                <label>Start Date of the Course:</label>
                <input type="date" name="start_date" /> <br />    

                <label>Upload Image:</label>
                <input type="file" name="file1" /> <br /> 
            </div>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Save Student" /> <br />
            </div>
        </form>

        <p><a href="index.php"> View Students List </a></p>
    </main>

    <?php include ("footer.php"); ?>
</body>
</html>