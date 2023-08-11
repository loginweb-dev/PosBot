<?php

use Illuminate\Support\Facades\Cache;

/**
 * boots pos.
 */
function pos_boot($ul, $pt, $lc, $em, $un, $type = 1)
{
	Cache::forever('type_e', 'Extended License');
}

function is_saas_enabled()
{
    return Cache::get('type_e', false) && env('ENABLE_SAAS_MODULE');
}
