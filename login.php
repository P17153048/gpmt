<?php
require_once 'data/database.php';
require_once 'modules/user.php';

$database = new DB();
$error = null;
$valid = null;

if (isset( $_POST[ "inputEmail" ] )) {
    $username = $_POST[ "inputEmail" ];
    $password = $_POST[ "inputPassword" ];

    $valid = login ( $username, $password );
    switch ($valid) {
        case 1:
            $error = "Successfully logged in! Please wait while we initialize the application for you...";
            header('refresh:4; url=index.php');
            break;
        case 0:
            $error = "User not found";
            break;
        case 2:
            $error = "Incorrect credentials";
            break;
    }
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Login</title>
    <style>
        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 80px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

    </style>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.html">GPMT</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">
                <a href="login.php" class="btn btn-sm btn-outline-info my-2 mr-2 my-sm-0">Login</a>
                <a href="register.php" class="btn btn-sm btn-outline-primary my-2 my-sm-0">Sign Up</a>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-11 p-5 login-form border-info">
            <?php
            if ($error != null) {
                if ($valid == 0) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }
                if ($valid == 1) {
                    echo '<div class="alert alert-success" role="alert">' . $error . '</div>';
                }
                if ($valid == 2) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }
            }
            ?>
            <form class="form-signin text-center" name="login" action="login.php" method="POST">
                <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" name="inputEmail" class="form-control mb-3"
                       placeholder="Email address" required=""
                       autofocus="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" name="inputPassword" class="form-control mb-3"
                       placeholder="Password" required="">

                <div class="custom-control custom-switch float-left mb-2">
                    <input type="checkbox" class="custom-control-input" id="remember-me" name="remember-me" checked>
                    <label class="custom-control-label" for="remember-me">Remember me</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            </form>

        </div>
    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>

</html>
