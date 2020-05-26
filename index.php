<?php
require_once 'modules/projects.php';
require_once 'modules/tasks.php';
require_once 'modules/messages.php';

$user = $_SESSION[ "user" ];


if(isset($_POST['title'])){
    $project_title = $_POST['title'];
    $project_description = $_POST['description'];
    $date_created = strtotime($_POST['deadline']);
    $created_by = $user['id'];
    $status = 0;
    $project_created = create_project ($project_title, $project_description, $date_created, $created_by, $status);
    if($project_created){
        $error = 'Creating project. Please wait..';
        $error_type = 'success';
    }else{
        $error = 'Unable to create project. Please try again';
        $error_type = 'danger';
    }
}

$projects = get_projects ();
$tasks = get_tasks ( $user[ 'id' ] );
$error = null;
$error_type = null;
$unread_messages = get_unread_message_count ($user['id']);

function get_badge($task)
{
    if (date ( "d-m-y", $task[ 'deadline_date' ] ) > date ( "d-m-y" )) {
        return 'danger';
    } else if (date ( "d-m-y", $task[ 'deadline_date' ] ) == date ( "d-m-y" )) {
        return 'warning';
    } else if (date ( "d-m-y", $task[ 'deadline_date' ] ) < date ( "d-m-y" )) {
        return 'success';
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
                        <a class="dropdown-item" href="messages.php">Messages <?php echo $unread_messages > 0 ? '<span class="badge badge-danger">' . $unread_messages . '</span>' : ''; ?></a>
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
        <div class="col-lg-8 col-md-6">
            <h4>Tasks assigned to me</h4>
            <?php
            foreach ($tasks as $task) {
                echo '<div class="card border-' . get_badge ( $task ) . ' mb-2 shadow rounded-0"">
                            <h5 class="card-header">
                                <a href="project.php?id=' . $task[ 'project_id' ] . '" class="stretched-link">' . $task[ 'project_title' ] . '</a>
                            </h5>
                            <div class="card-body">
                                <p class="m-0">' . $task[ 'description' ] . '</p>
                            </div>
                            <div class="card-footer text-muted">
                                <p class="m-0">Deadline: <span class="badge badge-' . get_badge ( $task ) . '">' . date ( "d-m-Y", $task[ 'deadline_date' ] ) . '</span></p>    
                                ' . ( $task[ 'status' ] == 1 ? '<p class="m-0">Finished: <span class="badge badge-primary">' . date ( "d-m-Y", $task[ 'complete_date' ] ) . '</span></p>' : '' )
                    . '</div>
                        </div>';
            }
            ?>
        </div>
        <div class="col-lg-4 col-md-6">
            <h4>Projects
                <button type="button" data-toggle="modal" data-target=".bd-example-modal-xl"
                        class="btn btn-primary btn-sm float-right">Create new project
                </button>
            </h4>

            <div class="list-group" id="project-list">
                <?php
                foreach ($projects as $project) {
                    echo '<a href="project.php?id=' . $project[ 'id' ] . '" class="list-group-item list-group-item-action flex-column shadow align-items-start ' . ($project['created_by'] == $user['id'] ? 'active': '') . '">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">' . $project[ 'title' ] . '</h5>
                        <small>' . ( $project[ 'unfinished_tasks' ] > 0 ? '<span class="badge badge-danger float-right" title="Unifinished tasks">' . $project[ 'unfinished_tasks' ] : '' ) . '</span></small>
                    </div>
                    <p class="mb-1">' . $project['description'] . '</p>
                    <small class="float-right">Owner: <strong>' . $project['f_name'] . ' ' . $project['l_name'] . '</strong></small>
                </a>';
                }
                ?>

            </div>
        </div>
    </div>
</main>

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form class="modal-content" name="create_project" action="index.php" method="POST">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">New project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                if ($error != null) {
                       echo '<div class="alert alert-' . $error_type . '" role="alert">' . $error . '</div>';
                }
                ?>
                <div class="form-group">
                    <label for="formGroupExampleInput">Project title</label>
                    <input type="text" name="title" id="project-title" class="form-control" id="formGroupExampleInput"
                           placeholder="Project title" autofocus required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Project description</label>
                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1"  rows="3"
                              required="required"></textarea>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">Project deadline</label>
                    <input type="datetime-local" name="deadline" value="<?php echo date("Y-m-j h:i A")?>" class="form-control" id="formGroupExampleInput2" required="required">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Create Project"/>
            </div>
        </form>
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
<script>
    function addProject() {
        var title = document.getElementById("project-title").value;
        $('.bd-example-modal-xl').modal('hide');
        document.getElementById("project-list").innerHTML += '<a href="project.html" class="list-group-item list-group-item-action">' + title + '</a>';
        document.getElementById("project-title").value = '';
    }
</script>
</body>

</html>
