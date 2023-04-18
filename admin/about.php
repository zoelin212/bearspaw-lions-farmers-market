<?php 
$q = "SELECT COUNT(*) FROM `index_content` WHERE `location`= 4";
$result = mysqli_query($connection, $q);
$count = mysqli_fetch_assoc($result)['COUNT(*)'];

if($count==0){
    $action="add";
    $prefill_content = '';
    $prefill_title='';
}else{
    $action="edit";
    $query = "SELECT * FROM `index_content` WHERE `location`= 4 LIMIT 1";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    $prefill_title = strip_tags($row['title']);
    $prefill_content = strip_tags($row['content']);
}
?> 
<h2>About</h2>
<div>
    <?php if (isset($_GET['action'])) : ?>
        <a href="index.php?dashboard=landing" class="text-btn">
            << Go Back</a>
            <?php else : ?>
               
            <?php endif; ?>
</div>
<section>
    <?php

    // $_SERVER['REQUEST_METHOD'] == 'POST'

    if (isset($_GET['action'])) :
        // if post: execute query, if not give form to entery
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 'POST execute query:';
            $title = mysqli_real_escape_string($connection, $_POST['title']);
            $content = mysqli_real_escape_string($connection, $_POST['content']);
         
            if ($_GET['action'] == "add") {
                $query = "INSERT INTO `index_content`(`location`, `title`, `content`) VALUES (4,'$title','$content')";
                $sql = mysqli_query($connection, $query);
                echo '<small class="msg">Now you have a about page!</small>';
            } elseif ($_GET['action'] == "edit") {
               
                $query = "UPDATE `index_content` SET `title`='$title',`content`='$content' WHERE `location`= 4";
                $sql = mysqli_query($connection, $query);
                echo '<small class="msg">The changes have been saved!</small>';
            }
            
            echo '<a href="index.php?dashboard=about" class="btn btn-primary">About</a>';
        } 
    ?>


    <?php else : ?>
        
        <!-- Edit Area -->
        <form action="index.php?dashboard=about&action=<?php echo $action;?>" method="post">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo $prefill_title; ?>" required>
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="60" rows="10"><?php echo $prefill_content;?></textarea>
            <div class="row"><input type="submit" value="Submit" class="btn btn-outlined btn-md" id="submit"><div id="loading"></div></div>
        </form>


    <?php endif; ?>
</section>