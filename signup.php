<?php

if ($_POST): {

    include_once('connection.php');
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_pass = hash('sha512', $password);

    echo $username;
    echo $password;

    $sql = "SELECT uid, username FROM users WHERE username='$username'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<center>Username Already in use! <a href='signup.php'>Go back</a></center>";
        exit();
    } else {
        $sql = "INSERT INTO users (username, pass_hash) VALUES('$username','$hashed_pass')";
        $query = mysqli_query($conn, $sql);

        header('Location:login.php');
    }
} else: ?>
    <?php
    include_once('header.php');
    ?>

    <div class="container">
        <div class="container">
            <div class="row vertical-offset-100">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sign Up</h3>
                        </div>
                        <div class="panel-body">
                            <form action="signup.php" method="post" accept-charset="UTF-8" role="form">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="username" type="text">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="password"
                                               type="password" value="">
                                    </div>
                                    <input class="btn btn-lg btn-success btn-block" type="submit" value="Signup">
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php endif ?>