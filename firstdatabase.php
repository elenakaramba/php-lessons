<?php

$conn = new mysqli("localhost","dblinda", "ldko(8Nyd!fg", "test1" );
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

$sql = "SELECT product_id, product_name, submission_date FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Product ID: " . $row["product_id"] . " " . "Product Name: " . $row["product_name"] . " " . "Date: " . $row["submission_date"] . "<br>";
    }
}else {
        echo "0 results";
    }
$conn->close();
?>