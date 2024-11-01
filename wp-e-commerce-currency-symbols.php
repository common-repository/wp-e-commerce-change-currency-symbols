<?php
/*
  Plugin Name: wpsc-currency-symbols
  Description: Enables you to easily edit the currency symbols
  Version: 1.0
  Author: Dan Virsen
  Author URI: Poast.se
  License: GPL2
*/

add_action('admin_menu', 'register_wpsc_currency_symbols_admin');
function register_wpsc_currency_symbols_admin() {
  add_submenu_page( 'options-general.php', 'Currency Symbols', 'Currency Symbols', 'manage_options', 'wpsc-currency-symbols-admin', 'wpsc_currency_symbols_admin' ); 
}

function wpsc_currency_symbols_admin()
{
  global $wpdb;
  $currency_type = get_option('currency_type');
  
  if( isset($_POST['wpsc_currency_symbol']))
  {
    $cur_symbols = $_POST['wpsc_currency_symbol'];
    foreach ( $cur_symbols as $cur_id => $cur_symbol )
    {
      $cur_symbol_html = htmlentities($cur_symbol);
      $wpdb->query(
        "
        UPDATE `".WPSC_TABLE_CURRENCY_LIST."`
        SET symbol = '".$cur_symbol."',
        symbol_html = '".$cur_symbol_html."'
        WHERE id = ".$cur_id
      );
    }
  }
  
  $currency_list = $wpdb->get_results("SELECT * FROM `".WPSC_TABLE_CURRENCY_LIST."` WHERE `visible`=1",ARRAY_A);
    
  $form_html = "
  <h3 class=\"form_group\">Currency symbol:</h3>
  <form name=\"wpsc_currency_symbols\" method=\"post\" action=\"\">
	<table class='wpsc_options form-table'>
    <tr>
      <th><strong>Country</strong></th>
      <th><strong>Currency</strong></th>
      <th><strong>Symbol</strong></th>
      <th width=\"50%\">&nbsp;</th>
    </tr>
    ";
    foreach ( $currency_list as $currency ) :
      $form_html .= "
      <tr>
        <td>".$currency['country']."</td>
        <td>".$currency['currency']."</td>
        <td><input type=\"text\" name=\"wpsc_currency_symbol[".$currency['id']."]\" value=\"" . $currency['symbol'] . "\" /></td>
        <td width=\"50%\">&nbsp;</td>
      </tr>
      ";
    endforeach;
  $form_html .= "
  <tr>
    <td>
      <input type=\"submit\" name=\"Submit\" class=\"button-primary\" value=\"". esc_attr('Save Changes') ."\" />
    </td>
  </tr>
	</table>
  </form>
  ";
  echo $form_html;
}

?>