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

add_filter( 'woocommerce_product_query_meta_query', 'filter_product_query_meta_query', 10, 2);
function filter_product_query_meta_query ( $meta_query, $query) {
    // On woocommerce home page only
    if ( is_front_page() ){
        // Exclude product "out of stock"
        $meta_query[] = array(
            'key' => '_stock_status',
            'value' => 'outofstock',
            'compare' => '!=',
        );
    }
    return $meta_query;
}

class iWC_Orderby_Stock_Status {

    public function __construct() {
        // Check if WooCommerce is active
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            add_action('pre_get_posts', array($this, 'custom_orderby_stock_status'));
        }
    }

    public function custom_orderby_stock_status($query) {
        if ($query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
            $query->set('meta_key', '_stock_status');
            $query->set('orderby', array('meta_value' => 'ASC', 'title' => 'ASC'));
            $query->set('order', 'ASC');
            $query->set('meta_query', array(
                array(
                    'key' => '_stock_status',
                    'value' => '',
                    'compare' => '!=',
                ),
            ));
        }
    }
}

new iWC_Orderby_Stock_Status();


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
