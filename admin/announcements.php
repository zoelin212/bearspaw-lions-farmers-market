<?php
ob_start();
include 'defines.php';
include_once( 'functions.php');
$connection = mysqli_connect($sever, $user, $pass, $database);
// checkLogin($connection);

if (!empty($_POST)) {
    // echo "INSERT INTO announcements (`title`, `content`, `author`, `create_date`, `start_date`, `end_date`, `status`) 
    // VALUES ('{$_POST['title']}', '{$_POST['message']}', '{$_POST['author_id']}', NOW(), '{$_POST['start_date']}', '{$_POST['end_date']}', 0)";
    $result = mysqli_query($connection, "INSERT INTO announcements (`title`, `content`, `author`, `create_date`, `start_date`, `end_date`, `status`) 
VALUES ('{$_POST['title']}', '{$_POST['content']}', '{$_POST['author_id']}', NOW(), '{$_POST['start_date']}', '{$_POST['end_date']}', 0)");
    
    // Check if the insert operation was successful
    if ($result) {
        echo "Announcement created successfully.";
        header("Location: ../admin/index.php?dashboard=list-announcement");    
    
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
<?php ob_end_flush(); ?>