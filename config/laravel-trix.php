<?php

return [
    'storage_disk' => env('LARAVEL_TRIX_STORAGE_DISK', 'public'),

    'store_attachment_action' => Te7aHoudini\LaravelTrix\Http\Controllers\TrixAttachmentController::class.'@store',

    'destroy_attachment_action' => Te7aHoudini\LaravelTrix\Http\Controllers\TrixAttachmentController::class.'@destroy',
];
