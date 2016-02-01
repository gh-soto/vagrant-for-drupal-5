<?php
function greenandsave_regions() {
return array(
	'left_topad'            => t('Top Paid Ad'),
	'right_topad'           => t('Top Ad'),
	'topad'           		=> t('Top Google Ad'),
	'topnav'                => t('Top Navigation'),
	'portal'				=> t('Portal Info'),
	'portalnav'				=> t('Portal Navigation'),
	'hr_nav'                => t('Main Navigation: Default'),
	'oth_nav'				=> ('Main Navigation: Special Menu'),
	'go_nav'                => t('Main Navigation: Green office'),
	'ec_nav'				=> ('Main Navigation: Eco Academy'),
	'rightsidebar'          => t('Right Sidebar'),
	'othsidebar'          	=> t('Other Sidebar'),
	'ss'                    => t('Frontpage: Slideshow'),
	'leftsidebar_news'		=> t('News Left Sidebar'),
	'midsidebar_news'		=> t('News Middle Sidebar'),
	'rightsidebar_news'		=> t('News Right Sidebar'),
	'fp_mini'               => t('Frontpage: Mini Block'),
	'fp_widebar'            => t('Frontpage: Wide Block'),
	'fp_lowerblocks'        => t('Frontpage: Lower Block'),
	'fp_lowerwidebar'		=> t('Frontpage: Lower Wide Block'),
	'content'               => t('Content'),
	'lowad'                 => t('Lower Paid Ad'),
	'right_lowad'           => t('Lower Ad'),
	'footer'                => t('Footer'),
	'footer_message'        => t('Footer Message'),
	'relatedcontent'        => t('Related Content'),
	'amazonsearch'			=> t('Store: Above amazon content'),
	'storequickaccess'		=> t('Store: Quick Access'),
	'storenavigation'		=> t('Store: Navigation'),
	'altstorenavigation'	=> t('Store: Alternate Navigation'),
	'cartstorenavigation'	=> t('Store: For Cart/Checkout generic nav'),
	'storeabovecontent'		=> t('Store: Above Content'),
	'storebelowcontent'		=> t('Store: Below Content'),
	'storerightsidebar'		=> t('Store: Right Sidebar'),
	'storeleftsidebar'    	=> t('Store: Left Sidebar'),
	'storefooter'			=> t('Store: Footer'),
	'sitefp_ss'				=> t('Site frontpage slideshow'),
	'sitefp_mainblock'		=> t('Site frontpage lower block')
);
}

function phptemplate_menu_item_link($item, $link_item) {
  if ($item['title']=='-') return "<hr/>";
  if ($item['title']=='<br>') return "<hr/>";
  if ($item['title']=='Also Consider') return "<hr/>";
  $path = explode("#",$link_item['path']);
  if($path[0])
    return l($item['title'], $path[0], !empty($item['description']) ? array('title' => $item['description']) : array(),  isset($item['query']) ? $item['query'] : NULL, $path[1]);
  else
    return $item['title'];
}

function phptemplate_menu_tree($pid = 1) {
  if ($tree = phptemplate_menu_tree_improved($pid)) {
    return "\n<ul class=\"menu\">\n". $tree ."\n</ul>\n";
  }
}
function phptemplate_menu_tree_improved($pid = 1) {
  $menu = menu_get_menu();
  $output = '';

  if (isset($menu['visible'][$pid]) && $menu['visible'][$pid]['children']) {
    $num_children = count($menu['visible'][$pid]['children']);
    for ($i=0; $i < $num_children; ++$i) {
      $mid = $menu['visible'][$pid]['children'][$i];
      $type = isset($menu['visible'][$mid]['type']) ? $menu['visible'][$mid]['type'] : NULL;
      $children = isset($menu['visible'][$mid]['children']) ? $menu['visible'][$mid]['children'] : NULL;
      $extraclass = $i == 0 ? 'first' : ($i == $num_children-1 ? 'last' : '');
      $output .= theme('menu_item', $mid, menu_in_active_trail($mid) || ($type & MENU_EXPANDED) ? theme('menu_tree', $mid) : '', count($children) == 0, $extraclass);
    }
  }
  return $output;
}
function phptemplate_menu_item($mid, $children = '', $leaf = TRUE, $extraclass = '') {
  $item = menu_get_item($mid); // get current menu item
  // decide whether to add the active class to this menu item
 if((($link_item['path']=='<front>') && ($front==$_GET['q'])) ||
    (menu_in_active_trail($mid))){
    $active_class = ' active'; // set active class
  } else { // otherwise...
    $active_class = ''; // do nothing
	}
 return '<li class="'. ($leaf ? 'leaf' : ($children ? 'expanded' : 'collapsed')) . ($extraclass ? ' ' . $extraclass : '') . $active_class .'">'. menu_item_link($mid, TRUE, $extraclass) . $children ."</li>\n";
}


