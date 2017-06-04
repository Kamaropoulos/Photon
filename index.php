<?php
include_once("header.php");

function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}
?>
<!--<div id="wrapper">-->
    <div id="columns">
        <ul class="news_list">
            <?php
            $resultsPerPage = 10;
            $query = mysqli_query($conn, "SELECT * FROM `images` ORDER BY `images`.`pid` DESC LIMIT 0 , $resultsPerPage");
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
<!--</div>-->

</body>
</html>