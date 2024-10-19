
<?php
require 'conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

<?php

    //getting id from url
    $id = $_GET['id'];

    //create query object
    $query = "SELECT * FROM `user` WHERE id=$id";

$result = mysqli_query($conn,$query);

//mysqli_close($conn); // Closing connection. This line is very important. If you forget to close the connection, your database server may run out of connections and slow down your website.
?>
    <nav class="flex align-right">
        <ul>
            <li>
                <a href="index.php">Home</a>
            </li>
        </ul>
    </nav>

    <br>
    <br>

    <table>
        <tbody>
 <!-- Fetching data from database -->
        <?php
        while($row = mysqli_fetch_assoc($result)){
            echo "
            <tr>
                <th>
                    ID
                </th>
                <td>{$row['id']}</td>
            </tr>
            <tr>
                <th>
                    Name
                </th>
                <td>{$row['name']}</td>
            </tr>
            <tr>
                <th>
                    Email
                </th>
                <td>{$row['email']}</td>
            </tr>
            <tr>
                <th>
                    <a href='edit.php?id={$row['id']}'>Edit</a>
                </th>
            </tr>
            ";
        }
        ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>