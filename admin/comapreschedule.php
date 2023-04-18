<?php

// check if the form has been submitted
if (isset($_POST['submit'])) {
    // check if a file was uploaded
    if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // check if the connection was successful
        if ($connection->connect_error) {
            die('Connection failed: ' . $connection->connect_error);
        }

        // open the uploaded CSV file for reading
        $file = fopen($_FILES['file']['tmp_name'], 'r');

        // extract the dates from the first row
        $headers = fgetcsv($file);

        // initialize an array to hold the dates
        $dates = array();

        // iterate through each column in the first row
        for ($i = 1; $i < count($headers); $i += 2) {
            // extract the date from the current column
            $dates[] = $headers[$i];
        }

        // initialize an array to hold the vendor data
        $vendor_data = array();

        // iterate through each row in the file
        while (($row = fgetcsv($file)) !== false) {
            // extract the location id from the first column of data
            $location_id = $row[0];
            // foreach ($row as $key => $value) {
            //     # code...
            //     echo "key type".$key."=".gettype($key);
            //     echo "value type".$key."=".gettype($value);
            //     echo "key".$key."=".$key;
            //     echo "value ".$key."=".$value;
            //     echo '<br>';
            // }
            $filerow = $row;
            for ($i = 0; $i < count($dates); $i++) {
                // extract the vendor email for the current date
                // print_r($filerow);
                $vendor_email = $filerow[2 + ($i * 2)];
                // extract the start date for the current row
                $start_date = $dates[$i];

                // look up the vendor ID based on the vendor email, if available
                $vendor_id = null;
                if (!empty($vendor_email)) {
                    $sql = "SELECT `id` FROM vendor WHERE `email` = '$vendor_email'";
                    // echo "id sql=" . $sql;
                    try {
                        // execute the SQL query using your database connection
                        $result = $connection->query($sql);
                        $rowsql = $result->fetch_assoc();
                        $vendor_id = $rowsql['id'];
                    } catch (Exception $e) {
                        // handle the exception here
                        echo 'Error: ' . $e->getMessage();
                        continue; // skip this iteration and continue with the next date
                    }
                } else {
                    // no vendor email provided, set vendor_id to empty string
                    $vendor_id = ' ';
                }

                // add the vendor data to the array
                if (!isset($vendor_data[$location_id][$start_date])) {
                    $vendor_data[$location_id][$start_date] = array();
                }
                // add the vendor ID to the vendor data array, even if it is empty
                $vendor_data[$location_id][$start_date][] = $vendor_id;
            }
        }


        // close the CSV file
        fclose($file);

        // check if the dates in the CSV file are different from the dates in the database
        $different_dates = false;
        $sql = "SELECT DISTINCT `date` FROM vendor_map";
        $result = $connection->query($sql);
        $db_dates = array();
        while ($row = $result->fetch_assoc()) {
            $db_dates[] = $row['date'];
        }
        sort($db_dates);
        sort($dates);
        if ($db_dates != $dates) {
            $different_dates = true;
        }

        // if the dates are different, delete all rows from the vendor_map table and insert the new data
        if ($different_dates) {
            try {
                // delete all rows from the vendor_map table
                $sql = "DELETE FROM vendor_map";
                $result = $connection->query($sql);

                // insert the new data into the vendor_map table
                foreach ($dates as $start_date) {
                    $location_id_list = '';
                    $vendor_id_list = '';

                    // iterate through each location and vendor for the current date
                    foreach ($vendor_data as $location_id => $date_data) {
                        if (isset($date_data[$start_date])) {
                            $location_id_list .= $location_id . ',';
                            $vendor_id_list .= implode(',', array_unique($date_data[$start_date])) . ',';
                        }
                    }

                    // remove the trailing commas from the location and vendor lists
                    $location_id_list = rtrim($location_id_list, ',');
                    $vendor_id_list = rtrim($vendor_id_list, ',');

                    // insert the row into the database
                    $sql = "INSERT INTO vendor_map (`location_id`, `vendor_id`, `date`) VALUES ('$location_id_list', '$vendor_id_list', '$start_date')";
                    // echo "INSERT sql=" . $sql;
                    // execute the SQL query using your database connection
                    $result = $connection->query($sql);
                }
                echo '<small class="msg">The File Upload Successful!</small>';
                echo "<a class='btn btn-outlined' href='../admin/index.php'>Back to Dashboard?</a>";

            } catch (Exception $e) {
                // handle the exception here
                echo 'Error: ' . $e->getMessage();
                echo '<small class="msg">The File Upload Faild!</small>';
                echo "<a class='btn btn-outlined' href='../admin/index.php?dashboard=marketschedule'>Try again?</a>";


                exit;
            }
        } else {
            // iterate through the dates and insert/update the data into the database
            foreach ($dates as $start_date) {
                $location_id_list = '';
                $vendor_id_list = '';

                // iterate through each location and vendor for the current date
                foreach ($vendor_data as $location_id => $date_data) {
                    if (isset($date_data[$start_date])) {
                        $location_id_list .= $location_id . ',';
                        $vendor_id_list .= implode(',', array_unique($date_data[$start_date])) . ',';
                    }
                }

                // remove the trailing commas from the location and vendor lists
                $location_id_list = rtrim($location_id_list, ',');
                $vendor_id_list = rtrim($vendor_id_list, ',');

                // check if the row already exists in the vendor_map table
                $sql = "SELECT `id` FROM vendor_map WHERE `date` = '$start_date'";
                $result = $connection->query($sql);
                $row = $result->fetch_assoc();
                $vendor_map_id = $row['id'];
                try {
                    // if the row exists, update it with the new data
                    if ($vendor_map_id) {
                        $sql = "UPDATE vendor_map SET `location_id` = '$location_id_list', `vendor_id` = '$vendor_id_list' WHERE `id` = '$vendor_map_id'";
                        // echo "UPDATE sql=" . $sql;
                        // execute the SQL query using your database connection
                        $result = $connection->query($sql);
                    } else {
                        // if the row does not exist, insert it into the database
                        $sql = "INSERT INTO vendor_map (`location_id`, `vendor_id`, `date`) VALUES ('$location_id_list', '$vendor_id_list', '$start_date')";
                        // echo "INSERT sql=" . $sql;
                        // execute the SQL query using your database connection
                        $result = $connection->query($sql);
                        echo '<small class="msg">The File Upload Successful!</small>';
                        echo "<a class='btn btn-outlined' href='../admin/index.php'>Back to Dashboard?</a>";
                    }
                } catch (Exception $e) {
                    // handle the exception here
                    echo 'Error: ' . $e->getMessage();
                    echo '<small class="msg">The File Upload Faild!</small>';
                    echo "<a class='btn btn-outlined' href='../admin/index.php?dashboard=marketschedule'>Try again?</a>";

                    exit;
                }
            }
        }
    }
}

// header("Location: ../admin/index.php?dashboard=marketschedule");
exit;
