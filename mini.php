<?php
echo "<h2>Mini File Manager & Uploader</h2>";

if (isset($_FILES['file'])) {
    $target = basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        chmod($target, 0644);
        echo "<p>Uploaded and permission set: $target</p>";
    } else {
        echo "<p>Upload failed!</p>";
    }
}

echo '<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" value="Upload">
</form>';

echo "<h3>Directory Listing:</h3><ul>";
$files = scandir(".");
foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;
    $perms = substr(sprintf('%o', fileperms($file)), -4);
    echo "<li><a href='$file'>$file</a> &nbsp; [$perms]</li>";
}
echo "</ul>";

?>