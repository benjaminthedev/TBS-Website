<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 19/05/2017
 * Time: 14:10
 * @var $item_label
 */


if (have_rows('menu_content', 'options')) : ?>
    <div class="row">
        <?php while (have_rows('menu_content', 'options')) : the_row();

            $menu_label = get_sub_field('menu_label');

            $menu_label = explode(', ', $menu_label);

            if (!in_array($item_label, $menu_label)) continue;

            ?>
                <?php
                if (get_row_layout() === 'one_block')
                    get_section('menu_content_block');
                if (have_rows('blocks'))
                    while (have_rows('blocks')) : the_row();
                        get_section('menu_content_block'); endwhile;
                ?>
        <?php endwhile; ?>
    </div>
<?php endif;