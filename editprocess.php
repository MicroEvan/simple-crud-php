<?php
include 'conn.php';

define('FIELD_REQUIRED_MESSAGE', '<span style="color:red;">Field is required.</span><br>');
define('DATA_ADDED_MESSAGE', '<span style="color:green;">Data Added Successfully.</span><br>');

if(isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    //Check if the data is in the correct format
    if(empty($name)||empty($age) || empty($email)){
        if(empty($name)){
            echo FIELD_REQUIRED_MESSAGE;
        }
        if(empty($age)){
            echo FIELD_REQUIRED_MESSAGE;
        }
        if(empty($email)){
            echo FIELD_REQUIRED_MESSAGE;
        }

    }else {
        $stmt = $conn->prepare("UPDATE `user` SET name=?, age=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $age, $email, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

