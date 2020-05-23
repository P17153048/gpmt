<?php
session_start ();
require_once 'modules/projects.php';
require_once 'modules/tasks.php';

$user = $_SESSION[ "user" ];
$projects = get_projects ();

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
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="project.php">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="calendar.php">Calendar</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false"><?php echo $user[ 'email' ]; ?></a>
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
        <div class="col-12">
            <h4 class="mb">Projects</h4>
        </div>
        <?php
        foreach($projects as $project){
            echo '<div class="col-12">
                    <div class="card border-success mb-2">
                        <h5 class="card-header">
                            <a href="project.php?id=' . $project['id'] . '" class="stretched-link">' . $project['title'] . '</a>
                        </h5>
                        <div class="card-body">
                            <p class="m-0">' . $project['description'] . '</p>
                        </div>
                        <div class="card-footer text-muted">
            
                            <p class="m-0">Created on: <span class="badge badge-success">' . date ( "d-m-Y", $project[ 'date_created' ] ) . '</span><span class="float-right">Owner: <a href="#">' . $project['f_name'] . ' ' . $project['l_name'] . '</a></span></p>
                        </div>
                     </div>
                   </div>';
        }
        ?>




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

