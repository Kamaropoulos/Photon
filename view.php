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

        .modal-header-danger {
            color:#fff;
            padding:9px 15px;
            border-bottom:1px solid #eee;
            background-color: #d9534f;
            -webkit-border-top-left-radius: 5px;
            -webkit-border-top-right-radius: 5px;
            -moz-border-radius-topleft: 5px;
            -moz-border-radius-topright: 5px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

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
                echo "<h2 style=\"color: #337ab7\">" . $row['title'] . "</h2>";
                if (isset($_SESSION['userid'])){
                    if ($row['uid'] == $_SESSION['userid']){
                        //echo "<a class=\"btn btn-danger pull-right\" href=\"#\"><i class=\"fa fa-trash pull-right\" aria-hidden=\"true\"></i> Delete</a>";
                        echo "<a class=\"btn btn-danger pull-right\" data-toggle=\"modal\" data-target=\"#DeleteModal\" href=\"#\">
                              <i class=\"fa fa-trash-o fa-lg\"></i> Delete</a><div id=\"DeleteModal\" class=\"modal fade\" role=\"dialog\">
                    <div class=\"modal-dialog\">

                        <!-- Modal content-->
                        <div class=\"modal-content\">
                            <div class=\"modal-header modal-header-danger\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                                <h4 class=\"modal-title\">Delete Image</h4>
                            </div>
                            <div class=\"modal-body\">
                                <p>Are you sure that you want to delete this image?</p>
                            </div>
                            <div class=\"modal-footer\">
                                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                <button type=\"button\" class=\"btn btn-danger\" onclick=\"location.href = 'delete.php?". $row['pid'] ."'\">Delete Image</button>
                            </div>
                        </div>

                    </div>
                </div>";
                        //echo "<a class=\"btn btn-danger pull-right\" href=\"#\"><i class=\"icon-trash icon-large\"></i> Delete</a>";
                    }
                }
                echo "<h4>by <a href='profile.php?". $row['uid']."'>". $row['username'] ."</a></h4><br>";
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