// $Id: README.txt,v 1.5 2008/05/04 19:03:24 aaron Exp $

jQ

The jQ module allows other modules to register jQuery plugins in a central repository, and allows administrators of a site to enable or disable specific plugins globally.

The concept behind this module is to create a basic hook structure for jQuery wrapper modules, allowing other modules using these plugins to invoke them in a consistent fashion.

The basic call to invoke a registered jQuery plugin on a page would be something like the following:

<?php  
  jq_add('plugin-2'); 
  jq_add('plugin-5', $extra1, $extra2, $extra3);
?>

This would load any files required by the plugin, unless an administrator has turned off the plugin manually.

The module defining the plugin would need at the least to provide the following hook:

Note that any extra arguments passed by jq_add will be passed after $plugin.

<?php
function my_module_jq($op, $plugin = NULL, $extra1 = NULL, $extra2 = NULL) {
  switch ($op) {
    case 'info':
      return array(
      	'plugin-1' => array(
      		'name' => t('Plugin One'),
      		'description' => t('This is the Plugin One jQuery plugin. It can make your coffee.'),
                'version' => 'r5 // 2008-01-01 // jQuery 1.1.2',
                'url' => 'http://plugins.jquery.com/project/plugin-1',
      		'files' => array(
      			'js' => array(
      				drupal_get_path('module', 'my_module') . '/js/plugin-1.js',
      			),
      		),
      	),
      	'plugin-2' => array(
      		'name' => t('Plugin Two'),
      		'description' => t('This is the Plugin Two jQuery plugin. It can be found at !link.', array('!link' => l('jQuery Plugins', 'http://jquery.com/plugins/repository/whatever'))),
      		'files' => array(
      			'js' => array(
      				drupal_get_path('module', 'my_module') . '/js/plugin-2-min.js',
      				drupal_get_path('module', 'my_module') . '/js/plugin-2-additional.js',
      			),
      			'css' => array(
      				drupal_get_path('module', 'my_module') . '/css/plugin-2.css',
      			),
      		),
      	),
      );
    case 'add':
    	// any additional processing required when adding a plugin to a page.
    	switch ($plugin) {
    		case 'plugin-1':
    			// fancy code here...
    			break;
    	}
    break;
  }
}
?>

In the future, we might add additional functionality, if we can identify other uses to simplify the jQuery invocation process, such as when jQuery calls are added inline.
