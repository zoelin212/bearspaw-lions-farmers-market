<?php
ob_start();
include 'defines.php';
include 'functions.php';
$connection = mysqli_connect($sever, $user, $pass, $database);

//checkLogin($connection);


$userid = checkLogin($connection);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bearspaw Farmers Market | Admin</title>
</head>

<body>

</body>

</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bearspaw Farmers Market | Admin</title>
    <link rel="stylesheet" href="../admin/style.css">
    <script src="https://kit.fontawesome.com/e2110d24a9.js" crossorigin="anonymous"></script>
</head>

<?php if (!empty($_GET)):?>
<body >
<?php else:?>
<body class="home">
<?php endif;?>
<header>
    <nav>
        <h1>Dashboard</h1>
        <a href="login.php?logout=true" class="btn btn-primary btn-md">Logout</a>
    </nav>
</header>

    <label for="toggle" class="btn btn-md text-btn">
        <i class="fa-solid fa-bars"></i>
    </label>
    <input type="checkbox" name="toggle" id="toggle">
    <?php if(!empty($_GET)):?>
    <aside>
        <h3>Panels</h3>
        <ul>
            <li><a href="../admin/index.php"><i class="fa-solid fa-house"></i>Home</a></li>
            <li><a href="../admin/index.php?dashboard=landing"><i class="fa-regular fa-message"></i>Landing</a></li>
            <li><a href="../admin/index.php?dashboard=about"><i class="fa-regular fa-message"></i>About</a></li>
            <li><a href="../admin/index.php?dashboard=list-announcement"><i class="fa-solid fa-bullhorn"></i>Anouncements</a></li>
            <li><a href="../admin/index.php?dashboard=photo"><i class="fa-solid fa-image"></i>Photos</a></li>
            <li><a href="../admin/index.php?dashboard=settings"><i class="fa-solid fa-gear"></i>Settings</a></li>
            <li><a href="../admin/index.php?dashboard=users"><i class="fa-solid fa-users"></i>Users</a></li>
            <li><a href="../admin/index.php?dashboard=uploadvendor"><i class="fa-solid fa-shop"></i>Upload Vendor</a></li>
            <li><a href="../admin/index.php?dashboard=marketschedule" class="group"><i class="fa-solid fa-shop"></i>Setup Schedule</a></li>

        </ul>
    </aside>
    <?php endif;?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['dashboard'])) {
        $dashboard = $_GET['dashboard'];
        echo "<main id=" . $dashboard . ">";
        // echo "$dashboard.php";
        include $dashboard . ".php";
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['dashboard'] == 'settings') {
        $dashboard = $_GET['dashboard'];
        echo "<main id=" . $dashboard . ">";
        
        // echo "settings.php";
        include "settings.php";
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['dashboard'])) {
        $dashboard = $_GET['dashboard'];
        echo "<main id=" . $dashboard . ">";
        // echo "$dashboard.php";
        include "$dashboard.php";
    } else {
        $dashboard = 'dashboard';
        echo "<main id=" . $dashboard . ">";
        // echo "else $dashboard";
        include $dashboard . ".php";
    }
    ?>
        </main>
        <footer><small>Bearspaw Farmers Market | Admin</small></footer>
        </body>
        <!-- <script src="script.js"></script> -->

</html>
<?php ob_end_flush(); ?>