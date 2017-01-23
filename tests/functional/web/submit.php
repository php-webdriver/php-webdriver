<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Form submit endpoint</title>
</head>
<body>

<?php
if (empty($_POST)) {
    echo '<h2>POST data not detected</h2>';
} else {
    echo '<h2>Received POST data</h2>';
    echo '<ul class="post-data">';
    foreach ($_POST as $key => $value) {
        echo sprintf(
            '<li data-key="%s">%s: <span class="value">%s</span></li>' . "\n",
            $key,
            $key,
            $value
        );
    }
    echo '</ul>';
}
?>

</body>
</html>
