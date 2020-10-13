<?php

/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 19/05/2017
 * Time: 10:19
 */
class main_menu_walker extends Walker_Nav_Menu
{


    private $no_child = [];

    private $current_item;


    public function check_current($classes)
    {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= ($depth == 0) ? "<div class='mega_menu'><div class='container-fluid clearfix'><ul class='menu_items row'>" : "<ul class='sub_items'>";


    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);
        if($depth === 0)
        $this->current_item = $item;
        $item_html = apply_filters('roots_wp_nav_menu_item', $item_html);
        $output .= $item_html;
    }

    public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {


        $element->has_children = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
        if ($element->has_children && ($depth === 0)) {
            $element->classes[] = 'mega_menu_parent';
        } else if ($element && ($depth === 1) && $element->has_children) {
            $element->classes[] = 'col-lg-4 col-sm-3';
        } 
        if ($element && ($depth === 1) && !$element->has_children) {
            $this->no_child[] = $element;
        } else {
            parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }


    }


    private function display_no_child_element()
    {
        $output = '';

        $no_child_blocks = array_chunk($this->no_child, 3);

        foreach ($no_child_blocks as $no_child_block) {
            $output .= '<li class="col-lg-4 col-sm-3 no_child">';
            $output .= '<ul>';
            foreach ($no_child_block as $key => $value) {
                $classes = implode(" ", $value->classes);
                $output .= "<li><a href='$value->url' class='$classes'>$value->title</a>";
            }
            $output .= '</ul>';
            $output .= '</li>';
        }

        return $output;

    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {

        if ($depth == 0) {


            $output .= $this->display_no_child_element();
            $this->no_child = [];

            $output .= '</ul>';
            $output .= '<div class="menu_content hidden-md-down">';
            ob_start();
            get_section_layout('menu_content', ['item_label' => $this->current_item->title]);
            $output .= ob_get_contents();
            ob_clean();
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        } else {
            $output .= '</ul>';
        }

    }
}