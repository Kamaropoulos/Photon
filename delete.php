<?php
include_once("connection.php");

if (isset($_SERVER['QUERY_STRING'])) {
    $id = (int)mysqli_real_escape_string($conn, $_SERVER['QUERY_STRING']);
    if ($id == 0) {
        header('HTTP/1.0 403 Forbidden');
        exit();
    }
} else {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

$sql = "SELECT images.*, users.username
FROM images
JOIN users ON users.uid = images.uid
WHERE images.pid = " . $id;
$result = $conn->query($sql);
if ($row = mysqli_fetch_assoc($result)) {
    if (isset($_SESSION['userid'])) {
        if ($row['uid'] == $_SESSION['userid']) {
            $sql = "DELETE FROM images WHERE pid=" . $id;

            if ($conn->query($sql) === TRUE) {
                header('Location:index.php');
            } else {
                header('Location:view.php?' . $id);
            }
        } else {
            header('HTTP/1.0 403 Forbidden');
            exit();
        }
    } else {
        header('HTTP/1.0 403 Forbidden');
        exit();
    }
}