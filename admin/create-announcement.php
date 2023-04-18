<?php

// Get user id
$userid = checkLogin($connection);

?>

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

    <h2>Announcements</h2>
    <form action="../admin/index.php?dashboard=announcements" method="post" id="annform">

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        <input type="hidden" id="title-color" name="title-color" value="#EBB700">
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
        <div id="editor" contenteditable="true"></div><br><br>
        <input type="hidden" name="content" id="content-field">

        <label for="start_date">Start Date and Time:</label>
        <input type="datetime-local" id="start_date" name="start_date" required><br><br>

        <label for="end_date">End Date and Time:</label>
        <input type="datetime-local" id="end_date" name="end_date" required><br><br>

        <input type="hidden" name="author_id" value="<?php echo $userid; ?>">


        <input type="submit" value="Create" class="btn btn-primary">

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
<?php ob_end_flush(); ?>