<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>


<?php

if ($_POST) : {
    $root_pass = $_POST['root_pass'];
    $db_name = $_POST['db_name'];
    $username = $_POST['username'];
    $user_pass = $_POST['user_pass'];
    $host = $_POST['host'];
    ?>

    <head>
        <title>Photon Installation</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://use.fontawesome.com/5747f3daf1.js"></script>
    </head>
    <body>

    <div class="container">
        <h2></h2>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Photon Installation</h3></div>
                <div class="panel-body">


                    <?php
                    echo "<i style='color: dodgerblue;' class=\"fa fa-cog\" aria-hidden=\"true\"></i> Photon Installation Started!<br>";
                    flush();
                    ob_flush();

                    $conn = new mysqli($host, 'root', $root_pass);
                    if ($conn->connect_error) {
                        die("<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Connection failed!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>");
                    }
                    echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Connected successfully to the server!<br>";
                    flush();
                    ob_flush();

                    if (!$conn->query("CREATE USER '$username'@'$host' IDENTIFIED BY '$user_pass';")) {
                        die("<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> User creation error!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>");
                    }
                    echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> User created successfully!<br>";
                    flush();
                    ob_flush();

                    if (!$conn->query("GRANT ALL ON $db_name.* TO '$username'@'$host';")) {
                        die("<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error granting privileges!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>");
                    }
                    echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Privileges granted successfully!<br>";
                    flush();
                    ob_flush();

                    if (!$conn->query("CREATE DATABASE $db_name;")) {
                        die("<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Database creation error!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>");
                    }
                    echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Database created successfully!<br>";
                    flush();
                    ob_flush();

                    $conn->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
                    $conn->close();

                    echo "<i style='color: dodgerblue;' class=\"fa fa-cog\" aria-hidden=\"true\"></i> Updating configuration file...<br>";
                    flush();
                    ob_flush();

                    $db_name_placeholder = "--db--";
                    $username_placeholder = "--username--";
                    $user_pass_placeholder = "--pass--";
                    $host_placeholder = "--host--";

                    $config = file_get_contents('connection.sample.php');

                    $config = str_replace($db_name_placeholder, $db_name, $config);
                    $config = str_replace($username_placeholder, $username, $config);
                    $config = str_replace($user_pass_placeholder, $user_pass, $config);
                    $config = str_replace($host_placeholder, $host, $config);

                    file_put_contents('connection.php', $config) or die("Error updating configuration file.");

                    echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Configuration file updated succesfully!<br>";
                    flush();
                    ob_flush();

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


                    mysqli_query($conn, "DROP TABLE IF EXISTS `$db_name`.`users`") or die(mysqli_error($conn));
                    mysqli_query($conn, "DROP TABLE IF EXISTS `$db_name`.`follows`") or die(mysqli_error($conn));
                    mysqli_query($conn, "DROP TABLE IF EXISTS `$db_name`.`images`") or die(mysqli_error($conn));
                    mysqli_query($conn, "DROP TABLE IF EXISTS `$db_name`.`comments`") or die(mysqli_error($conn));
                    mysqli_query($conn, "DROP TABLE IF EXISTS `$db_name`.`categories`") or die(mysqli_error($conn));

                    if ($conn->query($sql_users) === TRUE) {
                        echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Table Users created successfully!<br>";
                        flush();
                        ob_flush();
                    } else {
                        echo "<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error creating table Users!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>";
                        flush();
                        ob_flush();
                    }

                    if ($conn->query($sql_follows) === TRUE) {
                        echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Table Follows created successfully!<br>";
                        flush();
                        ob_flush();
                    } else {
                        echo "<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error creating table Follows!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>";
                        flush();
                        ob_flush();
                    }

                    if ($conn->query($sql_images) === TRUE) {
                        echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Table Images created successfully!<br>";
                        flush();
                        ob_flush();
                    } else {
                        echo "<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error creating table Images!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>";
                        flush();
                        ob_flush();
                    }

                    if ($conn->query($sql_comments) === TRUE) {
                        echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Table Comments created successfully!<br>";
                        flush();
                        ob_flush();
                    } else {
                        echo "<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error creating table Comments!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>";
                        flush();
                        ob_flush();
                        exit();
                    }

                    if ($conn->query($sql_categories) === TRUE) {
                        echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Table Categories created successfully!<br>";
                        flush();
                        ob_flush();
                    } else {
                        echo "<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error creating table Categories!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>";
                        flush();
                        ob_flush();
                        exit();
                    }
                    $admin_pass = htmlentities(mysqli_real_escape_string($conn, $_POST['admin_pass']));
                    $hashed_pass = hash('sha512', $admin_pass);
                    if ($conn->query("INSERT INTO users (username, pass_hash) VALUES('admin','$hashed_pass')")){
                        echo "<i style='color: green;' class=\"fa fa-check\" aria-hidden=\"true\"></i> Admin user created successfully!<br>";
                        flush();
                        ob_flush();
                    } else {
                        echo "<i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Error creating Admin user!<br><h4><i style='color: red;' class=\"fa fa-times\" aria-hidden=\"true\"></i> Photon Installation failed!</h4>";
                        flush();
                        ob_flush();
                        exit();
                    }

                    $conn->close();

                    echo "<h4 style='display: inline; color: green;'><i  class=\"fa fa-check\" aria-hidden=\"true\"></i> Photon Installation finished succesfully!</h4> &nbsp;&nbsp;<input style='display: inline;' type=\"button\" class=\"btn btn-info\" value=\"Visit Home\" onclick=\"location.href = 'index.php';\">";

                    ?>
                </div>
            </div>
        </div>

    </div>
    <?php

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
                <label class="col-md-4 control-label" for="root_pass">root password</label>
                <div class="col-md-4">
                    <input id="root_pass" name="root_pass" type="password" placeholder="root password"
                           class="form-control input-md">
                    <span class="help-block">Your database's root password</span>
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="host">Database Host</label>
                <div class="col-md-4">
                    <input id="host" name="host" type="text" placeholder="localhost" value='localhost'
                           class="form-control input-md">
                    <span class="help-block">Password to be used for the admin user</span>

                </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="admin_pass">Admin Password</label>
                <div class="col-md-4">
                    <input id="admin_pass" name="admin_pass" type="password" placeholder="admin password"
                           class="form-control input-md">
                    <span class="help-block">Your database's root password</span>
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