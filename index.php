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
require 'conn.php';

$result = mysqli_query($conn, "
    SELECT *
    FROM user ORDER BY id DESC");

//mysqli_close($conn); // Closing connection. This line is very important. If you forget to close the connection, your database server may run out of connections and slow down your website.
?>
    <nav class="flex align-right">
        <ul>
            <li>
                <a href="index.php">Home</a>
            </li>
            <li>
            <a href="add.phtml">Add New</a>
            </li>
        </ul>
    </nav>
    <br>
    <br>
    <table class="table">
           <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Name
                </th>
                <th>
                    Email
                </th>
                <th>
                    Action
                </th>
            </tr>
          </thead>
        <tbody>
 <!-- Fetching data from database -->
        <?php
        while($row = mysqli_fetch_assoc($result)){
            echo "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>
                    <div class='btn-group'>
                        <button type='button' class='btn btn-outline-secondary'>Choose</button>
                        <button type='button' class='btn btn-outline-secondary dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown' aria-expanded='false'>
                            <span class='visually-hidden'>Toggle Dropdown</span>
                        </button>
                        <ul class='dropdown-menu dropdown-menu-end'>
                            <li>
                                <a class='dropdown-item' href='view.php?id={$row['id']}'>View</a>
                            </li>
                            <li>
                                <a class='dropdown-item' href='edit.php?id={$row['id']}'>Edit</a>
                            </li>
                            <li>
                                <a class='dropdown-item' href='delete.php?id={$row['id']}'>Delete</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            ";
        }
        ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>