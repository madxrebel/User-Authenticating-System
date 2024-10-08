<?php 

    // connecting to database
    $conn = mysqli_connect('localhost', 'Jano', 'maniwatan', 'UASystem');

    // checking connection
    if (!$conn) {
        echo "error: " . mysqli_connect_error($conn);
    }

?>