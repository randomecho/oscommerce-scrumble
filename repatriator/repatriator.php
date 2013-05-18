<?php
/**
 * Repatriator
 *
 * Fix up the address book entries from incorrect import losing
 * country and state/province data into an osCommerce store
 *
 * @package    osCommerce Scrumble
 * @author     Soon Van - randomecho.com
 * @copyright  2013 Soon Van
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU Public License
 */

$max_per_page = 15;
define('FILENAME_REPATRIATOR', 'repatriator.php');

require 'includes/application_top.php';

if (isset($_POST['abook_save']))
{
  foreach ($_POST['fixer'] as $address_book_id)
  {
    $country_id = ($_POST['fixer_country'][$address_book_id] > 0) ? (int)$_POST['fixer_country'][$address_book_id] : 0;
    $zone_id = ($_POST['fixer_state'][$address_book_id] > 0) ? (int)$_POST['fixer_state'][$address_book_id] : 0;

    $sql_data_array = array('entry_country_id' => $country_id,
                            'entry_zone_id' => $zone_id);

    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$address_book_id . "'");
  }

  tep_redirect(tep_href_link(FILENAME_REPATRIATOR));
}
else
{
?>
<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Repatriator</title>
<style>
body {
  background: #fff;
  color: #000;
  font-size: 0.9em;
  font-family: sans-serif;
  margin: 0;
  padding: 1em;
}

.abookline {
  border-bottom: 1px solid #ccc;
  padding: 1em 0;
}

input[type="submit"] {
  background: #000;
  border: none;
  border-radius: 5px;
  color: #fff;
  font-size: 1.5em;
  margin: 1em 0;
}
</style>
</head><body>
<?php
  $zone_query = tep_db_query("select z.zone_id, z.zone_country_id, z.zone_code, z.zone_name, c.countries_name
    from zones z inner join countries c on z.zone_country_id=c.countries_id
    order by z.zone_name, c.countries_name");

  $zone_list = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));

  if (tep_db_num_rows($zone_query))
  {
    while ($row = tep_db_fetch_array($zone_query))
    {
      $zone_list[] = array(
        'id' => $row['zone_id'],
        'text' => $row['zone_name'].' ['.$row['zone_code'].'] - '.$row['countries_name'],
      );
    }
  }

  $misaligned_query_count = tep_db_query("select count(address_book_id) as displaced
    from address_book
    where entry_country_id = 0
    order by entry_city, entry_suburb
    limit $max_per_page");
  if (tep_db_num_rows($misaligned_query_count))
  {
    $info = tep_db_fetch_array($misaligned_query_count);
    echo '<h1>'.$info['displaced'].' address book entries left to fix</h1>';
    echo '<p>Showing only '.$max_per_page.' at a time for your sanity.</p>';
  }

  $misaligned_query = tep_db_query("select address_book_id, entry_street_address, entry_suburb, entry_city, entry_state, entry_country_id, entry_zone_id
    from address_book
    where entry_country_id = 0
    order by entry_city, entry_suburb
    limit $max_per_page");

  if (tep_db_num_rows($misaligned_query))
  {
    echo tep_draw_form('abookfixer', tep_href_link(FILENAME_REPATRIATOR), 'post');

    while ($row = tep_db_fetch_array($misaligned_query))
    {
      $slug_city = ($row['entry_city'] != $row['entry_state']) ? $row['entry_city'].' '.$row['entry_state'] : $row['entry_city'];
      $slug_address = str_replace("\n", ' ', $row['entry_street_address']);
      echo '<div class="abookline">';
      echo '<a href="http://google.com/search?q='.urlencode($slug_address.' '.$row['entry_suburb'].' '.$slug_city).'" target="_blank">'.$slug_address. ', ' .$row['entry_suburb'].' '.$slug_city.'</a>';
      echo '<br>';
      echo 'Country: '.tep_get_country_list('fixer_country['.$row['address_book_id'].']', $row['entry_country_id']);
      echo ' -- State/Province: '.tep_draw_pull_down_menu('fixer_state['.$row['address_book_id'].']', $zone_list, $row['entry_zone_id']);
      echo tep_draw_hidden_field('fixer['.$row['address_book_id'].']', $row['address_book_id']);
      echo '</div>';
    }

    echo tep_draw_hidden_field('abook_save', true);
    echo '<input type="submit" value="save">';
    echo '</form>';
  }
}
?>
</body></html>