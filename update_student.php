<?php 
session_start();

require_once('database.php');
require_once('image_util.php');

$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$course = filter_input(INPUT_POST, 'course');
$attendance = filter_input(INPUT_POST, 'attendance');
$schedule = filter_input(INPUT_POST, 'schedule');
$start_date = filter_input(INPUT_POST, 'start_date');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['image'] ?? null;

// 
if (!$student_id || !$first_name || !$last_name || !$course || !$attendance || !$schedule || !$start_date || !$type_id) {
    $_SESSION["add_error"] = "Invalid student data. Check all fields and try again.";
    header("Location: error.php");
    exit;
}

// 
$query = 'SELECT * FROM students WHERE studentID = :studentID';
$statement = $db->prepare($query);
$statement->bindValue(':studentID', $student_id);
$statement->execute();
$current_student = $statement->fetch();
$statement->closeCursor();

if (!$current_student) {
    $_SESSION["add_error"] = "Student not found.";
    header("Location: error.php");
    exit;
}

$old_image_name = $current_student['imageName'];
$image_name = $old_image_name;
$base_dir = 'images/';

// 
$queryStudents = 'SELECT * FROM students WHERE studentID != :studentID';
$statement = $db->prepare($queryStudents);
$statement->bindValue(':studentID', $student_id);
$statement->execute();
$students = $statement->fetchAll();
$statement->closeCursor();

foreach ($students as $s) {
    if ($first_name === $s["firstName"] && $last_name === $s["lastName"]) {
        $_SESSION["add_error"] = "Duplicate student name. Try again.";
        header("Location: error.php");
        exit;
    }
}

// 
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;

    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    $dot_pos = strrpos($original_filename, '.');
    $new_image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
    $image_name = $new_image_name;

    // Delete
    if ($old_image_name !== 'placeholder_100.jpg') {
        $old_base = substr($old_image_name, 0, strrpos($old_image_name, '_100'));
        $old_ext = substr($old_image_name, strrpos($old_image_name, '.'));
        $old_files = [$old_base . $old_ext, $old_base . '_100' . $old_ext, $old_base . '_400' . $old_ext];

        foreach ($old_files as $file) {
            $path = $base_dir . $file;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}

// Update student
$query = '
    UPDATE students
    SET firstName = :firstName,
        lastName = :lastName,
        course = :course,
        attendance = :attendance,
        schedule = :schedule,
        startDate = :startDate,
        typeID = :typeID,
        imageName = :imageName
    WHERE studentID = :studentID';

$statement = $db->prepare($query);
$statement->bindValue(':firstName', $first_name);
$statement->bindValue(':lastName', $last_name);
$statement->bindValue(':course', $course);
$statement->bindValue(':attendance', $attendance);
$statement->bindValue(':schedule', $schedule);
$statement->bindValue(':startDate', $start_date);
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':studentID', $student_id);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;
header("Location: update_confirmation.php");
exit;
?>
