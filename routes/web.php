<?php

use App\Helpers\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return JsonResponse::response(status: 'success', message: 'Welcome to the Aksamedia Backend API', code: 200, data: [
        'documentation' => 'https://www.postman.com/solar-crescent-872954/workspace/aksamedia',
        'live_project' => 'https://aksamedia-backend.rwa.my.id/',
        'github_repository' => 'https://github.com/ZumaAkbarID/akasamedia-backend-test',
        'email' => 'rahmatwahyumaakbar@gmail.com'
    ]);
});