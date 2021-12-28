<?php

/*
Plugin Name: Kur Düzenleyici
Plugin URI: 
Description: Bu eklenti, katalog ve ürün görüntüleme sayfasına para birimi ekleyecektir. Para birimi simgesi yöneticiden ayarlanabilir.
Author: Halil İbrahim Yücel
Version: 1.0
Author URI: 
*/

add_action('admin_notices', 'brijesh_additional_currency_admin_notices');
function brijesh_additional_currency_admin_notices() {

    if (!is_plugin_active('woocommerce/woocommerce.php')) {

        echo '<div id="notice" class="error"><p>';
        echo '<b>' . __('Kur Göstergesi', 'woocommerce-display-additional-currency') . '</b> ' . __('add-on requires', 'woocommerce-display-additional-currency') . ' ' . '<a href="http://www.woothemes.com/woocommerce/" target="_new">' . __('WooCommerce', 'woocommerce-display-additional-currency') . '</a>' . ' ' . __('plugin. Please install and activate it.', 'woocommerce-display-additional-currency');
        echo '</p></div>', "\n";

    }
}


add_action('admin_menu', 'woo_display_additional_currency');


function woo_display_additional_currency() {


	add_menu_page('Kur Düzenleyici', 'Kur Düzenleyici', 'administrator', __FILE__, 'brijesh_currency_settings_page',plugins_url('/images/icon.png', __FILE__));

	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {

	register_setting( 'brijesh_additional_currency', 'brijesh_currency_symbol' );
	register_setting( 'brijesh_additional_currency', 'brijesh_exchange_rate' );
	
}

function brijesh_currency_settings_page() {
?>

<div class="wrap">
<h2>Ek Kur Göstergesi</h2>


<form method="post" action="options.php">
    <?php settings_fields( 'brijesh_additional_currency' ); ?>
    <?php do_settings_sections( 'brijesh_additional_currency' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Para Biriminin Değiştirin</th>
        <td><input type="text" name="brijesh_currency_symbol" value="<?php echo esc_attr( get_option('brijesh_currency_symbol') ); ?>" /></td>
        </tr> 
    </table>
    
    <?php submit_button(); ?>

<small><a href="mailto:ibrahimyucel95@gmail.com">Halil İbrahim Yücel</a> tarafından OrganiClick için hazırlandı.</small>

</form>


</div>
<?php
}
add_filter( 'woocommerce_get_price_html', 'custom_price_html', 100, 2 );


function custom_price_html( $price, $product ){

	// Kurları Getir
	$JSON = json_decode(file_get_contents('https://finans.truncgil.com/today.json'), true);

    $_new_price = $product->price / $JSON['USD']['Satış'];
	$_new_price = number_format((float)$_new_price, 2, '.', '');
    $price = $price . '<br>'.get_option('brijesh_currency_symbol') ." " . $_new_price;

    

    return apply_filters( 'woocommerce_get_price', $price );
}
?>