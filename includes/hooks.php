<?php

/**********************************************************************
 * GET FLEXI CONTENT
 **********************************************************************/

function get_flexi_content()
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/flexi_content.php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/flexi_content.php");
    }
}

add_action("get_flexi_content", "get_flexi_content", 10, 1);

/**********************************************************************
 * GET HERO
 **********************************************************************/

function get_hero($hero_type)
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/hero/" . $hero_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/hero/" . $hero_type . ".php");
    }
}

add_action("get_hero", "get_hero", 10, 1);

function get_hero_layout($hero_layout_type)
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/hero/layout/" . $hero_layout_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/hero/layout/" . $hero_layout_type . ".php");
    }
}

add_action("get_hero_layout", "get_hero_layout", 10, 1);

/**********************************************************************
 * GET SECTIONS - by type
 *********************************************************************
 * @param $section_type
 * @param array $args
 */

function get_section($section_type, $args = [])
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/section/" . $section_type . ".php";
    if (file_exists($path)) {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        include($path);
    }
}

add_action("get_section", "get_section", 10, 1);

function get_section_layout($section_layout_type, $args = [])
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/section/layout/" . $section_layout_type . ".php";
    if (file_exists($path)) {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        include(WP_HELPER::CONTENT_ABS_URL() . "/section/layout/" . $section_layout_type . ".php");
    }
}

add_action("get_section_layout", "get_section_layout", 10, 1);


/**********************************************************************
 * GET SECTIONS - by type
 **********************************************************************/

function get_header_part($header_type)
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/header/" . $header_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/header/" . $header_type . ".php");
    }
}

add_action("get_section", "get_section", 10, 1);

function get_header_layout($header_layout_type)
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/header/layout/" . $header_layout_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/header/layout/" . $header_layout_type . ".php");
    }
}

add_action("get_section_layout", "get_section_layout", 10, 1);


/**********************************************************************
 * GET LAYOUTS
 **********************************************************************/

function get_layout($layout_type)
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/layout/" . $layout_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/layout/" . $layout_type . ".php");
    }
}

add_action("get_layout", "get_layout", 10, 1);

/**********************************************************************
 * GET VECTORS
 **********************************************************************/

function get_vectors($vector_type)
{
    $path = WP_HELPER::CONTENT_ABS_URL() . "/vector/" . $vector_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::CONTENT_ABS_URL() . "/vector/" . $vector_type . ".php");
    }
}

add_action("get_vectors", "get_vectors", 10, 1);


/**********************************************************************
 * GET ADMIN PAGE LAYOUT
 **********************************************************************/

function get_admin_page_layout($admin_layout_type)
{
    $path = WP_HELPER::ADMIN_ABS_URL() . "/pages/" . $admin_layout_type . ".php";
    if (file_exists($path)) {
        include(WP_HELPER::ADMIN_ABS_URL() . "/pages/" . $admin_layout_type . ".php");
    }
}