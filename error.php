  <?php
    session_start();
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Manager - Error</title>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>
    <?php include ("header.php"); ?>

    <main>
        <h2>Error</h2>
        <p>
            <?php  echo $_SESSION["add_error"];  ?>  
        </p>

        <p><a href="add_student_form.php"> Add Contact </a></p>
        <p><a href="index.php"> View Student List </a></p>
    </main>

    <?php include ("footer.php"); ?>
</body>
</html>