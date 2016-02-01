<?php
// $Id: feedapi_mapper.api.php,v 1.1.4.2 2009/05/11 17:19:29 alexb Exp $ 
/**
 * @file
 * API documentation for Feed Element Mapper.
 */

/**
 * Implement hook_feedapi_mapper() to map an element of a feed to a field on a 
 * node. 
 * 
 * Mapping flow
 * 
 * 1) hook_feedapi_mapper('list')
 * 
 * This operation is invoked when the mapper is about to present mapping
 * options to the user. Return TRUE to list this mapping or FALSE to not
 * list it. Return an array in order to offer sub-fields to the user. See
 * feedapi_mapper_taxonomy.inc for an example of listing sub-fields.
 * 
 * 
 * 2) hook_feedapi_mapper('map')
 * 
 * This operation is invoked when the actual mapping happens. If a user 
 * selected the mapping functionality exposed on 'list' hook_feedapi_mapper 
 * will be called on node prepare with $op == 'map', $node = feed item node the
 * mapping is performed on, $feed_element = the element of the feed that a user
 * chose as a mapping source, $field_name = the name of the field that a user 
 * chose as a mapping target, $sub_field = the name of the sub field that a user
 * chose as a mapping target.
 * 
 * 
 * 3) hook_feedapi_mapper('describe') 
 * 
 * This operation is used to generate help text on the mapping form. Return a 
 * string of text describing the mapping.
 *
 * 
 * Which feed elements are available for mapping is up to the parser. 
 *
 * @param $op
 *   Operation to perform.
 *   Value of $op is one of 'describe', 'list' or 'map'.
 * @param $node
 *   Drupal node object.
 * @param $field_name
 *   Parameter only present on $op = 'map'
 *   Name of the field to map to.
 * @param $feed_element
 *   Parameter only present on $op = 'map'
 *   Element of the feed to map from. A simple data type (number, string) or a 
 *   one dimensional array of simple types.
 * @param $sub_field
 *   Parameter only present on $op = 'map'
 *   If given, a subfield on the node to map to.
 *   This parameter will depend on if the hook implementation returns a subfield on 
 *   $op = 'list'. 
 *   
 */
function hook_feedapi_mapper($op, $node, $field_name, $feed_element = array(), $sub_field = '') {
  if ($op == 'describe') {
    return t('Maps a string or an array of strings to myfields.');
  }
  else if ($op == 'list') {
    if ($myfields = myfields_get_available_fields()) {
      return $myfields;
    }
    return FALSE;
  }
  else if ($op == 'map') {
      if ($field_name == 'myfield') {
        if (is_string($feed_element)) {
          $node->myfields = array ($feed_element);
        }
        if (is_array($feed_element)) {
          $node->myfields[$subfield] = $feed_element;
        }
      return $node;
    }
  }
}

/**
 * Implement hook_feedapi_mapper_elements to define standard elements that your
 * configuration should be able to map to.
 * 
 * When FeedAPI Mapper exposes the mapping form on a feed node (node/%/map), it
 * parses the feed and uses the result to make elements of it available as 
 * mapping sources.
 * 
 * However, when FeedAPI mapper exposes the mapping form on a content type
 * form (admin/content/node-type/%/map), there is no feed to parse for 
 * discovering feed elements. In this case FeedAPI Mapper exposes some standard
 * elements. You may find yourself in the need for more specific standard
 * elements on the content type level, in this case implement 
 * hook_feedapi_mapper_elements() in your module.
 * 
 * @see _feedapi_mapper_get_standard_elements().
 * 
 * @return 
 *   An array of arrays that describe the path to a feed element on a feed.
 */
function hook_feedapi_mapper_elements() {
  return array(
    array('options', 'raw', 'headquarter', 'name'),
    array('options', 'raw', 'headquarter', 'city'),
    array('options', 'raw', 'headquarter', 'street'),
    array('options', 'raw', 'headquarter', 'country'),
  );
}

