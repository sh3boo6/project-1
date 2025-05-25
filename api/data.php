<?php

$data = [
    'message' => 'Hello, World!',
    'timestamp' => date('Y-m-d H:i:s'),
];
json_response($data, 200);