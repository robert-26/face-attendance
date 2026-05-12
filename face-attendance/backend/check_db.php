<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    $tables = DB::select('SHOW TABLES');
    print_r($tables);
    if (Schema::hasTable('settings')) {
        echo "Settings table exists.\n";
        print_r(DB::table('settings')->get());
    } else {
        echo "Settings table DOES NOT exist.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
