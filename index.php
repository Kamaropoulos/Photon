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
<div id="content" class="container-fluid">
<section id="photos">
            <?php
            $resultsPerPage = 10;
            $query = mysqli_query($conn, "SELECT * FROM `images` ORDER BY `images`.`pid` DESC LIMIT 0 , $resultsPerPage");
            while ($data = mysqli_fetch_array($query)) {
                $id = $data['pid'];
                $image = $data['original_image'];
                echo "<a href='view.php?". $id ."'><img src=\"images/" . $image . "\"></a>";
            }
            ?>
</section>



    <ul id="button">
        <li class="loadbutton">
            <button class="loadmore" data-page="2">Load More</button>
        </li>
    </ul>
</div>

</body>
</html>