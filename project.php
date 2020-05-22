<?php
session_start ();
require_once 'modules/projects.php';
require_once 'modules/tasks.php';

$user = $_SESSION[ "user" ];
$project = null;
$tasks = null;

if (isset( $_GET[ 'id' ] )) {
    $project_id = $_GET[ 'id' ];
    $project = get_project ( $project_id );
    $tasks = get_tasks_by_project ( $project_id );
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
            <h4 class="mb"><?php echo $project[ 'title' ]; ?></h4>
        </div>
        <div class="col-md-8 align-self-center">
            <div class="card">
                <div class="card-body">
                    <?php echo $project[ 'description' ]; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 align-self-center">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                   data-target=".bd-example-modal-xl">Create new task</a>
                <a href="#" class="list-group-item list-group-item-action">Invite user</a>
                <a href="#" class="list-group-item list-group-item-action">Edit project</a>
                <a href="#" class="list-group-item list-group-item-action">Complete project</a>
                <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Delete project</a>
            </div>
        </div>
        <div class="col-12 mt-3" id="tasks">
            <?php
            foreach ($tasks as $task) {
                $badge = $task['status'] == 1 ? 'success' : 'info';
                echo '<div class="card border-' . $badge . ' mb-2">
                        <div class="card-body">' .
                            ( $task[ 'status' ] == 0 ? '<div class="btn-group float-right">
                                <a href="#" class="btn btn-sm btn-warning float-right">Edit</a>
                                <a href="projects.php?id=' . $project_id . '&completetask=' .$task['id'] . '" class="btn btn-sm btn-success float-right">Complete</a>
                                <a href="projects.php?id=' . $project_id . '&deletetask=' .$task['id'] . '" class="btn btn-sm btn-danger float-right">Delete</a>
                         </div>' : '' )
                            . '<p class="m-0">' . $task[ 'description' ] . '</p>
                        </div>
                        <div class="card-footer text-muted">
                            <p class="m-0">Deadline: <span class="badge badge-danger">' . date ( "d-m-Y", $task[ 'deadline_date' ] ) . '</span> <span class="float-right">Assigned to: <a href="#">' . $task['f_name'] . ' ' . $task['l_name'] . '</a></span></p>
                            ' . ($task['status'] == 1 ? '<p class="m-0">Finished: <span class="badge badge-primary">' . date ( "d-m-Y", $task[ 'complete_date' ] ) . '</span></p>' : '') .
                        '</div>
                    </div>';
                    }
            ?>
            <!--            <div class="card border-danger mb-2">-->
            <!--                <div class="card-body">-->
            <!--                    <div class="btn-group float-right">-->
            <!--                        <a href="#" class="btn btn-sm btn-warning float-right">Edit</a>-->
            <!--                        <a href="#" class="btn btn-sm btn-success float-right">Complete</a>-->
            <!--                        <a href="#" class="btn btn-sm btn-danger float-right">Delete</a>-->
            <!--                    </div>-->
            <!---->
            <!--                    <p class="m-0">Short description about the task needed to be done</p>-->
            <!--                </div>-->
            <!--                <div class="card-footer text-muted">-->
            <!--                    <p class="m-0">Deadline: <span class="badge badge-danger">2020-01-07 15:00</span> <span class="float-right">Assigned to: <a href="#">Thomas John</a></span></p>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="card mb-2">-->
            <!--                <div class="card-body">-->
            <!--                    <div class="btn-group float-right">-->
            <!--                        <a href="#" class="btn btn-sm btn-warning float-right">Edit</a>-->
            <!--                        <a href="#" class="btn btn-sm btn-success float-right">Complete</a>-->
            <!--                        <a href="#" class="btn btn-sm btn-danger float-right">Delete</a>-->
            <!--                    </div>-->
            <!---->
            <!--                    <p class="m-0">Short description about the task needed to be done</p>-->
            <!--                </div>-->
            <!--                <div class="card-footer text-muted">-->
            <!--                    <p class="m-0">Deadline: <span class="badge badge-danger">2020-01-06 15:00</span> <span class="float-right">Assigned to: <a href="#">Thomas John</a></span></p>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="card mb-2">-->
            <!--                <div class="card-body">-->
            <!--                    <div class="btn-group float-right">-->
            <!--                        <a href="#" class="btn btn-sm btn-warning float-right">Edit</a>-->
            <!--                        <a href="#" class="btn btn-sm btn-success float-right">Complete</a>-->
            <!--                        <a href="#" class="btn btn-sm btn-danger float-right">Delete</a>-->
            <!--                    </div>-->
            <!---->
            <!--                    <p class="m-0">Short description about the task needed to be done</p>-->
            <!--                </div>-->
            <!--                <div class="card-footer text-muted">-->
            <!--                    <p class="m-0">Deadline: <span class="badge badge-warning">2020-01-05 15:00</span> <span class="float-right">Assigned to: <a href="#">Thomas John</a></span></p>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="card border-success text-success mb-2">-->
            <!--                <div class="card-body">-->
            <!--                    <p class="m-0">Short description about the task needed to be done</p>-->
            <!--                </div>-->
            <!--                <div class="card-footer text-muted">-->
            <!--                    <p class="m-0">-->
            <!--                        Deadline: <span class="badge badge-danger">2020-01-06 15:00</span>-->
            <!--                        <span class="float-right">Assigned to: <a href="#">Thomas John</a></span>-->
            <!--                    </p>-->
            <!--                    <p class="m-0">Finished: <span class="badge badge-primary">2020-01-06 19:00</span></p>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
    </div>
</main>
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Assign to</label>
                    <select class="form-control" id="exampleFormControlSelect1">
                        <option value="Thomas John">Thomas John</option>
                        <option value="John Smith">John Smith</option>
                        <option value="Morgan Freeman">Morgan Freeman</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Description</label>
                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"
                              required></textarea>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">Deadline</label>
                    <input type="datetime-local" name="deadline" class="form-control" id="formGroupExampleInput2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" type="submit" class="btn btn-success" onclick="addTask()">Add task</button>
            </div>
        </form>
    </div>
</div>
<script>
    function addTask() {
        $('.bd-example-modal-xl').modal('hide');
        var assign = document.getElementById("exampleFormControlSelect1").value;
        var deadline = document.getElementById("formGroupExampleInput2").value;
        var desc = document.getElementById("exampleFormControlTextarea1").value;
        var tmpStr = document.getElementById("tasks").innerHTML;
        tmpStr = '<div class="card mb-2"><div class="card-body"><div class="btn-group float-right"><a href="#" class="btn btn-sm btn-warning float-right">Edit</a><a href="#" class="btn btn-sm btn-success float-right">Complete</a><a href="#" class="btn btn-sm btn-danger float-right">Delete</a></div><p class="m-0">' + desc + '</p></div><div class="card-footer text-muted"><p class="m-0">Deadline: <span class="badge badge-warning">' + deadline + '</span> <span class="float-right">Assigned to: <a href="#">' + assign + '</a></span></p></div></div>' + tmpStr;
        document.getElementById("tasks").innerHTML = tmpStr;

    }
</script>
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

