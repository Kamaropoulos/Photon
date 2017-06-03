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
<style>
    #container {
        width: 80%;
        margin: auto auto;
    }

    .news_list {
        list-style: none;
    }

    .loadmore {
        color: #FFF;
        border-radius: 5px;
        width: 50%;
        height: 50px;
        font-size: 20px;
        background: #42B8DD;
        outline: 0;
    }

    .loadbutton {
        text-align: center;
    }

    #wrapper {
        width: 90%;
        max-width: 1100px;
        min-width: 800px;
        margin: 50px auto;
    }

    #columns {
        -webkit-column-count: 3;
        -webkit-column-gap: 10px;
        -webkit-column-fill: auto;
        -moz-column-count: 3;
        -moz-column-gap: 10px;
        -moz-column-fill: auto;
        column-count: 3;
        column-gap: 15px;
        column-fill: auto;
    }

    .pin {
        display: inline-block;
        background: #FEFEFE;
        border: 2px solid #FAFAFA;
        box-shadow: 0 1px 2px rgba(34, 25, 25, 0.4);
        margin: 0 2px 15px;
        -webkit-column-break-inside: avoid;
        -moz-column-break-inside: avoid;
        column-break-inside: avoid;
        padding: 15px;
        padding-bottom: 5px;
        background: -webkit-linear-gradient(45deg, #FFF, #F9F9F9);
        opacity: 1;

        -webkit-transition: all .2s ease;
        -moz-transition: all .2s ease;
        -o-transition: all .2s ease;
        transition: all .2s ease;
    }

    .pin img {
        width: 100%;
        border-bottom: 1px solid #ccc;
        padding-bottom: 15px;
        margin-bottom: 5px;
    }

    .pin p {
        font: 12px/18px Arial, sans-serif;
        color: #333;
        margin: 0;
    }

    @media (min-width: 960px) {
        #columns {
            -webkit-column-count: 4;
            -moz-column-count: 4;
            column-count: 4;
        }
    }

    @media (min-width: 1100px) {
        #columns {
            -webkit-column-count: 5;
            -moz-column-count: 5;
            column-count: 5;
        }
    }

    #columns:hover .pin:not(:hover) {
        opacity: 0.4;
    }

    ul {
        list-style-type: none;
    }
</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '.loadmore', function () {
        $(this).text('Loading...');
        var ele = $(this).parent('li');
        $.ajax({
            url: 'loadmore.php',
            type: 'POST',
            data: {
                page: $(this).data('page'),
            },
            success: function (response) {
                if (response) {
                    ele.hide();
                    $(".news_list").append(response);
                }
            }
        });
    });
</script>
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