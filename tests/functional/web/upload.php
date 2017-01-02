<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>File upload endpoint</title>
</head>
<body>

<?php
if (empty($_FILES)) {
    echo '<h2>File upload not detected</h2>';
} elseif (isset($_FILES['upload']) && $_FILES['upload']['error'] == 4) {
    echo '<h2>Form was submitted but no file was selected for upload</h2>';
} else {
    echo sprintf('<h2>Received %d uploaded file(s)</h2>', count($_FILES));
    echo '<ul class="uploaded-files">';
    foreach ($_FILES as $file) {
        echo sprintf(
            '<li>File name: <span class="file-name">%s</span>, size: <span class="file-size">%d</span></li>',
            $file['name'],
            $file['size']
        );
    }
    echo '</ul>';
}
?>

</body>
</html>
