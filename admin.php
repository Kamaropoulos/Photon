<?php

session_start();
if (isset($_SESSION['userid']) && $_SESSION['userid'] == 1) {
    include_once('header.php'); ?>


    <script src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.form.min.js"></script>

    <script>
        function deleteRow(rowid) {
            var row = document.getElementById(rowid);
            row.parentNode.removeChild(row);
        }

        $(document).on('click', '.delete-btn', function () {
            var id = $(this).val();

            $.ajax({
                type: 'post',
                url: 'categories.php',
                data: {
                    type: "delete",
                    id: id
                },
                success: function (response) {
                    if (response == 0) {
                        deleteRow("row" + id);
                    }
                }
            });
        });


        $(function () {
            $('#add_category').click(function () {
                var category = $('#new_category').val();
                if (category == '') {
                    alert('Please a category name to add!');
                    return;
                }

                $('#add_category').prop("disabled", true);
                $.ajax({
                    type: 'post',
                    url: 'categories.php',
                    data: {
                        type: "add",
                        category: category
                    },
                    success: function (response) {
                        var previous = document.getElementById("categories_table").innerHTML;
                        if (~previous.indexOf("<td>No categories found.</td>")) previous = "";
                        document.getElementById("categories_table").innerHTML = previous + response;
                        $('#add_category').prop("disabled", false);
                        $('#new_category').val("");
                    }
                });
            });
        });
    </script>

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
            <tbody id="categories_table">
            <?php
            $cat_select = "SELECT * FROM categories;";
            $categories = $conn->query($cat_select);
            $i = 0;
            while ($row_cat = mysqli_fetch_array($categories)) {
                $i++;
                $cat_id = $row_cat['cat_id'];
                $category = $row_cat['category']; ?>

                <tr id="row<?= $cat_id ?>">
                    <td><?= $cat_id ?></td>
                    <td><?= $category ?></td>
                    <td class="text-right">
                        <button value="<?= $cat_id ?>" class="btn btn-danger delete-btn">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <?php
            }
            ?><?php if ($i == 0) {
                echo "<td>No categories found.</td>";
            } ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-12">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control input-lg" id="new_category"
                           placeholder="New Category Name">
                    <span class="input-group-btn">
                            <button id="add_category" class="btn btn-default btn-lg" type="submit"><i class="fa fa-plus"
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