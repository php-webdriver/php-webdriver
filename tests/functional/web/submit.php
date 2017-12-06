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
