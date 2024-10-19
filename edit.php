<?php
require_once 'conn.php';

 //getting id from url
$id = $_GET['id'];

//create query object
$query = "SELECT * FROM `user` WHERE id=$id";
//select data associated to id from users
$results = mysqli_query($conn, $query);

while($row = mysqli_fetch_array($results)){
    $name = $row['name'];
    $age = $row['age'];
    $email = $row['email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit Data</title>
</head>
<body>
    <a href="index.php">Home</a>
    <br><br>

    <form method="post" name="edit-form" action="editprocess.php" >
        <table class="table">
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" value="<?php echo $name;?>"></td>
            </tr>
            <tr>
                <td>Age:</td>
                <td><input type="text" name="age" value="<?php echo $age;?>"></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="text" name="email" value="<?php echo $email;?>"></td>
            </tr>
            <tr>
                <td><input type="hidden" name="id" value=<?php echo $_GET['id'];?>></td>
                    <input class="btn btn-primary" type="submit" name="update" value="update">
                    <a class="btn btn-secondary" href="index.php">Cancel</a>
            </tr>
            <tr>

            </tr>
        </table>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
