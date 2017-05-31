<?php

mysql_connect('localhost','root','');
mysql_query("CREATE USER 'photon'@'localhost' IDENTIFIED BY '';");
mysql_query("GRANT ALL ON photon.* TO 'photon'@'localhost'");
mysql_query("CREATE DATABASE photon");
mysql_close();

include("connection.php");

$sql_users = "CREATE TABLE `users` (
uid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(30) NOT NULL,
pass_hash CHAR(128) NOT NULL,
reg_date TIMESTAMP
);";

$sql_photos = "CREATE TABLE `photos` (
pid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
uid INT UNSIGNED,
title varchar(50) NOT NULL,
description text,
page_views int(7),
likes int(7),
foreign key (uid) references users(uid)
);";

if ($conn->query($sql_users) === TRUE) {
    echo "Table Users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_photos) === TRUE) {
    echo "Table Photos created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>