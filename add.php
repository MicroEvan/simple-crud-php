<?php
include 'conn.php';

define('FIELD_REQUIRED_MESSAGE', '<font color="red">Field is required.</font><br>');
define('DATA_ADDED_MESSAGE', '<font color="green">Data Added Successfully.</font><br>');



if(isset($_POST['Submit'])){
    //escape the input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age =  mysqli_real_escape_string($conn, $_POST['age']);
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
            echo '<br><a href="javascript:self.history.back();">Back</a>';
            }else{
                    // Prepare the SQL statement
                $stmt = $conn->prepare("INSERT INTO `user` (name, age, email) VALUES (?, ?, ?)");
                // Bind parameters to the prepared statement
                $stmt->bind_param("sis", $name, $age, $email); // 's' for string, 'i' for integer
                // Execute the prepared statement
                if ($stmt->execute()) {
                    echo DATA_ADDED_MESSAGE;
                    // Close the statement
                    $stmt->close();
                    echo '<br><a href="index.php">View Result</a>';
                } else {
                    echo "Error adding record: " . $stmt->error;
                }


         }
}
