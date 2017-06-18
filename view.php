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
WHERE images.pid = " . $id;
$result = $conn->query($sql);
if ($row = mysqli_fetch_assoc($result)) {

    $sql_view = "UPDATE images
                 SET page_views = page_views + 1
                 WHERE pid = " . $id;
    $conn->query($sql_view);


    include_once("header.php");
    ?>
    <script>
        function post() {
            var comm = document.getElementById("comment").value;

            if (comment) {
                $.ajax({
                    type: 'post',
                    url: 'comment.php',
                    data: {
                        image_id: <?=$id?>,
                        comment: comm
                    },
                    success: function (response) {
                        document.getElementById("comments").innerHTML = response + document.getElementById("comments").innerHTML;
                        document.getElementById("comment").value = "";
                    }
                });
            }
            return false;
        }
    </script>
    <!-- Page Content -->
    <style>
        /*body, html {*/
        /*margin: 0;*/
        /*padding: 0;*/
        /*}*/

        details {
            text-align: justify !important;
        }

        details:after {
            display: inline-block !important;
            width: 100% !important;
        }

        .cn {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .cont {
            padding-top: 1vh;
            padding-right: 1vh;
            padding-bottom: 1vh;
            padding-left: 1vh;
        }

        .modal-header-danger {
            color: #fff;
            padding: 9px 15px;
            border-bottom: 1px solid #eee;
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
            width: 100%;
            text-align: center;
        }

        img {
            height: 80%;
            vertical-align: top;
        }

        .modal-body .form-horizontal .col-sm-2,
        .modal-body .form-horizontal .col-sm-10 {
            width: 100%
        }

        .modal-body .form-horizontal .control-label {
            text-align: left;
        }

        .modal-body .form-horizontal .col-sm-offset-2 {
            margin-left: 15px;
        }

        textarea {
            width: 100%;
            height: 100px;
        }
    </style>
    <div class="container-fluid cont">
        <div class="row  cn">
            <div class="col-md-8">
                <div class="img_div">
                    <img src="images/<?= $row['original_image'] ?>" class="img-thumbnail">
                </div>
            </div>
            <br>
            <div class="container cont ">
                    <?php
                    echo "<h2 style=\"display: inline; color: #337ab7\">" . $row['title'] . "</h2>";
                    echo "<h4 style='display: inline;'> by <a href='profile.php?" . $row['uid'] . "'>" . $row['username'] . "</a></h4><br><br>";

                    if (isset($_SESSION['userid'])) {
                        if ($row['uid'] == $_SESSION['userid']) {
                            //echo "<a class=\"btn btn-danger pull-right\" href=\"#\"><i class=\"fa fa-trash pull-right\" aria-hidden=\"true\"></i> Delete</a>";
                            echo "<div class=\"btn-toolbar\">
                              
                              <a class=\"btn btn-default\" data-toggle=\"modal\" data-target=\"#myModalNorm\" href=\"#\">
                              <i class=\"fa fa-edit fa-lg\" ></i > Edit</a >
                              <a class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#DeleteModal\" href=\"#\">
                              <i class=\"fa fa-trash-o fa-lg\"></i > Delete</a >
                              </div>
                              
                              <!-- Modal -->
                                <div class=\"modal fade\" id=\"myModalNorm\" tabindex=\"-1\" role=\"dialog\" 
                                     aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                                    <div class=\"modal-dialog\">
                                        <div class=\"modal-content\">
                                            <!-- Modal Header -->
                                            <div class=\"modal-header\">
                                                <button type=\"button\" class=\"close\" 
                                                   data-dismiss=\"modal\">
                                                       <span aria-hidden=\"true\">&times;</span>
                                                       <span class=\"sr-only\">Close</span>
                                                </button>
                                                <h4 class=\"modal-title\" id=\"myModalLabel\">
                                                    Modal title
                                                </h4>
                                            </div>
                                            
                                            <!-- Modal Body -->
                                            <div class=\"modal-body\">
                                                
                                                <form action='update.php' method='post' role=\"form\">
                                                  <div class=\"form-group\">
                                                    <label for=\"Title\">Title</label>
                                                      <input type=\"text\" class=\"form-control\"
                                                      id=\"Title\" name='title' value='" . $row['title'] . "'/>
                                                  </div>
                                                  <div class=\"form-group\">
                                                    <label for=\"description\">Description</label>
                                                          <textarea class=\"form-control\" rows='5' name='description' id='description'>" . $row['description'] . "</textarea>
                                                  </div>
                                                  <input type='hidden' name='pid' value='" . $row['pid'] . "'>
                                                
                                                
                                                
                                            </div>
                                            
                                            <!-- Modal Footer -->
                                            <div class=\"modal-footer\">
                                                <button type=\"button\" class=\"btn btn-default\"
                                                        data-dismiss=\"modal\">
                                                            Close
                                                </button>
                                                <button type=\"button sumbit\" class=\"btn btn-primary\">
                                                    Save changes
                                                </button>
                                            </div></form>
                                        </div>
                                    </div>
                                </div>
                              
                              
                              <div id=\"DeleteModal\" class=\"modal fade\" role=\"dialog\">
                              
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
                                <button type=\"button\" class=\"btn btn-danger\" onclick=\"location.href = 'delete.php?" . $row['pid'] . "'\">Delete Image</button>
                            </div>
                        </div>

                    </div>
                </div>";
                        }
                    }
                    echo "<br><p>" . $row['description'] . "</p><br>";
                    ?>
            </div>
        </div>
        <div class="cont">
            <div>
                <h2>&nbsp;Comments</h2>
                <form method="post" action="" onsubmit="return post();">

                    <textarea id="comment" placeholder="Write your comment here..."></textarea><br>
                    <input type="submit" value="Post">

                </form>
                <div id="comments">
                    <?php
                    $comm_sql = "SELECT comments.*, users.username
                                    FROM comments
                                    JOIN users on users.uid = comments.uid
                                    WHERE comments.pid = " . $id . ";";
                    $comments = $conn->query($comm_sql);
                    while ($row_comm = mysqli_fetch_array($comments)) {
                        $username = $row_comm['username'];
                        $comment = $row_comm['comment'];
                        $timestamp_raw = new DateTime($row_comm['post_time']);
                        $timestamp = $timestamp_raw->format('F j, Y g:ia');

                        $uid = $row_comm['uid']; ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">Posted by <a href="profile.php?<?= $uid ?>"><?= $username ?></a>
                                at <?= $timestamp ?></div>
                            <div class="panel-body"><?= $comment ?></p></div>
                        </div>

                        <?php
                    }
                    ?>
                </div>
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