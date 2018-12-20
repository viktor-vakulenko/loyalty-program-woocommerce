<?php
/*
Plugin Name: WooCommerce Sale
Plugin URI:
Description: Create sale for product category
Version: 1.0
Author: Viktor Vakulenko
Author URI:
License: GPL
*/


//-------------------------------------------------------

add_action( 'admin_menu', 'sale_admin_menu' );
add_action( 'admin_init', 'sale_admin_settings' );

function sale_admin_settings(){
    // $option_group, $option_name, $sanitize_callback
    register_setting( 'sale_theme_options_group', 'sale_theme_options', 'sale_theme_options_sanitize' );

    // $id, $title, $callback, $page
    add_settings_section( 'sale_theme_options_id', '', '', 'sale-theme-options' );
    // $id, $title, $callback, $page, $section, $args
    add_settings_field( 'sale_theme_options_body', 'Percent of sale', 'sale_theme_body_cb', 'sale-theme-options', 'sale_theme_options_id' , array('label_for' => 'sale_theme_options_body') );
    add_settings_field( 'sale_theme_options_header', 'Category wocommerce', 'sale_theme_header_cb', 'sale-theme-options', 'sale_theme_options_id', array('label_for' => 'sale_theme_options_header') );
}

function sale_theme_body_cb(){
    $options = get_option('sale_theme_options');
    ?>

    <input type="text" name="sale_theme_options[sale_theme_options_body]" id="sale_theme_options_body" value="<?php echo esc_attr($options['sale_theme_options_body']); ?>" class="regular-text">

    <?php
}

function sale_theme_header_cb(){
    $options = get_option('sale_theme_options');
    $orderby = 'name';
    $order = 'asc';
    $hide_empty = false ;
    $cat_args = array(
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
    );

    $product_categories = get_terms( 'product_cat', $cat_args );

    if( !empty($product_categories) ){
        echo '
 
<ul>';
        foreach ($product_categories as $key => $category) {
            echo '
 
<li>';
            echo '<a href="'.get_term_link($category).'" >';
            echo $category->name;
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>
 
 
';
    }

}

function sale_theme_options_sanitize($options){
    $clean_options = array();
    foreach($options as $k => $v){
        $clean_options[$k] = strip_tags($v);
    }
    return $clean_options;
}

function sale_admin_menu(){
    // $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position
    add_menu_page( 'Опции темы (title)', 'Loyalty program ', 'manage_options', 'sale-theme-options',
        'sale_option_page', plugins_url( 'loyalty.png', __FILE__ ) ,40 );
}

function sale_option_page(){
    $options = get_option( 'sale_theme_options' );
    ?>

    <div class="wrap">
        <h2>Options</h2>
        <p>Loyalty programm for products &amp; Woocommerce</p>
        <form action="options.php" method="post">
            <?php settings_fields( 'sale_theme_options_group' ); ?>
            <?php do_settings_sections( 'sale-theme-options' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>

    <?php
}


//function for calculate sale

//add_action( 'woocommerce_cart_calculate_fees','custom_cart_discount', 200, 10 );
//function custom_cart_discounts( $cart ) {
//    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
//        return;
//    //  product category
//    $category = array('kosher-wine','dry-wine','limited-edition-wine','home-made-wine','new-wine','sacura-wine','fruit-wine','authors-wine','all-products');
//    // initializing variables
//    $discount = 0;
//    $calculated_qty = 0;
//    $calculated_total = 0;
//    $discount = 0;
//    // Iterating through each cart item
//    foreach($cart->get_cart() as $cart_item):
//
//        // Make this discount calculations only for products of your targeted category
//        if(has_term($category, 'product_cat', $cart_item['product_id'])):
//            $item_price = $cart_item['data']->get_price();
//            $item_qty = $cart_item["quantity"];// Quantity
//            $item_line_total = $cart_item["line_total"]; // Item total price (price x quantity)
//            $calculated_qty += $item_qty; // ctotal number of items in cart
//            $calculated_total += $item_line_total; // calculated total items amount
//        endif;
//    endforeach;
//    //  calculations
//
//    if($calculated_total >= 2000 && $calculated_total < 3000):
//        $calculation = $calculated_total * 0.05;
//        $discount -= $calculation;
//        $cart->add_fee( __( 'Discount 5%', 'chizay' ), $discount, false );
//        wc_clear_notices();
//        wc_add_notice( __( 'Loyalty discount 5% congratulations!', 'chizay' ));
//    endif;
//    if($calculated_total >= 3000 && $calculated_total < 4000):
//        $calculation = $calculated_total * 0.07;
//        $discount -= $calculation;
//        $cart->add_fee( __( 'Discount 7%', 'chizay' ), $discount, false );
//        wc_clear_notices();
//        wc_add_notice( __( 'Loyalty discount 7% congratulations!', 'chizay' ));
//    endif;
//    if($calculated_total >= 4000):
//        $calculation = $calculated_total * 0.1;
//        $discount -= $calculation;
//        $cart->add_fee( __( 'Discount 10%', 'chizay' ), $discount, false );
//        wc_clear_notices();
//        wc_add_notice( __( 'Loyalty discount 10% congratulations!', 'chizay' ));
//    endif;
//}































































