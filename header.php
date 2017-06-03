<?php
session_start();
include_once(__DIR__ . "/connection.php");
?>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/grid.css">
    <script src="assets/js/grid.js"></script>
    <script src="https://use.fontawesome.com/5747f3daf1.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Photon</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <?php
                if (isset($_SESSION['username'])){
                    echo "<li><a href='upload.php'><i class='fa fa-upload'></i> Upload</a></li>";
                    echo "<li class=\"dropdown\">
                          <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">". $_SESSION['username'] . "
                          <span class=\"caret\"></span></a>
                              <ul class=\"dropdown-menu\">
                                <li><a href=\"#\">Profile</a></li>
                                <li><a href=\"#\">Your Content</a></li>
                                <li><a href='logout.php'>Log Out</a></li>
                              </ul>
                          </li>";
                } else {
                    echo "<li><a href='signup.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>
                          <li><a href='login.php'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>";
                }
            ?>
        </ul>
    </div>
</nav>