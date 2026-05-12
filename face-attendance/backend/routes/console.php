<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Stay focused and keep coding!');
})->purpose('Display an inspiring message');