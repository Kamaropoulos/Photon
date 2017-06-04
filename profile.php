<?php
session_start();
include_once("connection.php");

function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

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
                        <a class="btn btn-info" href="#">
                            <i class="fa fa-plus fa-lg"></i> Follow</a>
                    </div>
                    <div class="span6">
                        <ul class="inline stats">
                            <li>
                                <span><?php echo $row['followers']; ?></span>
                                Followers
                            </li>
                            <li>
                                <span><?php if (is_null($row['sum_likes'])) echo 0; else echo $row['sum_likes']; ?></span>
                                Likes
                            </li>
                            <li>
                                <span><?php if (is_null($row['sum_views'])) echo 0; else echo $row['sum_views']; ?></span>
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
                <div id="columns">
                    <ul class="news_list">
                        <?php
                        $resultsPerPage = 10;
                        $query = mysqli_query($conn, "SELECT * FROM `images` WHERE uid = $id ORDER BY `images`.`pid` DESC LIMIT 0 , $resultsPerPage");
                        while ($data = mysqli_fetch_array($query)) {
                            $id = $data['pid'];
                            $title = $data['title'];
                            $content = $data['description'];
                            $image = $data['original_image'];
                            //echo "<li><h3>$title</h3><p>$content<p></li>";
                            echo "<div class='pin'>
                       <a href='view.php?". $id ."'> <img src='images/" . $image . "'  /></a>
                        <h4><a href='view.php?" . $id . "'>" . $title . "</a><i style=\"color: #337ab7\" class=\"fa fa-thumbs-up pull-right\"></i></h4>
                        <p>" . limit_text($content, 20) . "</p>
                      </div>";
                        }
                        ?>
                    </ul>


                </div>
                <ul>
                    <li class="loadbutton">
                        <button class="loadmore" data-page="2">Load More</button>
                    </li>
                </ul>
            </div>
        </div>
