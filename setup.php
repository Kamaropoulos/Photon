<?php

mysqli_connect('localhost','root','');
mysqli_query("CREATE USER 'photon'@'localhost' IDENTIFIED BY '';");
mysqli_query("GRANT ALL ON photon.* TO 'photon'@'localhost'");
mysqli_query("CREATE DATABASE photon");
mysqli_close();

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
uid_follower INT UNSIGNED NOT NULL,
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
foreign key (uid) references users(uid),
foreign key (pid) references images(pid)
);";

if ($conn->query($sql_users) === TRUE) {
    echo "Table Users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_follows) === TRUE) {
    echo "Table Photos created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_images) === TRUE) {
    echo "Table Users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_comments) === TRUE) {
    echo "Table Photos created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>