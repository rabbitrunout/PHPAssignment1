<?php
    session_start();

     require_once('message.php');

    // get data from the form
    $user_name = filter_input(INPUT_POST, 'user_name');    
    $password = filter_input(INPUT_POST, 'password');

    $hash = password_hash($password, PASSWORD_DEFAULT);  

    $email_address = filter_input(INPUT_POST, 'email_address');
    
    require_once('database.php');
    $queryRegistrations = 'SELECT * FROM registrations';
    $statement = $db->prepare($queryRegistrations);
    $statement->execute();
    $registrations = $statement->fetchAll();

    $statement->closeCursor();

    foreach ($registrations as $registration)
    {
        if ($user_name == $registration["userName"])
        {
            $_SESSION["add_error"] = "Invalid data, Duplicate Username. Try again.";

            $url = "error.php";
            header("Location: " . $url);
            die();
        }
    }

    if ($user_name === null || $password === null)
    {
        $_SESSION["add_error"] = "Invalid registration data, Check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die();
    }
    else
    {        

        require_once('database.php');

        // Add the contact to the database
        $query = 'INSERT INTO registrations
            (userName, password, emailAddress)
            VALUES
            (:userName, :password, :emailAddress)';

        $statement = $db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->bindValue(':password', $hash);
        $statement->bindValue(':emailAddress', $email_address);

        $statement->execute();
        $statement->closeCursor();

    }

    $_SESSION["isLoggedIn"] = TRUE;
    $_SESSION["userName"] = $user_name;

    // set up email variables
    $to_address = $email_address;
    $to_name = $user_name;
    $from_address = 'YOUR_USERNAME@gmail.com';
    $from_name = 'Student Manager 2025';
    $subject = 'Student Manager 2025 - Registration Complete';
    $body = '<p>Thanks for registering with our site.</p>' .
        '<p>Sincerely,</p>' .
        '<p>Student Manager 2025</p>';
    $is_body_html = true;

    // Send email
    try
    {
        send_email($to_address, $to_name, $from_address, $from_name, $subject, $body, $is_body_html);
    }
    catch (Exception $ex)
    {
        $_SESSION["add_error"] = $ex->getMessage();
        header("Location: error.php");
        die();
    }

    // redirect to confirmation page
    $url = "register_confirmation.php";
    header("Location: " . $url);
    die(); // releases add_contact.php from memory

?>