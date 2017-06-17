<?php

	if ($_POST):
{
        include_once("connection.php");
        $username = htmlentities(mysqli_real_escape_string($conn, $_POST['username']));
        $password = htmlentities(mysqli_real_escape_string($conn, $_POST['password']));
        $hashed_pass = hash('sha512',$password);

        $sql="SELECT uid, username, pass_hash FROM users WHERE username='$username' AND pass_hash='$hashed_pass'";

        $query=$conn->query($sql);
        if (!$query){
            die ("Database connection failed!");
        }
        $row = mysqli_fetch_row($query);

        $db_id = $row[0];
        $db_username = $row[1];
        $db_password = $row[2];

        if ($hashed_pass != $db_password){
            echo '<center>Incorrect Username or Password! <a href="login.php">Go back</a></center>';
            exit();
        } else {
            session_start();
            $_SESSION['userid'] = $db_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['pass_hash'] = $db_password;
            echo $_SESSION['userid'] = $db_id;
            echo $_SESSION['username'];
            echo $_SESSION['pass_hash'];
            header('Location:index.php');
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
                                <h3 class="panel-title">Please sign in</h3>
                            </div>
                            <div class="panel-body">
                                <form action="login.php" method="post" accept-charset="UTF-8" role="form">
                                    <fieldset>

                                        <div class="input-group margin-bottom-sm">
                                            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                                            <input class="form-control" name="username" type="text" placeholder="Email address" required="true" autofocus>
                                        </div><br>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                                            <input class="form-control" name="password" type="password" placeholder="Password" required="true">
                                        </div><br>
                                        <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php endif ?>