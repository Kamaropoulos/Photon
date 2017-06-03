<?php
include_once("connection.php");

if (isset($_SERVER['QUERY_STRING'])) {
    $id = (int)mysqli_real_escape_string($conn, $_SERVER['QUERY_STRING']);
    if ($id == 0) {
//        http_response_code(404);
//        die();
        header('HTTP/1.0 404 Not Found');
        echo "<h1>Error 404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit();
    }
} else {
//    http_response_code(404);
//    die();
    header('HTTP/1.0 404 Not Found');
    echo "<h1>Error 404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}

//$sql = "SELECT * FROM images WHERE pid=" . $id;
$sql = "SELECT images.*, users.username
FROM images
JOIN users ON users.uid = images.uid
WHERE images.pid = ". $id;
$result = $conn->query($sql);
if ($row = mysqli_fetch_assoc($result)) {
    include_once("header.php");
    ?>
    <!-- Page Content -->
    <style>
        /*body, html {*/
        /*margin: 0;*/
        /*padding: 0;*/
        /*}*/

        .img_div {
            display: inline-block;
            height: 100vh;
            width: 100%;
            text-align: center;
        }

        img {
            height: 80%;
            vertical-align: top;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="img_div">
                    <img src="images/<?php echo $row['original_image']; ?>" class="img-thumbnail">
                </div>
            </div>
            <div class="col-md-3">
                <?php
                echo "<h2 style=\"color: #337ab7\">" . $row['title'] . "</h2>"; echo "<h4>by <a href='profile.php?". $row['uid']."'>". $row['username'] ."</a></h4><br>";
                echo "<p>" . $row['description'] . "</p><br>";
                ?>
            </div>
        </div>
    </div>
    <?php

} else {
//    http_response_code(404);
//    die();
    header('HTTP/1.0 404 Not Found');
    echo "<h1>Error 404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}

?>