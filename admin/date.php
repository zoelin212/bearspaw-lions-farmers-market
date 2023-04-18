<?php 
  $prefill_start = '2023-03-21';
  $prefill_end='2023-03-21';
 $qStart = "SELECT * FROM `settings` WHERE `option_name` = 'startdate'";
 $qEnd = "SELECT * FROM `settings` WHERE `option_name` = 'enddate'";
 $sqlStart = mysqli_query($connection, $qStart);
 $sqlEnd = mysqli_query($connection, $qEnd);
 $resultStart = mysqli_fetch_assoc($sqlStart);
 $resultEnd = mysqli_fetch_assoc($sqlEnd);
 $prefill_start = $resultStart['option_value'];
 $prefill_end=$resultEnd['option_value'];

?> 
<h2>Date</h2>
<div>
    <?php if (isset($_GET['action'])) : ?>
        <a href="index.php?dashboard=date" class="text-btn">
            << Go Back</a>
    <?php endif; ?>          
</div>
<section>
    <?php

    // $_SERVER['REQUEST_METHOD'] == 'POST'

    if (isset($_GET['action'])) :
        // if post: execute query, if not give form to entery
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 'POST execute query:';
            $start = mysqli_real_escape_string($connection, $_POST['start']);
            $end = mysqli_real_escape_string($connection, $_POST['end']);
                $queryStart = "UPDATE `settings` SET `option_value`='$start' WHERE `option_name`= 'startdate'";
                $sqlStart = mysqli_query($connection, $queryStart);
                $queryEnd = "UPDATE `settings` SET `option_value`='$end' WHERE `option_name`= 'enddate'";
                $sqlEnd = mysqli_query($connection, $queryEnd);
                echo '<small class="msg">The changes have been saved!</small>';
          
            echo '<a href="index.php?dashboard=date" class="btn btn-primary">Date Setting</a>';
        } 
    ?>


    <?php else : ?>
        
        <!-- Edit Area -->
        <form action="index.php?dashboard=date&action=update" method="post">
            <label for="start">Start date</label>
            <input type="date" name="start" id="start" value="<?php echo $prefill_start; ?>" required>
            
            <label for="end">End date</label>
            <input type="date" name="end" id="end" value="<?php echo $prefill_end; ?>" required>

            <div class="row"><input type="submit" value="Submit" class="btn btn-outlined btn-md" id="submit"><div id="loading"></div></div>
        </form>


    <?php endif; ?>
</section>