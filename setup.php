<?php

mysql_connect('localhost','root','');
mysql_query("CREATE USER 'photon'@'localhost' IDENTIFIED BY '1234';");
mysql_query("GRANT ALL ON photon.* TO 'photon'@'localhost'");
mysql_query("CREATE DATABASE photon");
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
foreign key (uid) references users(uid),
foreign key (pid) references images(pid)
);";

mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`users`') or die(mysqli_error($conn));
mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`follows`') or die(mysqli_error($conn));
mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`images`') or die(mysqli_error($conn));
mysqli_query($conn, 'DROP TABLE IF EXISTS `photon`.`comments`') or die(mysqli_error($conn));

if ($conn->query($sql_users) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

if ($conn->query($sql_follows) === TRUE) {
    echo "Table follows created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

if ($conn->query($sql_images) === TRUE) {
    echo "Table Users created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

if ($conn->query($sql_comments) === TRUE) {
    echo "Table Photos created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();

?>