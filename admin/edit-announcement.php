
<style>
    #color-palette {
        display: flex;
        justify-content: space-between;
        width: 300px;
        margin-bottom: 10px;
    }

    .color {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #fff;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .color:hover {
        transform: scale(1.1);
    }

    .selected-color {
        transform: scale(1.2);
        border-width: 4px;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/29.2.0/classic/ckeditor.js"></script>

<?php
// Get the announcement ID from the URL
$an_id = $_GET['id'];

// Execute a SELECT query to get the announcement with the given ID
$query = "SELECT a.`title`, a.`content`, (SELECT u.`username` FROM users u WHERE u.`id` = a.`author`) as author_name, a.`start_date`, a.`end_date`, a.`status`
          FROM announcements a
          WHERE a.`id` = $an_id";

$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);



// extract the style attribute value
preg_match('/style="([^"]*)"/', $row['title'], $styleMatches);
$styleValue = $styleMatches[1];

// extract the color value from the style attribute
preg_match('/color:\s*rgb\((\d+),\s*(\d+),\s*(\d+)\)/', $styleValue, $colorMatches);
$colorValue = sprintf('rgb(%d, %d, %d)', $colorMatches[1], $colorMatches[2], $colorMatches[3]);

// extract the inner HTML value
preg_match('/>([^<]*)<\/h3>/', $row['title'], $html_matches);
$html_value = $html_matches[1];

// echo 'Style: ' . $style_value . '<br>';
// echo 'Color: ' . $color_value . '<br>';
// echo 'HTML: ' . $html_value . '<br>';



// If the form is submitted, update the announcement in the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    // $author = $_POST['author'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    $query = "UPDATE announcements
              SET `title` = '$title', `content` = '$content', `start_date` = '$start_date', `end_date` = '$end_date', `status` = $status
              WHERE `id` = $an_id";
    echo $query;

    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Announcement updated successfully.";
        header("Location: ../admin/index.php?dashboard=list-announcement");    
    } else {
        echo "Error updating announcement: " . mysqli_error($connection);
    }
}

?>
    <h2>Announcements</h2>

    <form id="annform" action="" method="post">
        <label for="title">Title:</label>
        <input type='text' id='title' name='title' value='<?php echo $html_value; ?>' required><br><br>
        <input type="hidden" id="title-color" name="title-color" value='<?php echo $color_value; ?>'>
        <div id="color-palette">
            <div class="color" style="background-color: #EBB700" data-color="#EBB700"></div>
            <div class="color" style="background-color: #00338D" data-color="#00338D"></div>
            <div class="color" style="background-color: #313235" data-color="#313235"></div>
            <div class="color" style="background-color: #266AA3" data-color="#266AA3"></div>
            <div class="color" style="background-color: #00AB68" data-color="#00AB68"></div>
            <div class="color" style="background-color: #D989A6" data-color="#D989A6"></div>
        </div>
        <br><br>
        <label for="content">Content:</label><br>
        <div id="editor" contenteditable="true"><?php echo $row['content']; ?></div><br><br>
        <input type="hidden" name="content" id="content-field">

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?php echo $row['author_name']; ?>" readonly><br><br>

        <label for="start_date">Start Date:</label>
        <input type="datetime-local" id="start_date" name="start_date" value="<?php echo $row['start_date']; ?>" required><br><br>

        <label for="end_date">End Date:</label>
        <input type="datetime-local" id="end_date" name="end_date" value="<?php echo $row['end_date']; ?>" required><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="0" <?php if ($row['status'] === '1') {
                                    echo 'selected';
                                } ?>>Active</option>
            <option value="1" <?php if ($row['status'] === '0') {
                                    echo 'selected';
                                } ?>>Inactive</option>
        </select><br><br>

        <input type="submit" value="Update" class="btn btn-outlined">
    </form>

<script>
        
        var titleInput = document.getElementById('title');
        const titleColorInput = document.getElementById('title-color');
        const colorPalette = document.getElementById('color-palette');

        // Get the selected color from the color palette
        var titleColor = titleColorInput.value;

        // Set initial color
        titleInput.style.color = titleColorInput.value;
        // Get the title input field value
        const titleValue = document.querySelector("#title").value;

        // Create an h3 element with the title text and inline CSS for the font color
        const titleElement = document.createElement("h3");


        // Handle color selection
        colorPalette.addEventListener('click', function(event) {
            if (event.target.classList.contains('color')) {
                const color = event.target.getAttribute('data-color');
                titleInput.style.color = color;
                titleColorInput.value = `color: ${color}`;
                titleColor = color;
                console.log('titlecolor=', titleColor);
                titleElement.innerText = titleValue;
                titleElement.style.color = titleColor;
            }
        });


        // Get the contenteditable element with tag ID "editor"
        const editor = document.querySelector("#editor");

        // Listen for the "keydown" event
        editor.addEventListener("keydown", function(event) {
            // If the pressed key is not "b", prevent the default behavior
            if (event.key !== "b") {
                event.preventDefault();
            }
        });


        // Initialize the CKEditor
        ClassicEditor.create(document.querySelector("#editor"), {
                toolbar: ["bold"],
            })
            .catch(error => {
                console.error(error);
            });


        // Form validation
        function validateForm() {
            const startDate = new Date(document.getElementById("start_date").value);
            const endDate = new Date(document.getElementById("end_date").value);

            if (endDate < startDate) {
                alert("End date cannot be earlier than start date.");
                return false;
            }
            return true;
        }


        const annform = document.getElementById('annform');
        annform.addEventListener('submit', function(event) {
            event.preventDefault();

            const formattedContent = document.querySelector('.ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline.ck-blurred p').innerHTML;
            console.log("#formattedContent-field", formattedContent);
            // Set the formatted content as the value of the hidden input field with tag ID "content-field"
            const contentField = document.querySelector("#content-field");
            titleElement.innerHTML = titleInput.value;
            titleInput.value = titleElement.outerHTML;
            contentField.value = formattedContent;
            console.log("titleInput=", titleInput.value);
            validateForm();
            annform.submit();
            // window.location.href = "../admin/index.php?dashboard=list-announcement";

        });
</script>