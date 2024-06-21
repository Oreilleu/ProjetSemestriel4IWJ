<?php

use App\Kernel;

require_once '/var/www/html/symfony-app/public_html/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
