 <?php
    session_start();    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Student Manager - Login Confirmation</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Login Confirmation</h2>
            <p>
                Thank you, <?php echo $_SESSION["userName"]; ?> for
                logging in.
            </p>

            <p>You are logged in and may proceed to the student list by clicking below.</p>
            
            <p><a href="index.php">Student List</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>