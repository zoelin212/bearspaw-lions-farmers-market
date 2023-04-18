<?php
// login funcitons
function checkLogin($connection)
{
    if (isset($_COOKIE['login'])) {
        $user = $_COOKIE['login'];
        $query = "SELECT `id`, `username`, `password`, `privilege`,`status` FROM `users` WHERE `username` = '$user'";
        $sql = mysqli_query($connection, $query);
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        // echo gettype($row['id']);
        if ($sql == false) {
            echo $user . '<br>';
            header("Location: login.php");
            die();
        }
    } else {
        header("Location: login.php");
        die();
    }
    return $row['id'];
}

setlocale(LC_ALL, 'en_US.UTF8');
function toAscii($str, $replace = array(), $delimiter = '-')
{
    if (!empty($replace)) {
        $str = str_replace((array)$replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return $clean;
}

// generate garbles
function generateGarbledString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $garbledString = '';
    for ($i = 0; $i < $length; $i++) {
        $garbledString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $garbledString;
}

?>