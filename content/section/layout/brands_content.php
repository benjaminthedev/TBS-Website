<?php
/**
 * Created by Connor Mulhall.
 * Date: 19/09/2017
 * Time: 13:38
 * @var $item_label
 */

$brands = [];

$product_brand = get_terms(['taxonomy' => 'product_brand', 'hide_empty' => true]);

foreach ($product_brand as $brand)

    $brands[strtoupper($brand->name[0])][] = [

        'title' => $brand->name,

        'link' => get_term_link($brand, '$brand')

    ];


?>

<div class="brands-menu">

    <header class="brands-menu-navigation">

        <div class="container">

            <div class="row">

                <?php

                $x = 0;

                foreach ($brands as $key => $value) : $x++; ?>

                    <a href="<?php echo "#brand-tab-$key" ?>" class="col <?php echo $x === 1 ? 'active' : ''; ?>">

                        <?php echo $key ?>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </header>

    <div class="brands-menu-tabs">

        <div class="container">

            <?php

            $x = 0;

            foreach ($brands as $key => $input) :

                $x++;

                $len = count($input);

                $firsthalf = array_slice($input, 0, $len / 2);

                $secondhalf = array_slice($input, $len / 2);

                ?>

                <div class="brands-menu-tab" <?php echo $x === 1 ? 'style="display:block;"' : ''; ?>

                id="<?php echo "brand-tab-$key" ?>"

                >

                    <div class="row">

                        <div class="col-lg-6">

                            <div class="title">Brands <?php echo $key; ?></div>

                            <div class="row">

                                <?php if ($firsthalf) : ?>

                                    <div class="col-lg-6">

                                        <ul>

                                            <?php foreach ($firsthalf as $brand)

                                                echo '<li><a href="' . $brand['link'] . '">' . $brand['title'] . '</a></li>';

                                            ?>

                                        </ul>

                                    </div>

                                <?php endif; ?>

                                <?php if ($secondhalf) : ?>

                                    <div class="col-lg-6">

                                        <ul>

                                            <?php foreach ($secondhalf as $brand)

                                                echo '<li><a href="' . $brand['link'] . '">' . $brand['title'] . '</a></li>';

                                            ?>

                                        </ul>

                                    </div>

                                <?php endif; ?>

                            </div>


                        </div>

                        <div class="col-lg-6">

                            <?php get_section_layout('menu_content', ['item_label' => $item_label]); ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>
