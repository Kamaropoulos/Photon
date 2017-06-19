<?php

session_start();
if (isset($_SESSION['userid']) && $_SESSION['userid'] == 1) {
    include_once('header.php'); ?>

    <div class="container">
        <h2>Categories</h2>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th class="text-right"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $cat_select = "SELECT * FROM categories;";
            $categories = $conn->query($cat_select);
            $i = 0;
            while ($row_cat = mysqli_fetch_array($categories)) {
                $i++;
                $cat_id = $row_cat['cat_id'];
                $category = $row_cat['category'];?>

                <tr>
                    <td><?= $cat_id?></td>
                    <td><?=$category?></td>
                    <td class="text-right">
                        <button class="btn btn-danger" ng-click="r.changeView('requests/edit/' + request.id)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <?php
            }
            ?><?php if ($i == 0){
                echo "<td>No categories found.</td>";
            } ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-12">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control input-lg" id="search-church"
                           placeholder="New Category Name">
                    <span class="input-group-btn">
                            <button class="btn btn-default btn-lg" type="submit"><i class="fa fa-plus"
                                                                                    aria-hidden="true"></i></button>
                        </span>
                </div>
            </div>
        </div>
    </div>

    <?php
} else {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

?>