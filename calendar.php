<?php
session_start();
require_once './modules/tasks.php';
require_once 'modules/messages.php';

$user = $_SESSION['user'];
$date = null;
$error = null;
$error_type = null;

if (isset($_POST['date'])) {
    $date = $_POST['date'];
} else {
    $date = date("d-m-Y");
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
    if($action == 'complete_task'){
        $task_id = $_GET['task_id'];
        $task_completed = complete_task ($task_id);
        $error = $task_completed ? 'Task completed successfully' : 'Unable to update task';
        $error_type = $task_completed ? 'success' : 'danger';
    }
}

$tasks = get_tasks_by_date ($user['id'], $date);
$unread_messages = get_unread_message_count ($user['id']);

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
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="projects.php">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="calendar.php">Calendar</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $user['email']; ?>
                    </a>
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
        <div class="col-12 mb-2">
            <?php
            if ($error != null) {
                echo '<div class="alert alert-' . $error_type . '" role="alert">' . $error . '</div>';
            }
            ?>

            <form class="form-inline" id="form" method="POST" action="calendar.php">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <a href="?date=<?=date('d-m-Y', strtotime('-1 day 00:00'))?>" class="btn btn-primary btn-sm">Yesterday</a>
                        <a href="?date=<?=date('d-m-Y', strtotime('00:00'))?>" class="btn btn-primary btn-sm">Today</a>
                        <a href="?date=<?=date('d-m-Y', strtotime('+1 day 00:00'))?>" class="btn btn-primary btn-sm">Tomorrow</a>
                    </div>
                    <input type="date" id="date" class="form-control form-control-sm" value="<? echo date('Y-m-d',$date) ?>" >
                    <input type="hidden" name="date" id="post_date" value="">
                </div>
            </form>
        </div>
        <?php
        echo '<div class="col-12"><h3>' . date("d-m-Y", strtotime($date))  . '</h3></div>';
        ?>

        <div class="col-12 mt-3" id="tasks">
            <?php
            foreach ($tasks as $task) {
                $badge = $task[ 'status' ] == 1 ? 'success' : 'info';
                echo '<div class="card border-' . $badge . ' mb-2">
                        <div class="card-body">' .
                    ( $task[ 'status' ] == 0 ? '<div class="btn-group float-right">
                                <a href="calendar.php?action=complete_task&task_id=' . $task[ 'id' ] . '" class="btn btn-sm btn-success float-right">Complete</a>
                                </div>' : '' )
                    . '<p class="m-0">' . $task[ 'description' ] . '</p>
                        </div>
                        
                    </div>';


            }
            ?>

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


<script>
    $(document).ready(function(){
        $('#date').change(function(){
            debugger;
            let selectedDate = $("#date").val();
            let postDate = selectedDate.split('-').reverse().join('-');
            $('#post_date').val(postDate);
            $('#form').submit();
        });
    });
</script>
</body>

</html>
