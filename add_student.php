<?php
    session_start();

    // get data from the form
    $first_name = filter_input(INPUT_POST, 'first_name');
    // alternative
    // $first_name = $_POST['first_name'];
    $last_name = filter_input(INPUT_POST, 'last_name');
    $teacher_note = filter_input(INPUT_POST, 'teacherNote');
    $course = filter_input(INPUT_POST, 'course');
    $attendance = filter_input(INPUT_POST, 'attendance');
    $schedule = filter_input(INPUT_POST, 'schedule'); // assigns the value of the selected radio button
    $start_date = filter_input(INPUT_POST, 'start_date');
    $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
    $image_name = $_FILES['file1']['name'];

    require_once('database.php');
    require_once('image_util.php');

    $base_dir = 'images/';

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

    $image_name = '';  // default empty

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
    // Process new image
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    // Save _100 version in DB
    $dot_pos = strrpos($original_filename, '.');
    $name_100 = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
    $image_name = $name_100;
    } else {
    // Use placeholder
    $placeholder = 'placeholder.jpg';
    $placeholder_100 = 'placeholder_100.jpg';
    $placeholder_400 = 'placeholder_400.jpg';

    if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
        process_image($base_dir, $placeholder);
    }

    $image_name = $placeholder_100;
}


            


        // Add the contact to the database 
        $query = 'INSERT INTO students
            (firstName, lastName, course, attendance, schedule, startDate, imageName, typeID, teacherNote)
            VALUES
            (:firstName, :lastName, :course, :attendance, :schedule, :startDate, :imageName, :typeID, :teacherNote)';


        $statement = $db->prepare($query);
        $statement->bindValue(':firstName', $first_name);
        $statement->bindValue(':lastName', $last_name);
        $statement->bindValue(':course', $course);
        $statement->bindValue(':attendance', $attendance);
        $statement->bindValue(':schedule', $schedule);
        $statement->bindValue(':startDate', $start_date);
        $statement->bindValue(':imageName', $image_name);
        $statement->bindValue(':typeID', $type_id);
        $statement->bindValue(':teacherNote', $teacher_note);
        $statement->execute();
        $statement->closeCursor();

    
    $_SESSION["fullName"] = $first_name . " " . $last_name;

    // redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); // releases add_contact.php from memory

?>