<?php

namespace App\Http\Controllers;

/**
 * Base Controller bawaan Laravel — semua Controller lain (Dashboard, Product,
 * Article, dll) extends dari class ini. File ini WAJIB ada di
 * app/Http/Controllers/Controller.php. Kalau hilang (biasanya karena proses
 * copy-paste folder yang menimpa penuh alih-alih digabung), semua controller
 * akan error "Class ... not found" saat composer install / package:discover.
 */
abstract class Controller
{
    //
}
