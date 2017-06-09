<?php
    session_start();
    include_once("connection.php");

    if($_POST && isset($_SESSION['userid'])){
        $follower_uid = $_SESSION['userid'];
        $follow_uid = mysqli_real_escape_string($conn, $_POST['id']);

        $sql_check = "SELECT fid FROM follows WHERE uid_follower = " . $follower_uid . " AND followed_uid = " . $follow_uid .";";
        $result = $conn->query($sql_check);
        if (mysqli_num_rows($result) > 0){
            // User already follow
            //return current follows
            $sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $follow_uid;
            $result = $conn->query($sql_follows);
            $row = mysqli_fetch_assoc($result);
            echo $row['follows'];
            exit();
        } else {
            //follow user
            $sql_follow = "INSERT INTO follows VALUES (NULL, " . $follower_uid .", ". $follow_uid .")";
            $result = $conn->query($sql_follow);
            if (!$result){
                echo "-1";
                exit();
            }

            //return current follows
            $sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $follow_uid;
            $result = $conn->query($sql_follows);
            $row = mysqli_fetch_assoc($result);
            echo $row['follows'];
            exit();

        }
    } else {
        echo "-1";
    }