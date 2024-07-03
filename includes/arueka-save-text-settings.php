<?php 

if (isset($_POST['submit'])) {
    // Check if the file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $upload_dir = wp_upload_dir(); // Get the WordPress upload directory
        $file_name = $_FILES["image"]["name"];
        $file_tmp = $_FILES["image"]["tmp_name"];
        $file_path = $upload_dir['path'] . '/' . $file_name;
        $file_url = $upload_dir['url'] . '/' . $file_name;

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            update_option('uploaded_image_url', $file_url);
            echo "<div class='gc-success-message'>Image uploaded successfully!</div>";
        } else {
            echo "<div class='gc-error-message'>Error moving uploaded image.</div>";
        }
    } else {
        echo "<div class='error-message'>Error uploading image.</div>";
    }
}


?>
<div class="Gc_wrap gc-arueka-setting">
    <h1>Upload Image</h1>
    <form class="gc-arueka-img-form" method="post" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <div class="input-container">
            <input type="file" id="image" name="image">
            <input type="submit" name="submit" value="Upload Image">
        </div>
    </form>


    <?php
    // Retrieve the image URL from the wp_options table
    $image_url = get_option('uploaded_image_url');
    if (!empty($image_url)): ?>
        <div id="uploaded-image">
            <h2>The uploaded image will be displayed in the vendor registration form on both step 1 and step 2.</h2>
            <img src="<?php echo esc_url($image_url); ?>" alt="Uploaded Image">
        </div>
    <?php endif; ?>
</div>



