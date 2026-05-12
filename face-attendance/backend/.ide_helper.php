<?php
/**
 * Laravel Stubs untuk IDE
 * Tambahkan ke composer.json di bagian "require-dev":
 * "barryvdh/laravel-ide-helper": "^2.12"

 * Atau gunakan:
 * composer require --dev barryvdh/laravel-ide-helper
 */

// Stubs untuk DB Facade
namespace Illuminate\Support\Facades {
    class DB {
        public static function table($table) {}
    }
}

// Stubs untuk Redirect Redirector
namespace Illuminate\Routing {
    class Redirector {
        public function route($name, $parameters = []) {}
    }
}

// Stubs untuk View Factory
namespace Illuminate\View {
    class Factory {
        public function make($view, $data = []) {}
    }
}
