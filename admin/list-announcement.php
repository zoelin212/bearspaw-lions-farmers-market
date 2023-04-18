   <h2>Announcements</h2>
    <?php
    // Get the current page number from the URL
    $page = isset($_GET['page']) ? $_GET['page'] : 1;


    // Calculate the offset based on the current page number and the number of records per page
    $offset = ($page - 1) * 10;


    // Execute a SELECT query to get the announcements with LIMIT and OFFSET clauses
    $querypage = "SELECT a.`id`, a.`title`, a.`content`, (SELECT u.`username` FROM users u WHERE u.`id` = a.`author`) as author_name, a.`create_date`,a.`start_date`, a.`end_date`, a.`status`
        FROM announcements a
        ORDER BY create_date DESC
        LIMIT 10 OFFSET $offset";
    //   echo $query;
    $result = mysqli_query($connection, $querypage);
    echo "<div><a href='../admin/index.php?dashboard=create-announcement' class='btn btn-theme btn-md'>Create Announcement</a></div>";
    // Display the announcements in a table
    echo "<table>

      <tr>
          <th class='optional'>ID</th>
          <th>Title</th>
          <th class='optional'>Author</th>
          <th>Create Date</th>
          <th class='optional'>Start Date</th>
          <th class='optional'>End Date</th>
          <th>Status</th>
          <th>Edit</th>
      </tr>";


        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
              <td class='optional'>{$row['id']}</td>
              <td>{$row['title']}</td>
              <td class='optional'>{$row['author_name']}</td>

              <td>{$row['create_date']}</td>
              <td class='optional'>{$row['start_date']}</td>
              <td class='optional'>{$row['end_date']}</td>
              <td>" . ($row['status'] ? 'Active' : 'Inactive') . "</td>
              <td><a href='../admin/index.php?dashboard=edit-announcement&id={$row['id']}' class='btn btn-sm btn-outlined'>Edit</a></td>
            </tr>";
    }

    echo "</table>";



        // Display pagination links for navigating between pages
        $query = "SELECT COUNT(*) AS count FROM announcements";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $total_records = $row['count'];
        $total_pages = ceil($total_records / 10);
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='../admin/index.php?dashboard=list-announcement&page=$i' class='btn btn-sm text-btn'>$i</a>";
        }
        echo "</div>";


    ?>
