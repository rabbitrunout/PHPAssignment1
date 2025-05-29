 <?php 

    session_start();
    require_once('database.php');
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

    // Get form data
    $first_name = filter_input(INPUT_POST, 'first_name');
    // alternative
    // $first_name = $_POST['first_name'];

    $last_name = filter_input(INPUT_POST, 'last_name');
    $course = filter_input(INPUT_POST, 'course');
    $attendance = filter_input(INPUT_POST, 'attendance');
    $schedule = filter_input(INPUT_POST, 'schedule'); // assigns the value of the selected radio button
    $start_date = filter_input(INPUT_POST, 'start_date');
    $image_name = $_FILES['file1']['name'];

    // Check for duplicate email
    // require_once('database.php');
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

// Check for empty fields
   if ($first_name == null || $last_name == null || $course == null ||
    $attendance == null || $schedule == null || $start_date == null)

    {
        $_SESSION["add_error"] = "Invalid contact data, Check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die();
    }
    else
    {
        // Insert into database
        $query = 'UPDATE students
        SET firstName = :firstName, 
            lastName = :lastName, 
            course = :course, 
            attendance = :attendance, 
            schedule = :schedule,
            startDate = :startDate
        WHERE studentID = :studentID';

        $statement = $db->prepare($query);
        $statement->bindValue(':studentID', $student_id);
        $statement->bindValue(':firstName', $first_name);
        $statement->bindValue(':lastName', $last_name);
        $statement->bindValue(':course', $course);
        $statement->bindValue(':attendance', $attendance);
        $statement->bindValue(':schedule', $schedule);
        $statement->bindValue(':startDate', $start_date);  

        $statement->execute();
        $statement->closeCursor();
    
    }
// Save session data and redirect
    $_SESSION["fullName"] = $first_name . " " . $last_name;

    // redirect to confirmation page
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die(); //releases add_contact.php from memory

?>