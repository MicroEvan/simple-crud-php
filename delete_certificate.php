<?php
 include 'conn.php';
//getting id of the data from url
$id = $_GET['id'];

//delete query
$query = "DELETE FROM `certificate` WHERE certificate_id=$id";

//delete data associated to id from users table in database
$result = mysqli_query($conn,$query);

//redirecting to the display page (index.php in our case)
header("Location:index.php");
