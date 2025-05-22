 <?php
    session_start();

 ?>
 <!DOCTYPE html>
<html>
<head>
    <title>Students Directory Database Error</title>
    <link rel="stylesheet" type="txt/css" href="css/main.css"/>
</head>
<body>
    <?php include ("header.php");  ?>

    <main>
        <h2>Students List</h2>
        <p>There was an error connecting to the database.</p>
        <p>The database must be installed.</p>
        <p>MySQL must be running.</p>
        <p>Error mesage: <?php echo $_SESSION["database_error"]; ?></p>

        <p><a href="index.php">View Srudents Directory</a></p>
       
    </main>

    <?php include ("footer.php"); ?>
    
</body>
</html>