<?php

/**
 * This is a "static" class which is used to provide helper methods for wordpress.
 *
 * @author BenjaminThedev 
 */
final class WP_HELPER {

    public static function THEME_ABS_URL() {
        global $THEME_ABS_URL;
        return $THEME_ABS_URL;
    }

    public static function CONTENT_ABS_URL() {
        return self::THEME_ABS_URL() . "/content";
    }

    public static function MODULES_ABS_URL() {
        return self::CONTENT_ABS_URL() . "/modules";
    }

    public static function SECTIONS_ABS_URL() {
        return self::CONTENT_ABS_URL() . "/sections";
    }

    public static function HERO_ABS_URL() {
        return self::CONTENT_ABS_URL() . "/hero";
    }

    public static function HEADER_ABS_URL() {
        return self::CONTENT_ABS_URL() . "/header";
    }

    public static function ROWS_ABS_URL() {
        return self::SECTIONS_ABS_URL() . "/rows";
    }

    public static function ADMIN_ABS_URL() {
        return self::THEME_ABS_URL() . "/admin";
    }

}
