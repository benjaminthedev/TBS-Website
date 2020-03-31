<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly

// $widget_args is passed when widget is initialised
print_object($widget_args, true);


$current_currency = $widget_args['selected_currency'];
$current_currency_info = get_currency_info($current_currency);

?>


<form class="switcher float-left" method="post">
    <a href="#" id="switcher_drop">
      <div class="switcher">
            <?php echo get_img("$current_currency.png", $current_currency);
            echo "<span class='hidden-sm-down'>{$current_currency_info['name']}</span>" ?>
      </div>
        <ul class="currency_drop_down">
            <?php foreach ($widget_args['currency_options'] as $currency_code)  :
                $currency_info = get_currency_info($currency_code);
                $image = get_img("$currency_code.png", $currency_code);
                echo "<li><button class='currency_option' name='aelia_cs_currency' value='$currency_code'>
                          $image <label class='currency_label'>$currency_code, {$currency_info['symbol']}</label>
                    <small class='currency_label_small'>$currency_code</small></button></li>";
            endforeach;
            ?>
        </ul>
</form>
