<?php
require_once 'modules/user.php';
require_once 'modules/messages.php';

$user = $_SESSION['user'];
$users = get_all_users ();
$error = null;
$error_type = null;

if(isset($_POST['send_message'])){
    $sent_id = $user['id'];
    $deliver_id = $_POST['user_email'];
    $title = $_POST['title'];
    $message = $_POST['message'];
    $status = 0;
    $date = strtotime (date("d-m-Y"));

    $message_sent = send_message ($sent_id, $deliver_id, $title, $message, $status, $date);
    $error = $message_sent ? 'Message sent successfully!' : 'Unable to send message. Try again!';
    $error_type = $message_sent ? 'success' : 'danger';
}
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

    <title>Messages</title>
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
                        <a class="dropdown-item active" href="messages.php">Messages <?php echo $unread_messages > 0 ? '<span class="badge badge-danger">' . $unread_messages . '</span>' : ''; ?></a>
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
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target=".bd-example-modal-xl">New message</button>
                <hr>
                <?php
                if ($error != null) {
                    echo '<div class="alert alert-' . $error_type . '" role="alert">' . $error . '</div>';
                }
                ?>
            </div>
            <div class="col-12">
                <table class="table table-bordered">
                </table>
            </div>

    </div>
</main>
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="message-modal">
    <div class="modal-dialog modal-xl" role="document">
        <form class="modal-content" name="send_message_form" method="POST" action="messages.php">
            <div class="modal-header">
                <h5 class="modal-title">Send message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none" id="error-msg"></div>
                <div class="alert alert-success" style="display:none" id="success-msg"></div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Send message to</label>
                    <select class="form-control" name="user_email" id="exampleFormControlSelect1" required>
                        <?php
                        foreach ($users as $u){
                            if($u['id'] != $user['id']){
                                echo '<option value="' . $u['id'] . '">' . $u['f_name'] . ' ' . $u['l_name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="project_title">Title</label>
                    <input type="text" name="title" value=""class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="project_desc">Message</label>
                    <textarea class="form-control" name="message" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" id="send-message" name="send_message" class="btn btn-success" value="Send message">
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
    $(document).ready(function() {
        // $('#send-message').click(function() {
        //     $('#error-msg').hide().empty();
        //     $('#success-msg').hide().empty();
        //
        //     var postForm = {
        //         'email': $('input[name=email]').val(),
        //         'message': $('textarea[name=message]').val(),
        //         'title': $('input[name=title]').val()
        //     };
        //
        //     $.ajax({
        //         type: 'POST',
        //         url: 'ajaxSendMessage.php',
        //         data: postForm,
        //         dataType: 'json',
        //         success: function(data) {
        //             if (!data.success) {
        //                 $.each(data.errors, function(key, value) {
        //                     $('#error-msg').fadeIn(500).append('<div>' + value + '</div>');
        //                 });
        //
        //             } else {
        //                 $('#success-msg').fadeIn(500).append('<div>' + data.posted + '</div>');
        //             }
        //         }
        //     });
        //     event.preventDefault();
        // });
    });

</script>
</body>

</html>