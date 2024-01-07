
<?php

use Cocur\Slugify\Slugify;

if (!function_exists('slugify')) {
    function slugify(string $title, string $separator = '-'): string
    {
        $slugified = (new Slugify())->activateRuleSet("turkish")->slugify($title, $separator);

        if (!$slugified) {
            $slugified = strtolower(
                preg_replace('/[\s_]+/', $separator, $title),
            );
        }

        return $slugified;
    }
}

if (!function_exists('stringNumberToBool')) {
    function stringNumberToBool($value)
    {
        if ($value === "0") {
            return false;
        }

        if ($value === "1") {
            return true;
        }

        return null;

    }
}
