<?php
require_once 'modules/user.php';

$error = null;
$error_type = null;

if (isset( $_POST[ 'inputFName' ] )) {
    $f_name = $_POST[ 'inputFName' ];
    $l_name = $_POST[ 'inputLName' ];
    $username = $_POST[ 'inputUsername' ];
    $password = $_POST[ 'inputPassword' ];
    $confirm_password = $_POST[ 'inputPassword2' ];
    $email = $_POST[ 'inputEmail' ];
    $permission = 0;

    if ($password != $confirm_password) {
        $error = 'Passwords do not match. Please rectify and try again';
        $error_type = 'error';
    }else{
        $created = create ( $username, $email, $f_name, $l_name, $password, $permission );
        if ($created) {
            $error = 'Registration success! You can <a href="login.php">login</a> now';
            $error_type = 'success';
        } else {
            $error = 'Email address exists. Please register with a different email address';
            $error_type = 'error';
        }
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

    <link rel="stylesheet" href="css/style.css">
    <title>Register</title>
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
                echo '<div class="alert alert-' . ( $error_type == 'error' ? 'danger' : 'success' ) . '" role="alert">' . $error . '</div>';
            }
            ?>
            <form class="form-signin text-center" name="register" method="POST" action="register.php">

                <h1 class="h3 mb-3 font-weight-normal">Register</h1>
                <label for="inputFName" class="sr-only">First Name</label>
                <input type="text" id="inputFName" name="inputFName" class="form-control mb-3" placeholder="First Name" required=""
                       autofocus="">
                <label for="inputLName" class="sr-only">Last Name</label>
                <input type="text" id="inputLName" name="inputLName" class="form-control mb-3" placeholder="Last Name" required="">
                <label for="inputUsername" class="sr-only">Username</label>
                <input type="text" id="inputUsername" name="inputUsername" class="form-control mb-3" placeholder="Username" required="">
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" name="inputEmail" class="form-control mb-3" placeholder="Email address" required="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" name="inputPassword" class="form-control mb-3" placeholder="Password" required="">
                <label for="inputPassword2" class="sr-only">Repeat Password</label>
                <input type="password" id="inputPassword2" name="inputPassword2" class="form-control mb-3" placeholder="Confirm Password"
                       required="">
                <div class="custom-control custom-switch float-left mb-2">
                    <input type="checkbox" class="custom-control-input" id="remember-me" name="remember-me">
                    <label class="custom-control-label" for="remember-me">Remember me</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
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

