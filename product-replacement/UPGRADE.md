# Product replacement - Upgrade

Line numbers are based on a fresh install of osCommerce version 2.3, 
so use them as guides if other modules or enhancements have already been installed.


## Files to edit

- catalog/product_info.php
- catalog/includes/languages/english/product_info.php
- catalog/admin/categories.php
- catalog/admin/includes/functions/general.php
- catalog/admin/includes/languages/english/categories.php

### catalog/product_info.php

Add on line 57, or just inside the PHP tag before `tep_draw_form` appears:

    if ($messageStack->size('product_replaced') > 0) {
      echo $messageStack->output('product_replaced');
    }

Add on line 20, or after the language file is included (the line with `DIR_WS_LANGUAGES`):

    $products_replacement_query = tep_db_query("select pd.products_name, p.products_replacement 
      from " . TABLE_PRODUCTS . " p
      left join " . TABLE_PRODUCTS_DESCRIPTION. " pd on p.products_id=pd.products_id
      where language_id = '" . (int)$languages_id . "' 
      and p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_replacement <> '0'");

    if (tep_db_num_rows($products_replacement_query)) {
      $products_replacement = tep_db_fetch_array($products_replacement_query);
      $messageStack->add_session('product_replaced', sprintf(TEXT_PRODUCT_REPLACED, $products_replacement['products_name']), 'warning');
      tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_replacement['products_replacement']));
    }


### catalog/includes/languages/english/product_info.php

Add on line 21, or after `TEXT_PRODUCT_OPTIONS` is defined:

    define('TEXT_PRODUCT_REPLACED', '<b>%s</b> has been replaced by the following item.');

### catalog/admin/categories.php

Add on line 512, after `TEXT_PRODUCTS_STATUS` completes its table row:

    <tr>
      <td class="main"><?php echo TEXT_PRODUCTS_REPLACEMENT; ?></td>
      <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_get_product_replacements($pInfo->products_replacement); ?></td>
    </tr>

Edit line 407, right after `if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {`, 
to include the following column called in the SQL:

    p.products_replacement, 

Add on line 401, or after `'products_status' => '',`:

    'products_replacement' => '',

Add on line 221, or after `'products_status' => tep_db_prepare_input...`:

    'products_replacement' => tep_db_prepare_input($_POST['products_replacement']),
    

### catalog/admin/includes/functions/general.php

Add the following to the end of the file, or wherever you feel fits best:

    function tep_get_product_replacements($products_id = 0) {
      global $languages_id;

      $products_array = array(array('id' => '0', 'text' => TEXT_NONE));
      $products_query = tep_db_query("select p.products_id, pd.products_name 
        from " . TABLE_PRODUCTS . " p
        left join " . TABLE_PRODUCTS_DESCRIPTION. " pd on p.products_id=pd.products_id
        where language_id = '" . (int)$languages_id . "'
        and p.products_replacement = '0'
        order by pd.products_name");

      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'],
                                  'text' => $products['products_name']);
      }
  
      return tep_draw_pull_down_menu('products_replacement', $products_array, $products_id);
    }
  

### catalog/admin/includes/languages/english/categories.php

Add at line 72, or after `TEXT_PRODUCTS_STATUS` is defined:

    define('TEXT_PRODUCTS_REPLACEMENT', 'Product replaced by:');
 

## Database

Run the following SQL command against the database

    ALTER TABLE products
     ADD COLUMN products_replacement INT (11) DEFAULT '0';

This adds a new column to the PRODUCTS table that allows the 
module to know where the old product should redirect to.