<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
?><!DOCTYPE html>
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
