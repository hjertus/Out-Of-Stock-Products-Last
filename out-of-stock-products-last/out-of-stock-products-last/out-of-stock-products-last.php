<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://hjertus.online
 * @since             1.0.0
 * @package           Out_Of_Stock_Products_Last
 *
 * @wordpress-plugin
 * Plugin Name:       Out Of Stock Products Last
 * Plugin URI:        https://hjertus.online
 * Description:       Puts all Out Of Stock Products Last in archive pages. 
 * Version:           1.0.0
 * Author:            Theon
 * Author URI:        https://hjertus.online/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       out-of-stock-products-last
 * Domain Path:       /languages
 */


/**
 * snippet       Sort Products By Stock Status - WooCommerce Shop
 * how-to        Get CustomizeWoo.com FREE
 * author        Rodolfo Melogli
 * compatible    WooCommerce 3.9
 * donate $9     https://businessbloomer.com/bloomer-armada/
 */

/**
 * Order product collections by stock status, instock products first.
 */
class iWC_Orderby_Stock_Status
{

    public function __construct()
    {
        // Check if WooCommerce is active
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000);
        }
    }

    public function order_by_stock_status($posts_clauses)
    {
        global $wpdb;
        // only change query on WooCommerce loops
        if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
            $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
            $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
            $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
        }
        return $posts_clauses;
    }
}

new iWC_Orderby_Stock_Status;

// remove OOS products from related products in WooCommerce, because they are OOS! by Robin Scott of silicondales.com - see more at https://silicondales.com/tutorials/woocommerce/remove-out-of-stock-products-from-woocommerce-related-products/
add_filter('woocommerce_related_products', 'exclude_oos_related_products', 10, 3);

function exclude_oos_related_products($related_posts, $product_id, $args)
{
    $out_of_stock_product_ids = (array)wc_get_products(array(
        'status' => 'publish',
        'limit' => -1,
        'stock_status' => 'outofstock',
        'return' => 'ids',
    ));

    $exclude_ids = $out_of_stock_product_ids;

    return array_diff($related_posts, $exclude_ids);
}
?>
