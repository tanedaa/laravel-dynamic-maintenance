<?php

return [
    'title' => env('MAINTENANCE_TITLE', 'Service Unavailable'),
    'message' => env('MAINTENANCE_MESSAGE', 'Service Unavailable'),
    'code' => env('MAINTENANCE_CODE', 503),
];