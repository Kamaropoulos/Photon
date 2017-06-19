<?php

if ($_POST && isset($_POST['type'])) {
    include_once("connection.php");
    $msgType = $_POST['type'];
    switch ($msgType) {
        case "add":
            $category = htmlentities(mysqli_real_escape_string($conn, $_POST['category']));
            $sql_insert = "INSERT INTO categories values(NULL, '$category');";
            if ($conn->query($sql_insert) === TRUE) {
                $id = $conn->insert_id;
            } else {
                echo $conn->error;
                exit();
            }
            $sql_select = "SELECT * FROM categories
                       WHERE cat_id = " . $id . ";";
            $select = $conn->query($sql_select);

            if ($row = mysqli_fetch_array($select)) {
                $cat_id = $row['cat_id'];
                $category = $row['category'];
                echo "<tr id=\"row$cat_id\">
                    <td>$cat_id</td>
                    <td>$category</td>
                    <td class=\"text-right\">
                        <button value=\"$cat_id\" class=\"btn btn-danger delete-btn\">
                            <i class=\"fa fa-trash\"></i>
                        </button>
                    </td>
                </tr>";
            }
            exit();


            break;

        case "delete":
            $id = (int)mysqli_real_escape_string($conn, $_POST['id']);
            $sql_delete = "DELETE FROM categories WHERE cat_id = " . $id . ";";
            if ($conn->query($sql_delete) === TRUE) {
                echo 0;
            }
            break;
    }


} else {
    echo "nopost";
}

?>