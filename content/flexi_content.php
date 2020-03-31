<?php
/**
 * Created by topclicick
 * Developer: Harrison Parker
 * Date: 25/05/2016
 * Time: 17:05
 */


if (function_exists('have_rows')) {


    $id = have_rows('flexi') ? get_the_ID() : 'options';

    if (have_rows('flexi', $id)) {
        while (have_rows('flexi', $id)) {
            the_row();

            if (get_row_layout() === '') {
                get_hero('');
            }

            if (get_row_layout() === '') {
                get_section('');
            }

        }
    }
}


?>

<h1>hello darkness</h1>

