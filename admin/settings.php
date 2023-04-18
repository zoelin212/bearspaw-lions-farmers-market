<h2>Settings</h2>
<?php
$query = "SELECT COUNT(*) FROM `settings`";

$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$arr = ['phone', 'contact1Title', 'contact1email', 'contact2Title','contact2email', 'contact3Title','contact3email', 'address','addressDesc','sns1', 'sns2', 'sns3','startdate','enddate','adminemail'];
if ($row) {
    $var = 'UPDATE';
    // input value prefill
    foreach ($arr as $value) {
        $select_query = "SELECT `option_name`, `option_value` FROM `settings` WHERE `option_name` = '$value'";
        $select_sql = mysqli_query($connection, $select_query);
        $select_row = mysqli_fetch_array($select_sql);
        $$value = $select_row['option_value'];
    }
} else {
    $var = 'INSERT';
    $phone = ''; 
    $contact1Title=''; $contact1email = '';
    $contact2Title=''; $contact2email = '';
    $contact3Title=''; $contact3email = '';
    $address = ''; $addressDesc = '';
    $adminemail = '';
    $sns1 = '';
    $sns2 = '';
    $sns3 = '';
    $startdate = '2023-03-21';
    $enddate='2023-03-25';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'];
    // $arr = ['artist', 'description', 'email', 'adminemail', 'sns1', 'sns2', 'sns3'];
    foreach ($arr as $value) {
        $sanitize = mysqli_real_escape_string($connection, $_POST[$value]);
        if ($action == 'INSERT') {
            $query = "$action INTO settings (option_name, option_value) VALUES ('$value','$sanitize')";
        } else {
            $query = "UPDATE `settings` SET `option_value`='$sanitize' WHERE `option_name`= '$value'";
        }
        $sql = mysqli_query($connection, $query);
        if (!$sql) {
            die(mysqli_connect_error());
        }
    }
    foreach ($arr as $value) {
        $select_query = "SELECT `option_name`, `option_value` FROM `settings` WHERE `option_name` = '$value'";
        $select_sql = mysqli_query($connection, $select_query);
        $select_row = mysqli_fetch_array($select_sql);
        $$value = $select_row['option_value'];
    }

    echo '<small class="msg">Your settings has been saved!</small>';
}


?>


<form action="index.php?dashboard=settings&action=<?php echo $var; ?>" method="post" enctype="multipart/form-data">

    <label for="phone">Phone</label>
    <input type="tel" name="phone" id="phone" value="<?php echo $phone; ?>">
    <h4>Contact 1 </h4>
    <div class="row">
        <div class="group">
            <label for="contact1Title">Title</label>
            <input type="text" name="contact1Title" id="contact1Title" value="<?php echo $contact1Title; ?>" placeholder="contact method">
        </div>
        <div class="group">
            <label for="contact1email">Email</label>
            <input type="email" name="contact1email" id="contact1email" value="<?php echo $contact1email; ?>" placeholder="enter an email">
        </div>
    </div>
    <h4>Contact 2 </h4>
    <div class="row">
        <div class="group">
            <label for="contact2Title">Title</label>
            <input type="text" name="contact2Title" id="contact2Title" value="<?php echo $contact2Title; ?>" placeholder="contact method">
        </div>
        <div class="group">
            <label for="contact2email">Email</label>
            <input type="email" name="contact2email" id="contact2email" value="<?php echo $contact2email; ?>" placeholder="enter an email">
        </div>
    </div>
    <h4>Contact 3 </h4>
    <div class="row">
        <div class="group">
            <label for="contact3Title">Title</label>
            <input type="text" name="contact3Title" id="contact3Title" value="<?php echo $contact3Title; ?>" placeholder="contact method">
        </div>
        <div class="group">
            <label for="contact3email">Email</label>
            <input type="email" name="contact3email" id="contact3email" value="<?php echo $contact3email; ?>" placeholder="enter an email">
        </div>
    </div>

    <h4>Address</h4>
    <div class="row">
        <div class="group">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" value="<?php echo $address; ?>">
        </div>
        <div class="group">
        <label for="addressDesc">Address description</label>
        <input type="text" name="addressDesc" id="addressDesc" value="<?php echo $addressDesc; ?>">
        </div>

    </div>
    <h4>Social Media</h4>
    <label for="sns1">Twitter</label>
    <input type="text" name="sns1" id="sns1" value="<?php echo $sns1; ?>">

    <label for="sns2">Instagram</label>
    <input type="text" name="sns2" id="sns2" value="<?php echo $sns2; ?>">

    <label for="sns3">Facebook</label>
    <input type="text" name="sns3" id="sns3" value="<?php echo $sns3; ?>">
    <h4>Event Period</h4>
    <div class="row">
        <div class="group">
            <label for="start">Start date</label>
            <input type="date" name="startdate" id="startdate" value="<?php echo $startdate; ?>" required>
        </div>
        <div class="group">
            <label for="end">End date</label>
            <input type="date" name="enddate" id="enddate" value="<?php echo $enddate; ?>" required>
        </div>
    </div>

    <hr>

    <label for="adminemail">Admin Email</label>
    <input type="email" name="adminemail" id="adminemail" value="<?php echo $adminemail; ?>">

    <input type="submit" value="Save" class="btn btn-md btn-primary">
</form>
<?php //else : 
?>

<?php //endif; 
?>