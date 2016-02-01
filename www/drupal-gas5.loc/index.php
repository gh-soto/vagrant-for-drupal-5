<?php
// $Id: index.php,v 1.91 2006/12/12 09:32:18 unconed Exp $

/**
 * @file
 * The PHP page that serves all page requests on a Drupal installation.
 *
 * The routines here dispatch control to the appropriate handler, which then
 * prints the appropriate page.
 */

require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$return = menu_execute_active_handler();

//$audio_type = array();
//$i = 0;
//for ($i = 0; $i < 105886; $i++) {
//  $node = node_load(array('nid' => $i, 'type' => 'astorepage',));
////$drupaltype = $node->type;
////$term_id = taxonomy_node_get_terms($node->nid);
//  if (!empty($node)) {
//    $audio_type[] = $node;
//  }
//}
////print_r($audio_type);
//print count($audio_type);
//print '<pre>';
//print_r ($node);
//print '*************************************************';
//print_r (unserialize($node->data));
//print '*************************************************';
//
//print_r ($term_id);
//print '</pre>';
//exit();




// Menu status constants are integers; page content is a string.
if (is_int($return)) {
  switch ($return) {
    case MENU_NOT_FOUND:
      drupal_not_found();
      break;
    case MENU_ACCESS_DENIED:
      drupal_access_denied();
      break;
    case MENU_SITE_OFFLINE:
      drupal_site_offline();
      break;
  }
}
elseif (isset($return)) {
  // Print any value (including an empty string) except NULL or undefined:
  print theme('page', $return);

}

drupal_page_footer();