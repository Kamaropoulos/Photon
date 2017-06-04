<?php
session_start();
include_once("connection.php");

if (isset($_SERVER['QUERY_STRING'])) {
    $id = (int)mysqli_real_escape_string($conn, $_SERVER['QUERY_STRING']);
    if ($id == 0) {
        if (isset($_SESSION['userid'])) {
            $id = $_SESSION['userid'];
        } else {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

    }
} else {
    $id = $_SESSION['userid'];
}

$sql = "SELECT images.*, users.*, sum(images.likes) as sum_likes, sum(images.page_views) as sum_views
FROM images
JOIN users ON users.uid = images.uid
WHERE users.uid = " . $id;
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if(!isset($row['uid'])){
    header('HTTP/1.0 404 Not Found');
    exit();
}

include_once('header.php');


?>

<style>
    .stats {
        text-align: center;
        text-shadow: 1px 1px 0px #fff;
        margin-top: 25px;

    }

    .stats li {
        width: 125px;
    }

    .stats span {
        font-family: Helvetica;
        font-weight: bold;
        text-shadow: 1px 1px 0px #fff;
        font-size: 4em;

        display: block;
        line-height: 1em;
    }

    /* Large desktop */
    @media (min-width: 1200px) {
    }

    /* Portrait tablet to landscape and desktop */
    @media (min-width: 768px) and (max-width: 979px) {
    }

    /* Landscape phone to portrait tablet */
    @media (max-width: 767px) {
        .profile img {
            width: 75px;
        }

        .profile h2 {
            font-size: 1.7em;
        }

        .stats span {
            font-size: 2em;
        }
    }

    /* Landscape phones and down */
    @media (max-width: 480px) {
    }
</style>

<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<div class="container profile">
    <div class="row">
        <div class="span12">
            <div class="well well-small clearfix">
                <div class="row-fluid">
                    <div class="span2">
                        <img src="placeholder-user.png"
                             class="img-thumbnail"/>
                    </div>
                    <div class="span4">
                        <h2><?php echo $row['username']; ?></h2>
<!--                        <ul class="unstyled">-->
<!--                            <li><i class="icon-phone"></i> 916-241-3613</li>-->
<!--                            <li><i class="icon-envelope"></i> jonniespratley@me.com</li>-->
<!--                            <li><i class="icon-globe"></i> http://jonniespratley.me</li>-->
<!--                        </ul>-->
                    </div>
                    <div class="span6">
                        <ul class="inline stats">
                            <li>
                                <span><?php echo $row['followers']; ?></span>
                                Followers
                            </li>
                            <li>
                                <span><?php echo $row['sum_likes']; ?></span>
                                Likes
                            </li>
                            <li>
                                <span><?php echo $row['sum_views']; ?></span>
                                Views
                            </li>
                        </ul>
                        <div><!--/span6-->
                        </div><!--/row-->
                    </div>
                    <!--Body content-->
                </div>
            </div>

            <div class="well clearfix">


                <img class="pull-right"
                     src="http://kcdn3.klout.com/static/images/developers/dev-assets/powered-by-klout.png"
                     width="150px"/>
            </div>
        </div>
