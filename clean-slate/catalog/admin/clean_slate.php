<?php
/**
 * Clean Slate
 *
 * Quickly dump all products and/or categories from the catalogue database
 *
 * @package    osCommerce Scrumble
 * @author     Soon Van - randomecho.com
 * @copyright  2013 Soon Van
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

require('includes/application_top.php');

if (isset($_POST['trigger_purge'])) {
  if (isset($_POST['purge_categories'])) {
    tep_db_query("delete from " . TABLE_CATEGORIES. "");
    tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION. "");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . "");

    $messageStack->add_session(STATUS_CATEGORIES, 'warning');
  } else {
    $messageStack->add_session(STATUS_CATEGORIES_SKIP, 'warning');
  }

  if (isset($_POST['purge_customers'])) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . "");
    tep_db_query("delete from " . TABLE_CUSTOMERS . "");
    tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . "");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . "");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . "");
    tep_db_query("delete from " . TABLE_WHOS_ONLINE . "");
    tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null");

    $messageStack->add_session(STATUS_CUSTOMERS, 'warning');
  } else {
    $messageStack->add_session(STATUS_CUSTOMERS_SKIP, 'warning');
  }

  if (isset($_POST['purge_orders'])) {
    tep_db_query("delete from " . TABLE_ORDERS . "");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . "");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "");
    tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . "");
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . "");

    $messageStack->add_session(STATUS_ORDERS, 'warning');
  } else {
    $messageStack->add_session(STATUS_ORDERS_SKIP, 'warning');
  }

  if (isset($_POST['purge_products'])) {
    tep_db_query("delete from " . TABLE_PRODUCTS. "");
    tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION. "");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . "");
    tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . "");
    tep_db_query("delete from " . TABLE_SPECIALS . "");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . "");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . "");
    tep_db_query("delete from " . TABLE_REVIEWS . "");
    tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . "");

    $messageStack->add_session(STATUS_PRODUCTS, 'warning');
  } else {
    $messageStack->add_session(STATUS_PRODUCTS_SKIP, 'warning');
  }

  tep_redirect(tep_href_link(FILENAME_CLEAN_SLATE));
}

require(DIR_WS_INCLUDES . 'template_top.php');
?>
<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
  <p class="main"><?php echo TEXT_WHAT; ?></p>
  <?php echo tep_draw_form('purger', FILENAME_CLEAN_SLATE); ?>
  <p class="main"><?php echo tep_draw_input_field('purge_products', 1, 'id="purge_products"', false, 'checkbox'); ?>&nbsp;<label for="purge_products"><?php echo LABEL_PRODUCTS; ?></label></p>
  <p class="main"><?php echo tep_draw_input_field('purge_categories', 1, 'id="purge_categories"', false, 'checkbox'); ?>&nbsp;<label for="purge_categories"><?php echo LABEL_CATEGORIES; ?></label></p>
  <p class="main"><?php echo tep_draw_input_field('purge_customers', 1, 'id="purge_customers"', false, 'checkbox'); ?>&nbsp;<label for="purge_customers"><?php echo LABEL_CUSTOMERS; ?></label></p>
  <p class="main"><?php echo tep_draw_input_field('purge_orders', 1, 'id="purge_orders"', false, 'checkbox'); ?>&nbsp;<label for="purge_orders"><?php echo LABEL_ORDERS; ?></label></p>
  <p><?php echo tep_draw_hidden_field('trigger_purge', true); ?></p>
  <p><?php echo tep_draw_button(IMAGE_CONFIRM, 'disk'); ?></p>
  </form>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
