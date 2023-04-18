<h2>Photo</h2>
<div>
    <?php if (isset($_GET['action'])) : ?>
        <a href="index.php?dashboard=photo" class="text-btn">
            << Go Back</a>
            <?php else : ?>
                <a href="index.php?dashboard=photo&action=add" class="btn btn-theme btn-md">Add Photo</a>
            <?php endif; ?>
</div>
<section>
    <!-- Add/Delete photo -->
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * 10;

    $querypage = "SELECT `id`, `filename`, `caption`
        FROM photo
        ORDER BY `id` DESC
        LIMIT 10 OFFSET $offset";
    // $_SERVER['REQUEST_METHOD'] == 'POST'

    if (isset($_GET['action'])) :
        if ($_GET['action'] == "add") {
            echo '<h3>Add a Photo</h3>';
        } elseif ($_GET['action'] == "delete") {
            echo '<h3>Delte Photo</h3>';
        }

        // if post: execute query, if not give form to entery
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get file
            $imagedbfilename;
            $img;
            $imgdir  =  dirname(dirname(__FILE__)) . '/assets/photo/';
            $imagedbfilename = '';
            try {
                //code...
                if (isset($_FILES['photo'])) {
                    $myFile = $_FILES['photo'];
                    $tmp_name = $_FILES["photo"]["tmp_name"];
                    $ext = pathinfo($myFile["name"], PATHINFO_EXTENSION);
                    // echo "ext= ".$ext;
                    $temp = explode(".", $myFile["name"]);
                    $rename = generateGarbledString(10) . ".jpg";
                    $imagefilename = $imgdir . $rename;

                    // Load the PNG file
                    if ($ext == 'png') {
                        $png_file = imagecreatefrompng($tmp_name);

                        // Create a blank JPEG image with the same dimensions
                        $jpg_file = imagecreatetruecolor(imagesx($png_file), imagesy($png_file));

                        // Copy the PNG data to the JPEG image
                        imagecopy($jpg_file, $png_file, 0, 0, 0, 0, imagesx($png_file), imagesy($png_file));
                        $imagedbfilename = '../assets/photo/' .  $rename;
                        // Save the JPEG file
                        imagejpeg($jpg_file, $imagedbfilename, 50);

                        // Free up memory
                        imagedestroy($png_file);
                        imagedestroy($jpg_file);
                    } else if ($ext == 'jpg') {
                        // echo $imagefilename . '<br>';
                        $imagedbfilename = '../assets/photo/' .  $rename;
                        move_uploaded_file($myFile["tmp_name"], $imagefilename);
                        # compress image
                        $newimage = imagecreatefromjpeg("../$imagedbfilename");
                        imagejpeg($newimage, $imagefilename, 50);
                        imagedestroy($newimage);
                    }
                }
            } catch (Exception $e) {
                //throw $th;
                echo 'Error: ' . $e->getMessage();
            }

            // 'POST execute query:';
            // $filename = mysqli_real_escape_string($connection, $_POST['filename']);

            $caption = mysqli_real_escape_string($connection, $_POST['caption']);
            // echo 'title=' . $title . ' date=' . $date . ' content=' . $content;
            if ($_GET['action'] == "add") {
                $query = "INSERT INTO `photo`(`filename`, `caption`) VALUES ('$imagedbfilename','$caption')";
                // echo $query;
                $sql = mysqli_query($connection, $query);
                echo '<small class="msg">Your photo has been added!</small>';
            }

            echo '<a href="index.php?dashboard=photo" class="btn btn-primary">Photo List</a>';
        } elseif ($_GET['action'] == "delete") {
            $id = $_GET['id'];
            $query_select = "SELECT `id`, `filename`, `caption` FROM `photo`WHERE id='$id'";
            // Set the file path
            $file_path='';
            $sql_select = mysqli_query($connection, $query_select);
            if ($sql_select) {
                while ($row = mysqli_fetch_array($sql_select)) {
                    $file_path = $row['filename'];
                }
            }

            // Delete the file
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            $query = "DELETE FROM `photo` WHERE id='$id'";
            $sql= mysqli_query($connection, $query);
            echo '<small class="msg">The photo has been deleted!</small>';
            echo '<a href="index.php?dashboard=photo" class="btn btn-primary">Photo List</a>';
        } else {

            $id = '';
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            echo '<form action="index.php?dashboard=photo&action=add&id=' . $id . '" method="post" enctype="multipart/form-data">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" accept=".jpg,.png,.jpeg" >
            <label for="caption">Caption</label>
            <input type="text" name="caption" id="caption" value="" required>
            <div class="row"><input type="submit" value="Submit" class="btn btn-outlined btn-md"><div id="loading"></div></div>
        </form>';
        }
    ?>

    <?php else : ?>
        <!-- Photo List -->
        <table>
            <thead>
                <th>Photo</th>
                <th>Caption</th>
                <th>Delete</th>
            </thead>

            <?php
            // $query = "SELECT `id`, `filename`, `caption` FROM `photo`";
            $sql = mysqli_query($connection, $querypage);
            if ($sql) {
                while ($row = mysqli_fetch_array($sql)) {
                    echo '<tr id= "' . $row['id'] . '">';
                    echo '<td><img src="../' . $row['filename'] . '" alt="" width="50" class="thumbnail"></td>';
                    echo '<td>' . $row['caption'] . '</td>';
                    echo '<td><a href="index.php?dashboard=photo&action=delete&id=' . $row['id'] . '" class="btn btn-sm text-btn btn-danger">Delete</a></td>';
                    echo '</tr>';
                }
            }


            ?>
        </table>
        <?php
        // Display pagination links for navigating between pages
        $query = "SELECT COUNT(*) AS count FROM photo";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $total_records = $row['count'];
        $total_pages = ceil($total_records / 10);
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='../admin/index.php?dashboard=photo&page=$i' class='btn btn-sm text-btn'>$i</a>";
        }
        echo "</div>";
        ?>

    <?php endif; ?>
</section>