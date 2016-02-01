<?php
// $Id: views_audio.inc,v 1.6.2.4 2007/08/29 20:13:33 drewish Exp $

function audio_views_tables() {
  $players = array();
  foreach (audio_get_players('names') as $name => $player) {
    $players[$name] = $player['title'];
  }

  $tables['audio'] = array(
    'name' => 'audio',
    'join' => array(
      'left' => array(
        'table' => 'node',
        'field' => 'vid'
      ),
      'right' => array(
        'field' => 'vid'
      )
    ),
    'fields' => array(
      'play_count' => array(
        'name' => t('Audio: Play count'),
        'sortable' => TRUE,
        'help' => t('This will display the number of times this has been played.'),
      ),
      'download_count' => array(
        'name' => t('Audio: Download count'),
        'sortable' => TRUE,
        'help' => t('This will display the number of times this has been downloaded.'),
      ),
      'playtime' => array(
        'name' => t('Audio: Length'),
        'sortable' => TRUE,
        'help' => t('This will display the play length of the audio.'),
      ),
      'play_link' => array(
        'name' => t('Audio: Player link/flash'),
        'handler' => 'audio_views_handler_field_player',
        'option' => array(
          '#type' => 'select',
          '#options' => $players,
        ),
        'notafield' => TRUE,
        'sortable' => FALSE,
        'help' => t('This will display a flash player/link.'),
      ),
      'download_link' => array(
        'name' => t('Audio: Download link'),
        'handler' => 'audio_views_handler_field_download',
        'notafield' => TRUE,
        'sortable' => FALSE,
        'help' => t('This will display a download link if the node allows it.'),
      ),
    ),
    'filters' => array(
      'downloadable' => array(
        'name' => t('Audio: Downloadable'),
        'operator' => array('=' => 'Equals'),
        'list' => 'views_handler_operator_yesno',
        'list-type' => 'select',
        'help' => t('Filter by whether or not the audio is downloadable. '),
      ),
    ),
    'sorts' => array(
      'play_count' => array(
        'name' => t('Audio: Play count'),
        'help' => t("Sort audio nodes by the number of time they've been played."),
      ),
      'download_count' => array(
        'name' => t('Audio: Download count'),
        'help' => t("Sort audio nodes by the number of time they've been downloaded."),
      ),
    ),
  );

  $tables['audio_file'] = array(
    'name' => 'audio_file',
    'join' => array(
      'left' => array(
        'table' => 'audio',
        'field' => 'vid'
      ),
      'right' => array(
        'field' => 'vid'
      )
    ),
    'fields' => array(
      'filesize' => array(
        'name' => t('Audio: File size'),
        'handler' => 'views_handler_field_filesize',
        'sortable' => TRUE,
        'help' => t("This will display the audio file's size."),
      ),
    ),
  );

  $numeric_tags = array('track', 'year');

  foreach (audio_get_tags_allowed() as $tag) {
    $tables['audio_metadata_'. $tag] = array(
      'name' => 'audio_metadata',
      'join' => array(
        'left' => array(
          'table' => 'audio',
          'field' => 'vid'
        ),
        'right' => array(
          'field' => 'vid'
        ),
        'extra' => array(
          'tag' => $tag
        ),
      ),
      'fields' => array(
        'value' => array(
          'name' => t('Audio: Tag @tag', array('@tag' => $tag)),
          'sortable' => TRUE,
          'help' => t('This will display tag %tag values.', array('%tag' => $tag)),
        ),
      ),
      'filters' => array(
        'clean' => array(
          'name' => t('Audio: Tag @tag', array('@tag' => $tag)),
          'tag' => $tag,
          'operator' => array('=' => 'Equals'),
          'list' => 'audio_views_handler_filter_tags',
          'list-type' => 'list',
          'operator' => 'views_handler_operator_or',
          'value-type' => 'array',
          'help' => t('Filter by whether or not the audio is downloadable. '),
        ),
      ),
      'sorts' => array(
        'value' => array(
          'name' => t('Audio: Tag @tag', array('@tag' => $tag)),
          'help' => t('Sort audio nodes by tag %tag values.', array('%tag' => $tag)),
        ),
      ),
    );

    // Use different handlers for numeric tags.
    if (in_array($tag, $numeric_tags)) {
      $tables["audio_metadata_$tag"]['sorts']['value']['handler'] = 'audio_views_sort_handler_numeric_tag';
      // Set notafield to TRUE so that our handler can add the field.
      $tables["audio_metadata_$tag"]['fields']['value']['notafield'] = TRUE;
      $tables["audio_metadata_$tag"]['fields']['value']['query_handler'] = 'audio_views_field_query_handler_numeric';
    }
  }

  return $tables;
}

// TODO: profile this code and see if it can be optimized.
function audio_views_handler_field_player($fieldinfo, $fielddata, $value, $data) {
  $node = node_load($data->nid);
  return audio_get_node_player($node, $fielddata['options']);
}

// TODO: profile this code and see if it can be optimized.
function audio_views_handler_field_download($fieldinfo, $fielddata, $value, $data) {
  $node = node_load($data->nid);
  if ($node->url_download) {
    return l(t('download audio file'), $node->url_download, NULL);
  }
}

/**
 *
 */
function audio_views_handler_filter_tags($op, $field) {
  $tags = array();
  $result = db_query(db_rewrite_sql("SELECT a.value, a.clean FROM {node} n INNER JOIN {audio_metadata} a ON n.vid = a.vid WHERE a.tag = '%s' ORDER BY a.value ASC"), $field['tag']);
  while ($obj = db_fetch_object($result)) {
    $tags[$obj->clean] = $obj->value;
  }
  return $tags;
}

/**
 * Field sort handler to convert numeric values in string fiels for sorting.
 *
 * For more info on how this works see: http://blog.feedmarker.com/2006/02/01/how-to-do-natural-alpha-numeric-sort-in-mysql/
 */
function audio_views_field_query_handler_numeric($fielddata, $fieldinfo, &$query) {
  // This handler expects that the field will have 'notafield' => TRUE so that
  // we can add in our field and not have worry about views overwriting it with
  // the default.
  $query->add_field($fielddata['field'] .' + 0', $fielddata['tablename'], $fielddata['queryname']);
}

/**
 * Sort handler to convert numeric values in string fiels for sorting.
 *
 * For more info on how this works see: http://blog.feedmarker.com/2006/02/01/how-to-do-natural-alpha-numeric-sort-in-mysql/
 */
function audio_views_sort_handler_numeric_tag($op, &$query, $sortinfo, $sort) {
  // We go to a bunch of trouble here to make sure we're adding the same field
  // as audio_views_field_query_handler_numeric() would so that views doesn't
  // duplicate it.
  $query->add_orderby('', $sort['field'] .' + 0', $sort['sortorder'], $sortinfo['table'] .'_'. $sortinfo['field']);

}
