<?php
session_start();
include_once("connection.php");
if ($_POST){
    $id = (int)mysqli_real_escape_string($conn, $_POST['pid']);
    $title = htmlentities(mysqli_real_escape_string($conn, $_POST['title']));
    $description = htmlentities(mysqli_real_escape_string($conn, $_POST['description']));

    $sql = "SELECT images.*, users.username
            FROM images
            JOIN users ON users.uid = images.uid
            WHERE images.pid = " . $id;
    $result = $conn->query($sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if (isset($_SESSION['userid'])) {
            if ($row['uid'] == $_SESSION['userid']) {

                $sql = "UPDATE images SET title='" . $title . "', description='" . $description . "' WHERE pid=" . $id;

                if ($conn->query($sql) === TRUE) {
                    header('Location:view.php?' . $id);
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                header('HTTP/1.0 403 Forbidden');
                exit();
            }
        } else {
            header('HTTP/1.0 403 Forbidden');
            exit();
        }
    } else {
        header('HTTP/1.0 403 Forbidden');
        exit();
    }
    $conn->close();
}