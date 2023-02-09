<?php declare(strict_types=1);

sleep(5);

// Transparent 1x1 pixel
header('Content-Type: image/png');
echo base64_decode(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjY'
    . 'AAAAAIAAeIhvDMAAAAASUVORK5CYII=',
    true
);
