<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<?php

if ($_POST) : {
    $root_pass = $_POST['root_pass'];
    $db_name = $_POST['db_name'];
    $username = $_POST['username'];
    $user_pass = $_POST['user_pass'];
    $host = $_POST['host'];

    mysql_connect($host, 'root', $root_pass);
    mysql_query("CREATE USER '$username'@'$host' IDENTIFIED BY '$user_pass';");
    mysql_query("GRANT ALL ON $username.* TO '$username'@'$host'");
    mysql_query("CREATE DATABASE $db_name");
    mysql_query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    mysql_close();

    include("connection.php");

    $sql_users = "CREATE TABLE `users` (
uid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(30) NOT NULL,
pass_hash CHAR(128) NOT NULL,
reg_date TIMESTAMP,
followers integer(8) default 0
);";

    $sql_follows = "CREATE TABLE `follows` (
fid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
follower_uid INT UNSIGNED NOT NULL,
followed_uid INT UNSIGNED NOT NULL
);";

    $sql_images = "CREATE TABLE `images` (
pid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
uid INT UNSIGNED,
title varchar(50) NOT NULL,
description text,
original_image text,
page_views int(7) default 0,
likes int(7) default 0,
foreign key (uid) references users(uid)
);";

    $sql_comments = "CREATE TABLE `comments` (
cid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pid INT UNSIGNED NOT NULL,
uid INT UNSIGNED NOT NULL,
comment text NOT NULL,
post_time TIMESTAMP,
foreign key (uid) references users(uid),
foreign key (pid) references images(pid)
);";

    $sql_categories = "CREATE TABLE `categories` (
cat_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
category text NOT NULL
);";


    mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`users`') or die(mysqli_error($conn));
    mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`follows`') or die(mysqli_error($conn));
    mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`images`') or die(mysqli_error($conn));
    mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`comments`') or die(mysqli_error($conn));
    mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`categories`') or die(mysqli_error($conn));

    if ($conn->query($sql_users) === TRUE) {
        echo "Table Users created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    if ($conn->query($sql_follows) === TRUE) {
        echo "Table Follows created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    if ($conn->query($sql_images) === TRUE) {
        echo "Table Users created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    if ($conn->query($sql_comments) === TRUE) {
        echo "Table Comments created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    if ($conn->query($sql_categories) === TRUE) {
        echo "Table Categories created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    $conn->close();

} else : ?>

    <form class="form-horizontal" method="post" action="setup.php">
        <fieldset>

            <!-- Form Name -->
            <legend>Photon Setup</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="db_name">Database Name</label>
                <div class="col-md-4">
                    <input id="db_name" name="db_name" type="text" placeholder="Database Name"
                           class="form-control input-md" required="">
                    <span class="help-block">The name of the database you want to use with Photon</span>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="username">Username</label>
                <div class="col-md-4">
                    <input id="username" name="username" type="text" placeholder="Username"
                           class="form-control input-md" required="">
                    <span class="help-block">Username to be used with Photon</span>
                </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="user_pass">Password</label>
                <div class="col-md-4">
                    <input id="user_pass" name="user_pass" type="password" placeholder="Password"
                           class="form-control input-md" required="">
                    <span class="help-block">Password for the Photon user</span>
                </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="root_pass">root Password</label>
                <div class="col-md-4">
                    <input id="root_pass" name="root_pass" type="password" placeholder="root Password"
                           class="form-control input-md" required="">
                    <span class="help-block">Your database's root password</span>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="host">Database Host</label>
                <div class="col-md-4">
                    <input id="host" name="host" type="text" placeholder="localhost" class="form-control input-md">

                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit"></label>
                <div class="col-md-4">
                    <button id="submit" name="submit" class="btn btn-default">Submit</button>
                </div>
            </div>

        </fieldset>
    </form>


<?php endif ?>