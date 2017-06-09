<?php
    session_start();
    include_once("connection.php");

    if (isset($_SESSION['userid']) && isset($_POST['image_id']) && isset($_POST['comment'])){
        $pid = (int)mysqli_real_escape_string($conn, $_POST['image_id']);
        $comment = htmlentities(mysqli_real_escape_string($conn, $_POST['comment']));

        $sql_insert = "INSERT INTO comments values(NULL, ". $pid .", ". $_SESSION['userid'] .", '". $comment ."', NOW());";
        if ($conn->query($sql_insert) === TRUE){
            $id = $conn->insert_id;
        } else {
            echo $conn->error;
            exit();
        }

        $sql_select = "SELECT comments.*, users.username
                       FROM comments
                       JOIN users on users.uid = comments.uid
                       WHERE comments.cid = ". $id .";";
        $select = $conn->query($sql_select);

        if($row=mysqli_fetch_array($select)){
            $username = $row['username'];
            $comment = $row['comment'];
            $timestamp_raw = new DateTime($row['post_time']);
            $timestamp = $timestamp_raw->format('F j, Y g:ia');
            $uid = $row['uid']; ?>

            <div class="panel panel-default">
                <div class="panel-heading">Posted by <a href="profile.php?<?=$uid?>"><?=$username?></a> at <?=$timestamp?></div>
                <div class="panel-body"><?=$comment?></p></div>
            </div>
        <?php
        }
        exit();
    }