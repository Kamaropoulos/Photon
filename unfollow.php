<?php
    session_start();
    include_once("connection.php");

    if($_POST && isset($_SESSION['userid'])){
        $follower_uid = $_SESSION['userid'];
        $unfollow_uid = (int)mysqli_real_escape_string($conn, $_POST['id']);

        if ($follower_uid == $unfollow_uid){
            $sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $unfollow_uid;
            $result = $conn->query($sql_follows);
            $row = mysqli_fetch_assoc($result);
            echo $row['follows'];
            exit();
        }

        $sql_check = "SELECT fid FROM follows WHERE follower_uid = " . $follower_uid . " AND followed_uid = " . $unfollow_uid .";";
        $result = $conn->query($sql_check);
        if (mysqli_num_rows($result) > 0){
            // User already follows
            $sql_unfollow = "DELETE FROM follows WHERE follower_uid = " . $follower_uid . " AND followed_uid = " . $unfollow_uid .";";
            $result_unfollow = $conn->query($sql_unfollow);

            $sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $unfollow_uid;
            $result = $conn->query($sql_follows);
            $row = mysqli_fetch_assoc($result);
            echo $row['follows'];
            exit();
        } else {
            //doesn't follow
            //return current follows
            $sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $unfollow_uid;
            $result = $conn->query($sql_follows);
            $row = mysqli_fetch_assoc($result);
            echo $row['follows'];
            exit();

//            $sql_follow = "INSERT INTO follows VALUES (NULL, " . $follower_uid .", ". $unfollow_uid .")";
//            $result = $conn->query($sql_follow);
//            if (!$result){
//                exit();
//            }
//
//            //return current follows
//            $sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $unfollow_uid;
//            $result = $conn->query($sql_follows);
//            $row = mysqli_fetch_assoc($result);
//            echo $row['follows'];
//            exit();

        }
    };