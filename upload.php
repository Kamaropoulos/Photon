<?php

function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

if (isset($_POST) and isset($_POST['title']) and isset($_POST['description']) and $_SERVER['REQUEST_METHOD'] == "POST") : {

    ob_start();
    include_once('header.php');
    ob_end_clean();

    $path = "images/"; //set your folder path
    //set the valid file extensions
    $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "GIF", "JPG", "PNG"); //add the formats you want to upload

    $name = $_FILES['myfile']['name']; //get the name of the file

    $size = $_FILES['myfile']['size']; //get the size of the file

    if (strlen($name)) { //check if the file is selected or cancelled after pressing the browse button.
        list($txt, $ext) = explode(".", $name); //extract the name and extension of the file
        if (in_array($ext, $valid_formats)) { //if the file is valid go on.
            if ($size < 209888800) { // check if the file size is more than 2 mb
                while (true) {
                    $file_name = uniqid('img_', true);
                    $file_name = str_replace('.', '',$file_name);
                    if (!file_exists("images/" . $file_name)) break;
                }
                $tmp = $_FILES['myfile']['tmp_name'];
                //if (move_uploaded_file($tmp, $path . $file_name.'.'.$ext)) { //check if it the file move successfully.
                if (compress($tmp, $path . $file_name.'.'.$ext, 90)){
                    //echo "File uploaded successfully!!";
                    $title = mysqli_real_escape_string($conn, $_POST['title']);
                    $description = mysqli_real_escape_string($conn, $_POST['description']);

                    $sql = "INSERT INTO images (`uid`, `title`, `description`, `original_image`) VALUES ('" . $_SESSION['userid'] . "', '" . $title ."', '" . $description . "', '$file_name.$ext');";
                    if ($conn->query($sql) === TRUE) {
                        $last_id = $conn->insert_id;
                        echo $last_id;
                    } else {
                        echo -1; //MySQL Error
                    }
                } else {
                    //echo "failed";
                    echo -2; //File naming/moving failed
                }
            } else {
                //echo "File size max 2 MB";
                echo -3; //File too large
            }
        } else {
            //echo "Invalid file format..";
            echo -4; //Invalid file format
        }
    } else {
        //echo "Please select a file..!";
        echo -5; //No file selected
    }
    mysqli_close($conn);

    exit;
} else :
    include_once('header.php'); ?>
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

            $("#myfile").change(function(){
                readURL(this);
            });
        });

        $(function () {
            $('#btn').click(function () {
                $(".progress").show();
                $('.myprogress').css('width', '0');
                $('.msg').text('');
                var title = $('#title').val();
                var description = $('#description').val();
                var myfile = $('#myfile').val();
                if (title == '' || description == '' || myfile == '') {
                    alert('Please enter file name and select file');
                    return;
                }
                var formData = new FormData();
                formData.append('myfile', $('#myfile')[0].files[0]);
                formData.append('title', title);
                formData.append('description', description);
                $('#btn').attr('disabled', 'disabled');
                $('.msg').text('Uploading in progress...');
                $.ajax({
                    url: 'upload2.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    // this part is progress bar
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $('.myprogress').text(percentComplete + '%');
                                $('.myprogress').css('width', percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (data) {
                        //$('.msg').text(data);
                        $('#btn').removeAttr('disabled');
                        if (data > 0) window.location.href = "view.php?" + data;
                        else {
                            switch(data){
                                case -1:
                                    $('.msg').text("An error occured while saving your post.");
                                    break;
                                case -2:
                                    $('.msg').text("An error occured while saving your post.");
                                    break;
                                case -3:
                                    $('.msg').text("The image is too large.");
                                    break;
                                case -4:
                                    $('.msg').text("Invalid file format.");
                                    break;
                                case -5:
                                    $('.msg').text("No file selected.");
                                    break;
                                default:
                                    $('.msg').text("Undefined error.");
                            }
                        }
                    }
                });
            });
        });
    </script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-md-offset-3">
                <form id="myform" enctype="multipart/form-data" name='imageform' role="form" id="imageform" method="post">
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
                        Browse… <input class='file' name="images" type="file" id="myfile" required="true">
                    </span>
                </span>
                            <input type="text" class="form-control" readonly placeholder="Please choose your image">
                        </div>
                        <img id='img-upload'/>
                    </div>

                    <div class="form-group">
                        <div class="progress" style="display:none">
                            <div class="progress-bar progress-bar-success myprogress" id="pb1" role="progressbar" style="width:0%">0%</div>
                        </div>

                        <div class="msg"></div>
                    </div>

                    <input type="button" value="Upload" name="image_upload" id="btn" class=" btn btn-default"/>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>