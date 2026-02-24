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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            /*background-color: #f4f4f4;*/
            margin: 0;
            /*padding: 20px;*/
        }
        .certificate-container {
            width: 923px; /* Allow it to grow with the image */
            height: 1256px; /* Automatically adjust height based on content or image */
            /*padding: 40px;*/
            background-color: white;
            /*border: 5px solid #4CAF50;*/
            text-align: center;
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
                    /* Background image styles */
            background-image: url('cert.png');
            background-size: cover; /* Ensures the image covers the entire table */
            background-position: center;
            background-repeat: no-repeat;
        }
        .certificate-header {
            font-size: 24px;
            color: #4CAF50;
            /*margin-bottom: 20px;*/
        }
        .certificate-details {
            text-align: center;
            /*margin: 20px 0;*/
        }
        .certificate-details b {
            color: #333;
        }
        .certificate-details p {
            margin: 5px 0;
        }
        .certificate-footer {
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
        .print-button {
            margin-top: 20px;
        }
        .print-button input {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .print-button input:hover {
            background-color: #45a049;
        }
        @media print {
    nav,
    .print-button,
    .certificate-footer{
        display: none;
    }
}

    </style>
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

    <table class="certificate-container">
    <thead>
        <tr>
            <th colspan="2" class="certificate-header">Certificate Details</th>
        </tr>
    </thead>
    <tbody class="certificate-details">
        <!-- Fetching data from database -->
        <?php
        while($row = mysqli_fetch_assoc($result)){
            echo "
            <tr>
                <th>ID</th>
                <td>{$row['id']}</td>
            </tr>
            <tr>
                <th >Name</th>
                <td >{$row['name']}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{$row['email']}</td>
            </tr>

            ";
        }
        ?>
    </tbody>
    <tfoot class="certificate-footer">
        <tr>
            <td colspan="2">
                <input id="printpagebutton" type="button" value="Print Certificate" onclick="window.print();"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
            </td>
        </tr>
    </tfoot>
</table>


    <script type="text/javascript">
        function printpage() {
            var printButton = document.getElementById("printpagebutton");
            printButton.style.visibility = 'hidden';
            window.print();
            printButton.style.visibility = 'visible';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
