<?php include('connection.php'); ?>
<?php
if (isset($_POST['page'])):
    $resultsPerPage = 5;
    $paged = $_POST['page'];
    $sql = "SELECT * FROM `images` ORDER BY `images`.`pid` DESC";
    if ($paged > 0) {
        $page_limit = $resultsPerPage * ($paged - 1);
        $pagination_sql = " LIMIT  $page_limit, $resultsPerPage";
    } else {
        $pagination_sql = " LIMIT 0 , $resultsPerPage";
    }
    $result = mysqli_query($conn, $sql . $pagination_sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        while ($data = mysqli_fetch_array($result)) {
            $id = $data['pid'];
            $image = $data['original_image'];
            echo "<a href='view.php?". $id ."'><img src=\"images/" . $image . "\"></a>";

        }
    }
    if ($num_rows == $resultsPerPage) {
        ?>
        <li class="loadbutton">
            <button class="loadmore" data-page="<?php echo $paged + 1; ?>">Load More</button>
        </li>
        <?php
    } else {
        echo "<li style='text-align: center;'>No More Images</li>";
    }
endif;
?>