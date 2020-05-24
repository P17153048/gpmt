<?php
session_start ();
require_once 'modules/projects.php';
require_once 'modules/tasks.php';
require_once 'modules/user.php';

$user = $_SESSION[ "user" ];
$users = get_all_users ();
$project = null;
$tasks = null;
$error = null;
$error_type = null;

if (isset( $_GET[ 'id' ] )) {
    $project_id = $_GET[ 'id' ];
    $project = get_project ( $project_id );

    if(isset($_POST['assign_to'])){
        $task_description = $_POST['description'];
        $task_assigned_to = $_POST['assign_to'];
        $task_deadline = strtotime($_POST['deadline']);

        $added_task = add_task ($project_id, $task_assigned_to, $task_description, $task_deadline, 0);
        $error = $added_task ? 'Task created successfully!' : 'Unable to create task. Try again!';
        $error_type = $added_task ? 'success' : 'danger';
    }
    if (isset( $_GET[ 'action' ] )) {
        $action = $_GET[ 'action' ];
        if ($project[ 'created_by' ] != $user[ 'id' ]) {
            $error = 'You are not the owner of this project. Only project owner can change the status';
            $error_type = 'danger';
        } else {
            if ($action == 'completeproject') {
                $completed = complete_project ( $project_id );
                $error = $completed ? 'Project Completed. Congratulations!' : 'Unable to update project status';
                $error_type = $completed ? 'success' : 'danger';
            } else if ($action == 'deleteproject') {

                $deleted = delete_project ( $project_id );
                $error = $deleted ? 'Project deleted!' : 'Unable to delete project. Try again!';
                $error_type = $deleted ? 'success' : 'danger';
            }else if($action == 'invite_user'){
                $email = $_POST['user_email'];
                $invited = invite_user ($project_id, $email);
                $error = $invited ? 'User has been invited to the project' : 'Unable to invite user to the project';
                $error_type = $invited ? 'success' : 'danger';
            }else if($action == 'edit_project'){
                $title = $_POST['title'];
                $description = $_POST['description'];
                $updated = update_project ($project_id, $title, $description);
                $error = $updated ? 'Project updated successfully' : 'Unable to update project';
                $error_type = $updated ? 'success' : 'danger';
            }else if($action = 'edit_task'){
                $task_id = $_POST['edit_task_id'];
                $task_assigned_to = $_POST['assigned_to'];
                $task_description = $_POST['task_description'];
                $task_deadline = $_POST['task_deadline'];

                $task_updated = update_task ($task_id, $task_assigned_to, $task_description, strtotime ($task_deadline));
                $error = $task_updated ? 'Task updated successfully' : 'Unable to update task';
                $error_type = $task_updated ? 'success' : 'danger';
            }
        }

    }
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
                    <a class="nav-link" href="projects.php">Projects</a>
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
            <h4 class="mb"><?php echo $project != null ? $project[ 'title' ] : ''; ?></h4>
        </div>
        <div class="col-md-8 align-self-center">
            <?php
                if ($error != null) {
                    echo '<div class="alert alert-' . $error_type . '" role="alert">' . $error . '</div>';
                }
            ?>
            <div class="card">
                <div class="card-body">
                    <?php echo $project != null ? $project[ 'description' ] : ''; ?>
                </div>
            </div>

        </div>
        <div class="col-md-4 align-self-center" <?php echo $project != null ? '' : 'style="display:none;"' ?>>
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                   data-target="#create_task">Create new task</a>
                <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                   data-target="#invite_user">Invite user</a>
                <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                   data-target="#edit_project">Edit project</a>
                <?php
                    echo $project['status'] == 0 ? '<a href="project.php?id=' . $project_id . '&action=completeproject"
                   class="list-group-item list-group-item-action">Complete project</a>' : ''
                ?>

                <a href="project.php?id=<?php echo $project_id . '&action=deleteproject'; ?>"
                   class="list-group-item list-group-item-action list-group-item-danger">Delete project</a>
            </div>
        </div>
        <div class="col-12 mt-3" id="tasks">
            <?php
            foreach ($tasks as $task) {
                $badge = $task[ 'status' ] == 1 ? 'success' : 'info';
                echo '<div class="card border-' . $badge . ' mb-2">
                        <div class="card-body">' .
                    ( $task[ 'status' ] == 0 ? '<div class="btn-group float-right">
                                <a href="#" data-toggle="modal" data-target="#edit_task_' . $task['id'] . '" class="btn btn-sm btn-warning float-right">Edit</a>
                                <a href="projects.php?id=' . $project_id . '&completetask=' . $task[ 'id' ] . '" class="btn btn-sm btn-success float-right">Complete</a>
                                <a href="projects.php?id=' . $project_id . '&deletetask=' . $task[ 'id' ] . '" class="btn btn-sm btn-danger float-right">Delete</a>
                         </div>' : '' )
                    . '<p class="m-0">' . $task[ 'description' ] . '</p>
                        </div>
                        <div class="card-footer text-muted">
                            <p class="m-0">Deadline: <span class="badge badge-danger">' . date ( "d-m-Y", $task[ 'deadline_date' ] ) . '</span> <span class="float-right">Assigned to: <a href="#">' . $task[ 'f_name' ] . ' ' . $task[ 'l_name' ] . '</a></span></p>
                            ' . ( $task[ 'status' ] == 1 ? '<p class="m-0">Finished: <span class="badge badge-primary">' . date ( "d-m-Y", $task[ 'complete_date' ] ) . '</span></p>' : '' ) .
                    '</div>
                    </div>';

              echo  '<div class="modal fade" id="edit_task_' . $task['id'] . '">
                        <div class="modal-dialog modal-xl" role="document">
                            <form class="modal-content" method="post" name="edit_task_' . $task['id'] . '" action="project.php?id=' . $project_id . '&action=edit_task">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Task</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="edit_task_assigned_to_' . $task['id'] . '">Assigned to</label>
                                        <select class="form-control" name="assigned_to" id="edit_task_assigned_to_' . $task['id'] . '" required>';
                                            foreach ($users as $u){
                                                echo $u['id'] == $task['user_id'] ? '<option value="' . $u['id'] . '" selected>' . $u['f_name'] . ' ' . $u['l_name'] . '</option>' :  '<option value="' . $u['id'] . '">' . $u['f_name'] . ' ' . $u['l_name'] . '</option>';
                                            }
            echo '</select>
                  </div>
                    <div class="form-group">
                        <label for="task_description_' . $task['id'] . '">Task description</label>
                        <textarea class="form-control" name="task_description" id="task_description_' . $task['id'] . '" rows="3" required>' . $task['description'] . '</textarea>
                    </div>
                    <div class="form-group">
                        <label for="task_deadline_' . $task['id'] . '">Deadline</label>
                        <input type="datetime-local" name="task_deadline" class="form-control" value="' . date('Y-m-d\TH:i', $task['deadline_date']) . '" id="task_deadline_' . $task['id'] . '" required>
                    </div>
                    <input type="hidden" name="edit_task_id" value="' . $task['id'] . '">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" value="Save"></input>
                </div>
                </form>
                </div>
                </div>';
            }
            ?>

        </div>
    </div>
</main>
<div class="modal fade bd-example-modal-xl" id="create_task" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form class="modal-content" name="create-task" method="POST" action="project.php?id=<?php echo $project_id; ?>">
            <div class="modal-header">
                <h5 class="modal-title">New task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Assign to</label>
                    <select class="form-control" name="assign_to" id="exampleFormControlSelect1" required>
                        <?php
                            foreach ($users as $u){
                                echo '<option value="' . $u['id'] . '">' . $u['f_name'] . ' ' . $u['l_name'] . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Description</label>
                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"
                              required></textarea>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">Deadline</label>
                    <input type="datetime-local" name="deadline" class="form-control" id="formGroupExampleInput2" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Add task">
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="invite_user">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" name="invite_user" action="project.php?id=<?php echo $project_id; ?>&action=invite_user">
            <div class="modal-header">
                <h5 class="modal-title">Invite user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none" id="error-msg-invite"></div>
                <div class="alert alert-success" style="display:none" id="success-msg-invite"></div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Send invite to</label>
                    <select class="form-control" name="user_email" id="exampleFormControlSelect1" required>
                        <?php
                        foreach ($users as $u){
                            echo '<option value="' . $u['id'] . '">' . $u['f_name'] . ' ' . $u['l_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" id="invite-user" class="btn btn-success" value="Send invite"></input>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_project">
    <div class="modal-dialog modal-xl" role="document">
        <form class="modal-content" method="post" name="edit_project" action="project.php?id=<?php echo $project_id; ?>&action=edit_project">
            <div class="modal-header">
                <h5 class="modal-title">Edit Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="project_title">Project title</label>
                    <input type="text" name="title" required class="form-control" id="project_title" placeholder="Project title" value="<?php echo $project['title']; ?>">
                </div>
                <div class="form-group">
                    <label for="project_desc">Project description</label>
                    <textarea class="form-control" name="description" id="project_desc" rows="3" required><?php echo $project['description']; ?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Save"></input>
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

