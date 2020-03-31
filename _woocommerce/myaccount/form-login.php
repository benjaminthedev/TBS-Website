<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action('woocommerce_before_customer_login_form'); ?>

<?php if (get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>

<section id="customer_login">

    <div class="row">


        <div class="col-md-6">

            <h2><?php _e('NEW CUSTOMERS', 'woocommerce'); ?></h2>

            <form method="post" class="register">

                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                               placeholder="<?php _e('Username', 'woocommerce'); ?> *"
                               id="reg_username"
                               value="<?php if (!empty($_POST['username'])) echo esc_attr($_POST['username']); ?>"/>
                    </p>

                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                           id="reg_email"
                           value="<?php if (!empty($_POST['email'])) echo esc_attr($_POST['email']); ?>"
                           placeholder="<?php _e('Email address', 'woocommerce'); ?> *"/>
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
                               name="password" id="reg_password" value="<?php _e('Password', 'woocommerce'); ?>"/>
                    </p>

                    <p class="form-row form-row-wide">
                        <input type="password" class="input-text" name="password2" id="reg_password2"
                               value="<?php _e('Confirm Password', 'woocommerce'); ?>"/>
                    </p>

                <?php endif; ?>

                <?php if ($terms_and_conditions = get_field('terms_and_conditions', 'options')) : ?>
                    <p class="form-row form-row-wide">
                        <input type="checkbox" value="1" id="terms_conditions" name="terms_conditions">
                        <label for="terms_conditions" class="checkbox">
                            I have read and accepted the
                            <?php
                            echo "<a href='$terms_and_conditions' target='_blank'>terms and conditions</a>" ?>
                        </label>
                    </p>
                <?php endif; ?>

                <!-- Spam Trap -->
                <div style="<?php echo((is_rtl()) ? 'right' : 'left'); ?>: -999em; position: absolute;"><label
                            for="trap"><?php _e('Anti-spam', 'woocommerce'); ?></label><input type="text" name="email_2"
                                                                                              id="trap" tabindex="-1"
                                                                                              autocomplete="off"/></div>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocomerce-FormRow form-row text-right">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <input type="submit" class="woocommerce-Button btn btn-warning" name="register"
                           value="<?php esc_attr_e('Register', 'woocommerce'); ?>"/>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>

        </div>

        <div class="col-md-6">

            <?php endif; ?>

            <h2><?php _e('RETURNING CUSTOMERS', 'woocommerce'); ?></h2>

            <form class="woocomerce-form woocommerce-form-login login" method="post">

                <?php do_action('woocommerce_login_form_start'); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                           id="username"
                           value="<?php if (!empty($_POST['username'])) echo esc_attr($_POST['username']); ?>"
                           placeholder="Email Address"
                    />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password"
                           placeholder="Password"
                           id="password"/>
                </p>

                <?php do_action('woocommerce_login_form'); ?>

                <p class="form-row clearfix text-md-left text-right">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline float-md-left">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
                               type="checkbox" id="rememberme" value="forever"/>
                        <span><?php _e('Remember me', 'woocommerce'); ?></span>
                    </label>
                    <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                    <input type="submit" class="woocommerce-Button btn btn-warning float-md-right" name="login"
                           value="<?php esc_attr_e('Login', 'woocommerce'); ?>"/>

                </p>
                <p class="woocommerce-LostPassword lost_password">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Lost your password?', 'woocommerce'); ?></a>
                </p>

                <?php do_action('woocommerce_login_form_end'); ?>

            </form>

            <?php if (get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>

        </div>


    </div>

</section>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>
