<?php
include_once('header.php');
$data = array();
if (isset($_POST['image_upload']) && !empty($_FILES['images'])) : {

    $image = $_FILES['images'];
    $allowedExts = array("gif", "jpeg", "jpg", "png");

//create directory if not exists
    if (!file_exists('images')) {
        mkdir('images', 0777, true);
    }
    $image_name = $image['name'];
//get image extension
    $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
//assign unique name to image
    $name = time() . '.' . $ext;
//$name = $image_name;
//image size calcuation in KB
    $image_size = $image["size"] / 1024;
    $image_flag = true;
//max image size
    $max_size = 512;
    if (in_array($ext, $allowedExts) && $image_size < $max_size) {
        $image_flag = true;
    } else {
        $image_flag = false;
        $data['error'] = 'Maybe ' . $image_name . ' exceeds max ' . $max_size . ' KB size or incorrect file extension';
    }

    if ($image["error"] > 0) {
        $image_flag = false;
        $data['error'] = '';
        $data['error'] .= '<br/> ' . $image_name . ' Image contains error - Error Code : ' . $image["error"];
    }

    if ($image_flag) {
        move_uploaded_file($image["tmp_name"], "images/" . $name);
        $src = "images/" . $name;
        //$dist = "images/thumbnail_" . $name;
        //$data['success'] = $thumbnail = 'thumbnail_' . $name;
        //thumbnail($src, $dist, 200);

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        $sql = "INSERT INTO images (`uid`, `title`, `description`, `original_image`) VALUES ('" . $_SESSION['userid'] . "', '" . $title ."', '" . $description . "', '$name');";
        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
    mysqli_close($conn);
    //echo json_encode($data);
    header('Location:view.php?id='. $last_id);

} else: ?>
    <script src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.form.min.js"></script>

<!--    <form enctype="multipart/form-data" name='imageform' role="form" id="imageform" method="post" action="upload.php">-->
<!--        <div class="form-group">-->
<!--            <p> Please Choose Image: </p>-->
<!--            <input class='file' type="file" class="form-control" name="images" id="images"-->
<!--                   placeholder="Please choose your image">-->
<!--            <span class="help-block"></span>-->
<!--        </div>-->
<!--        <div id="loader" style="display: none;">-->
<!--            Please wait image uploading to server....-->
<!--        </div>-->
<!--        <input type="submit" value="Upload" name="image_upload" id="image_upload" class="btn"/>-->
<!--    </form>-->

    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        #img-upload{
            width: 100%;
        }
    </style>

    <script>
        $(document).ready( function() {
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }

            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function(){
                readURL(this);
            });
        });
    </script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-5 col-md-offset-3">
            <form enctype="multipart/form-data" name='imageform' role="form" id="imageform" method="post" action="upload.php">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required="true">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" rows="5" id="description" name="description" required="true"></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Image</label>
                    <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-default btn-file">
                        Browse… <input class='file' name="images" type="file" id="imgInp" required="true">
                    </span>
                </span>
                        <input type="text" class="form-control" readonly placeholder="Please choose your image">
                    </div>
                    <img id='img-upload'/>
                </div>
                <input type="submit" value="Upload" name="image_upload" id="image_upload" class="btn"/>
            </form>
        </div>
    </div>
</div>
<?php endif ?>