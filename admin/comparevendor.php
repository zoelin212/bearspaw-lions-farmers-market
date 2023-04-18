<?php
ob_start();

//compare
if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
    // Read the uploaded CSV file into an array
    $filename = $_FILES["file"]["tmp_name"];
    $file = fopen($filename, "r");
    $data = array();

    // Get the column names from the first row
    $header = fgetcsv($file);

    // Count the number of columns
    $numColumns = count($header);

    // Escape single quotes in each column name
    $header = array_map(function ($col) {
        return str_replace("'", "''", $col);
    }, $header);

    // Read the rest of the rows into the data array
    while (($row = fgetcsv($file)) !== false) {
        // Escape single quotes in each cell
        $row = array_map(function ($cell) {
            return str_replace("'", "''", $cell);
        }, $row);
        $data[] = $row;
    }

    fclose($file);

    // // Output the number of columns
    // echo "Number of columns: " . $numColumns;

    
    // Compare the data in the CSV file with the data in the database
    $query = "SELECT * FROM vendor";
    $result = mysqli_query($connection, $query);
    $rowcount = mysqli_num_fields($result);
    if ($numColumns == 7) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if the row exists in the CSV data
        $found = false;
        foreach ($data as $csv_row) {
            if (implode(",", $csv_row) == implode(",", $row)) {
                $found = true;
                break;
            }
        }
        // If the row does not exist in the CSV data, delete it from the database
        if (!$found) {
            $id = $row["id"];
            $delete_query = "DELETE FROM vendor WHERE id = $id";
            mysqli_query($connection, $delete_query);
        }
    }
    // echo $rowcount;
   
        // Compare the data in the database with the data in the CSV file
        foreach ($data as $csv_row) {
            // Check if the row exists in the database
            $found = false;
            $name = mysqli_real_escape_string($connection, $csv_row[0]);
            $contact_person = mysqli_real_escape_string($connection, $csv_row[1]);
            $query = "SELECT * FROM vendor WHERE `Name` = '$name' AND `Contact Person` = '$contact_person'";
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0) {
                $found = true;
                $row = mysqli_fetch_assoc($result);
            }
            // If the row exists in the CSV data, update the row in the database
            if ($found) {
                $id = $row["id"];
                $email = mysqli_real_escape_string($connection, $csv_row[2]);
                $category = mysqli_real_escape_string($connection, $csv_row[3]);
                $products = mysqli_real_escape_string($connection, $csv_row[4]);
                $description = mysqli_real_escape_string($connection, $csv_row[5]);
                $status = mysqli_real_escape_string($connection, $csv_row[6]);
                $update_query = "UPDATE vendor SET `Email` = '$email', `Category` = '$category', `Products` = '$products', `Business Description` = '$description', `Status` = '$status' WHERE `id` = $id";
                mysqli_query($connection, $update_query);
            }
            // If the row does not exist in the database, insert it into the database
            else {
                $name = mysqli_real_escape_string($connection, $csv_row[0]);
                $contact_person = mysqli_real_escape_string($connection, $csv_row[1]);
                $email = mysqli_real_escape_string($connection, $csv_row[2]);
                $category = mysqli_real_escape_string($connection, $csv_row[3]);
                $products = mysqli_real_escape_string($connection, $csv_row[4]);
                $description = mysqli_real_escape_string($connection, $csv_row[5]);
                $status = mysqli_real_escape_string($connection, $csv_row[6]);

                // Get the max ID value from the vendor table
                $id_query = "SELECT MAX(id) AS max_id FROM vendor";
                $id_result = mysqli_query($connection, $id_query);
                $id_row = mysqli_fetch_assoc($id_result);
                $max_id = $id_row["max_id"];
                if ($max_id === null) {
                    // If no rows exist in the table, set the max ID to 0
                    $max_id = 0;
                }

                // Generate the new ID value
                $new_id = $max_id + 1;

                // Make sure the new ID does not exceed the row count of the CSV file
                $row_count = count($data);
                if ($new_id > $row_count) {
                    $new_id = $row_count;
                }
                try {
                    //code...
                    $insert_query = "INSERT INTO vendor (`id`, `Name`, `Contact Person`, `Email`, `Category`, `Products`, `Business Description`, `Status`) VALUES ('$new_id', '$name', '$contact_person', '$email', '$category', '$products', '$description', '$status')";
                    // echo $insert_query;
                    mysqli_query($connection, $insert_query);
                } catch (Exception $e) {
                    //throw $th;
                    echo 'Error: ' . $e->getMessage();
                    echo '<small class="msg">The File Upload Faild!</small>';
                    echo "<a class='btn btn-outlined' href='../admin/index.php?dashboard=uploadvendor' >Try again?</a>";

                    exit;
                }
            }
        }
        echo '<small class="msg">The File Upload Successful!</small>';
        echo "<a class='btn btn-outlined' href='../admin/index.php'>Back to Dashboard?</a>";
    } else {
        echo '<small class="msg">Please Check CSV format!</small>';
        echo "<a class='btn btn-outlined' href='../admin/index.php?dashboard=uploadvendor'>Try again?</a>";
    }
}


exit;
