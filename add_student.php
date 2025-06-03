<?php
    session_start();

    require_once 'image_util.php'; // the process_image function

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    if (isset($_FILES['file1']))
    {
        $filename = $_FILES['file1']['name'];

        if (!empty($filename))
        {
            $source = $_FILES['file1']['tmp_name'];

            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

            move_uploaded_file($source, $target);

            // create the '400' and '100' versions of the image
            process_image($image_dir_path, $filename);
        }
    }

    // get data from the form
    $first_name = filter_input(INPUT_POST, 'first_name');
    // alternative
    // $first_name = $_POST['first_name'];
    $last_name = filter_input(INPUT_POST, 'last_name');
    $course = filter_input(INPUT_POST, 'course');
    $attendance = filter_input(INPUT_POST, 'attendance');
    $schedule = filter_input(INPUT_POST, 'schedule'); // assigns the value of the selected radio button
    $start_date = filter_input(INPUT_POST, 'start_date');
    $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

    $image_name = $_FILES['file1']['name'];

    // $i = strrpos($image_name, '.');
    // $image_name = substr($image_name, 0, $i);
    // $ext = substr($image_name, $i);
    // $image_name_100 = $image_name . '_100' . $ext;

    require_once('database.php');
    $queryStudents = 'SELECT * FROM students';
    $statement1 = $db->prepare($queryStudents);
    $statement1->execute();
    $students = $statement1->fetchAll();
    $statement1->closeCursor();

    foreach ($students as $student)
    {
        if ($first_name == $student["firstName"] &&
            $last_name == $student["lastName"])
        {
            $_SESSION["add_error"] = "Invalid data. Duplicate full name. Try again.";

            $url = "error.php";
            header("Location: " . $url);
            die();
        }
    }

    if ($first_name == null || $last_name == null ||
        $course == null || $attendance == null  || $schedule == null  || $start_date == null || $type_id === false)

    {
        $_SESSION["add_error"] = "Invalid contact data, Check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die();
    }
    else
    {        

        require_once('database.php');

        // Add the contact to the database
        $query = 'INSERT INTO students
            (firstName, lastName, course, attendance, schedule, startDate, imageName, typeID)
            VALUES
            (:firstName, :lastName, :course, :attendance, :schedule, :startDate, :imageName, :typeID)';

        $statement = $db->prepare($query);
        $statement->bindValue(':firstName', $first_name);
        $statement->bindValue(':lastName', $last_name);
        $statement->bindValue(':course', $course);
        $statement->bindValue(':attendance', $attendance);
        $statement->bindValue(':schedule', $schedule);
        $statement->bindValue(':startDate', $start_date);
        $statement->bindValue(':imageName', $image_name);
        $statement->bindValue(':typeID', $type_id);
        $statement->execute();
        $statement->closeCursor();

    }
    $_SESSION["fullName"] = $first_name . " " . $last_name;

    // redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); // releases add_contact.php from memory

?>