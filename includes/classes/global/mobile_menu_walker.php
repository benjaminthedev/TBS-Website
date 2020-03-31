<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 22/05/2017
 * Time: 14:14
 */


class mobile_menu_walker extends Walker_Nav_Menu {

    public function check_current($classes)
    {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }


    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){

        // add spacing to the title based on the current depth
        $item->title = str_repeat("&#160;", $depth * 4) . $item->title;

        // call the prototype and replace the <li> tag
        // from the generated markup...
        parent::start_el($output, $item, $depth, $args);


       $current = false;
       foreach($item->classes as $class)
           if($class == 'current-menu-item')
               $current = true;


        $output = $current ? str_replace('<li', '<option value="' . $item->url . '" selected', $output) : str_replace('<li', '<option value="' . $item->url . '" ', $output);
    }

    // replace closing </li> with the closing option tag
    public function end_el(&$output, $item, $depth = 0, $args = array()){
        $output .= "</option>\n";
    }


}