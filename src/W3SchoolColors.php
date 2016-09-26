<?php
/**
 * Created by PhpStorm.
 * User: e.nouni
 * Date: 26/09/2016
 * Time: 13:21
 */

namespace Com\NickelIT\Colors;


class W3SchoolColors
{

    /**
     * Returns a list of colors names mapped with its hex code.
     * Ex: ['name' => 'AliceBlue', 'hex' => '#F0F8FF'],
     *
     * @return array
     */
    public static function colors()
    {
        $path = __DIR__ . '/output/colors_names.php';
        return file_exists($path) ? require $path : [];
    }
}