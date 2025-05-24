<?php

$file = readFromUploadsAsBase64('image.png');
echo '<h1>', $file['name'], '</h1>';
echo '<h1>', $file['type'], '</h1>';
echo '<h1>', $file['size'], '</h1>';
echo '<img src="data:image/png;base64,' . $file['base64'] . '" alt="Image from uploads">';