function phptemplate_filter_tips($tips, $long = FALSE, $extra = '') {
// Override filter.module's theme_filter_tips() function to disable tips display.
return '';
}
function phptemplate_filter_tips_more_info() {
// Override filter.module's theme_filter_tips_more_info() function to disable links to tips display.
  return '';
}

function _phptemplate_variables($hook, $vars = array()) {
  switch ($hook) {
    case 'page':
	    $mypath = drupal_get_path_alias($_GET['q']);
		$mypath = explode("/", $mypath);
		$path_class = $mypath[0];
		//tidy up
		
	if(arg(0) == 'node'){
	  $p_class = isset($vars['node']) ? $vars['node']->type : '';
	}
	if (arg(0) == 'taxonomy') {
	  if(is_numeric(arg(2))){
        $term = arg(2);
        //$p_class = taxonomy_get_vocabulary($term->vid);
		//}else{
			$p_class = $path_class;
		}
      }
	  $p_class = preg_replace('/-/', '_', $p_class);
	  $path_class = preg_replace('/-/', '_', $path_class);
	  
	if(($p_class == 'page' && ($path_class == 'greenoffice' ||  $path_class == 'ec' || $path_class == 'eco_academy' || $path_class == 'greenguide')) || $path_class == 'groi' || $path_class == 'greenoffice' ||
	   $path_class == 'green_article' || $path_class == 'forum' || $path_class == 'eco_academy' || $p_class == 'webform' || $path_class == 'gctc' || $path_class == 'green_news'
	   || $path_class == 'audio' || $path_class == 'media'){
		$p_class = $path_class;
	}elseif(($p_class == 'page' && ( $path_class == 'great_green_home_show.html' || $path_class == 'co_host_paul_hughes.html' ||
		   $path_class == 'co_host_doug_hunt.html' || $path_class == 'sponsorship_opportunities.html' || $path_class == 'radio_show_segments.html' ||
		   $path_class == 'eco_mandments.html' )) || $p_class ==  'audio' || $path_class == 'gghs'){
			$p_class = 'gghs';
		}

	
	$vars['portal_class'] = $p_class;
	$vars['nav_class'] = $p_class;
    break;
  }
  return $vars;
}

function phptemplate_user_profile($account, $fields = array()) {
 // Pass to phptemplate, including translating the parameters to an associative array. The element names are the names that the variables
 // will be assigned within your template.
 /* potential need for other code to extract field info */
// return _phptemplate_callback('user_profile', array('user' => $user, 'fields' => $fields));
return _phptemplate_callback('user_profile', array('account' => $account, 'fields' => $fields));
}


function phptemplate_webform_mail_message_3315($form_values, $node, $sid, $cid) {
  return _phptemplate_callback('webform-mail-3315', array('form_values' => $form_values, 'node' => $node, 'sid' => $sid, 'cid' => $cid));
}

drupal_add_js(path_to_theme().'/navigation-drop.js');
drupal_add_css(path_to_theme().'/navigation-drop.css');
drupal_add_js(path_to_theme().'/portal-drop.js');
drupal_add_css(path_to_theme().'/portal-drop.css');
drupal_add_js('misc/jquery.calculation.js');
drupal_add_js('misc/jquery.field.js');
drupal_add_js('misc/extended_rounded_corner.js');
?>
