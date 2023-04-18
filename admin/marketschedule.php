<h2>Upload Vendor Placement CSV File</h2>
<h3 class="msg">*Please download the file from Marketwurks with email column</h3>
<form method="post" enctype="multipart/form-data" id="scheduleform" action="../admin/index.php?dashboard=comapreschedule">
    <label for="file">Choose a CSV file to upload:</label>
    <input type="file" id="file" name="file" accept=".csv">
    <br>
    <input type="submit" name="submit" value="Upload" class="btn btn-primary">
</form>