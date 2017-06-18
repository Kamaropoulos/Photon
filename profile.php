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

$sql_follows = "SELECT count(fid) as follows FROM follows WHERE followed_uid = " . $id;
$follows_result = $conn->query($sql_follows);
$follows_row = mysqli_fetch_assoc($follows_result);
$follows = $follows_row['follows'];

if (isset($_SESSION['userid'])){
    $isFollowing_sql = "SELECT fid FROM follows WHERE follower_uid = " . $_SESSION['userid'] . " AND followed_uid = " . $id .";";
    $isFollowing_result = $conn->query($isFollowing_sql);
    if ($isFollowing_result && (mysqli_num_rows($isFollowing_result) > 0)){
        $isFollowing = 1;
    } else $isFollowing = 0;
    if ($_SESSION['userid'] == $id){
        $isFollowing = 2;
    }
}



include_once('header.php');


?>

    <script>

        $(document).ready(function(){
            <?php if (!$isFollowing) : ?>
                $("#unfollow-btn").hide();
            <?php elseif ($isFollowing == 1): ?>
                $("#follow-btn").hide();
            <?php elseif ($isFollowing == 2) : ?>
                $("#unfollow-btn").hide();
                $("#follow-btn").hide();
            <?php endif ?>
            document.getElementById("follow-btn").onclick = function(){follow()};
            function follow(){
                var request = $.ajax({
                    type: "POST",
                    url: "follow.php",
                    data: {id: <?= $id ?>},
                    dataType: "html"
                });

                request.done(function(result){
                    $("#follows").html(result);
                    $("#unfollow-btn").show();
                    $("#follow-btn").hide();
                });
            }
        });
        $(document).ready(function(){
            document.getElementById("unfollow-btn").onclick = function(){unfollow()};

            function unfollow(){
                var request = $.ajax({
                    type: "POST",
                    url: "unfollow.php",
                    data: {id: <?= $id ?>},
                    dataType: "html"
                });

                request.done(function(result){
                    $("#follows").html(result);
                    $("#unfollow-btn").hide();
                    $("#follow-btn").show();
                });
            }
        });





        $(window).load(function() {
            // When the page has loaded
            $("#content").fadeIn(1000);
        });

        $(document).ready(function() {
            var x = document.images;
            var txt = "";
            var i;
            for (i = 0; i < x.length; i++) {
                $(x[i]).fadeIn(1000);
            }
        });

        $(document).on('click', '.loadmore', function () {
            $(this).text('Loading...');
            // var ele = $(this).parent('li');
            $.ajax({
                url: 'profile_loadmore.php',
                type: 'POST',
                data: {
                    page: $(this).data('page'),
                    id: <?= $id?>
                },
                success: function (response) {
                    if (response) {
                        var ele = document.getElementById("button");
                        ele.innerHTML = "";
                        if ((response.search("<li"))){
                            var p = response.search("<li");
                            var s = response.substr(p);
                            response = response.replace(s,"");
                            $("#button").append(s);
                            console.log("Button: " + s);
                        }
                        $("#photos").append(response);
                        window.scrollTo(0,document.body.scrollHeight);
                        console.log("Data: " + response);

                        var $target = $('html,body');
                        setTimeout($target.animate({scrollTop: $target.height()}, 1000), 500);

                        $(document).ready(function() {
                            var x = document.images;
                            var txt = "";
                            var i;
                            for (i = 0; i < x.length; i++) {
                                $(x[i]).fadeIn(1000);
                            }

                        });

                    }
                }
            });
        });
    </script>

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

        .fade {
            opacity: 1;
        }

        .fade:hover {
            opacity: 0.4;
        }

    }
</style>


<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<div class="container profile"><link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <div class="row">
        <div class="span12">
            <div class="well well-small clearfix">
                <div class="row-fluid">
                    <div class="span2">
                        <img src="images/placeholder-user.png"
                             class="img-thumbnail"/>
                    </div>
                    <div class="span4">
                        <h2><?php echo $row['username']; ?></h2><div id="follow-unfollow">
                            <button id="follow-btn" type="button" class="btn btn-info"><i class="fa fa-plus"></i> Follow</button>
                            <button id="unfollow-btn" type="button" class="btn btn-danger"><i class="fa fa-minus"></i> Unfollow</button>
                        </div>
                    </div>
                    <div class="span6">
                        <ul class="inline stats">
                            <li>
                                <span id="follows"><?= $follows ?></span>
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
            <div id="content" class="container-fluid">
                <section id="photos">
                        <?php
                        $resultsPerPage = 10;
                        $query = mysqli_query($conn, "SELECT * FROM `images` WHERE uid = $id ORDER BY `images`.`pid` DESC LIMIT 0 , $resultsPerPage");
                        $i = 0;
                        while ($data = mysqli_fetch_array($query)) {
                            $id = $data['pid'];
                            $image = $data['original_image'];
                            echo "<a href='view.php?". $id ."'><img src=\"images/" . $image . "\"></a>";
                            $i++;
                        }
                        if ($i == 10): ?>
                            </section>
                            <ul id="button">
                                <li class="loadbutton">
                                    <button class="loadmore" data-page="2">Load More</button>
                                </li>
                            </ul>
                        <?php else: ?>
                            </section>
                        <?php endif ?>

            </div>