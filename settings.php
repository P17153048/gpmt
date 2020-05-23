<?php
session_start ();
require_once 'modules/user.php';

$user = $_SESSION[ "user" ];
$error = null;
$error_type = null;

if (isset( $_POST[ 'submit' ] )) {
    $id = $user[ 'id' ];
    $f_name = $_POST[ 'f_name' ];
    $l_name = $_POST[ 'l_name' ];
    $password = $_POST[ 'pass' ];
    $current_password = $_POST[ 'pass2' ];
    $email = $user[ 'email' ];

    if (login ( $user[ 'email' ], $current_password ) == 2) {
        $error = 'Current password is not correct';
        $error_type = 'danger';
    }else{
        $updated = update ( $id, $email, $password, $f_name, $l_name );
        $user = $_SESSION[ "user" ];
        if ($updated) {
            $error = 'Updated user details successfully';
            $error_type = 'success';
        } else {
            $error = 'Unable to update user details';
            $error_type = 'danger';
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

    <title>Index</title>
    <style>
        main {
            padding-top: 80px;
        }

    </style>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">GPMT</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="projects.php">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="calendar.php">Calendar</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false"><?php echo $user[ "email" ] ?>></a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="settings.php">Settings</a>
                        <a class="dropdown-item" href="messages.php">Messages <span class="badge badge-danger">4</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h4>User Settings - <?php echo $user[ 'username' ]; ?></h4>
            <?php
            if ($error != null) {
                echo '<div class="alert alert-' . $error_type . '" role="alert">' . $error . '</div>';
            }
            ?>
            <form class="row" name="update_profile" method="post" action="settings.php">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-fn">First Name</label>
                        <input class="form-control" type="text" name="f_name" id="account-fn"
                               value="<?php echo $user[ 'f_name' ] ?>" placeholder="First Name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-ln">Last Name</label>
                        <input class="form-control" type="text" name="l_name" id="account-ln"
                               value="<?php echo $user[ 'l_name' ] ?>" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-email">E-mail Address</label>
                        <input class="form-control" type="email" name="email" id="account-email"
                               value="<?php echo $user[ 'email' ] ?>" placeholder="E-mail Address" disabled>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-confirm-pass">Current Password</label>
                        <input class="form-control" type="password" name="pass2" placeholder="Current Password"
                               id="account-confirm-pass" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-pass">New Password</label>
                        <input class="form-control" type="password" name="pass" placeholder="New Password"
                               id="account-pass" required>
                    </div>
                </div>

                <div class="col-12">
                    <hr class="mt-2 mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <button class="btn btn-style-1 btn-primary" type="submit" name="submit">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>


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
