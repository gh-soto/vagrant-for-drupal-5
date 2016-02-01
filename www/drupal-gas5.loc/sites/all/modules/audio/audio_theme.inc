<?php
// $Id: audio_theme.inc,v 1.5.2.2 2007/07/31 00:27:04 drewish Exp $

/**
 * Format the teaser for an audio node.
 */
function theme_audio_teaser($node){
  // make sure that all the allowed tags are included.
  foreach (audio_get_tags_allowed() as $tag) {
    $params['!'. $tag] = isset($node->audio_tags[$tag]) ? check_plain($node->audio_tags[$tag]) : '';
  }
  $params['!filelength'] = theme('audio_format_filelength', $node->audio_fileinfo);
  $params['!fileformat'] = theme('audio_format_fileformat', $node->audio_fileinfo);
  $params['!player'] = audio_get_node_player($node);
  $params['!play_count'] = check_plain($node->audio_fileinfo['play_count']);
  $params['!download_count'] = check_plain($node->audio_fileinfo['download_count']);

  $format = variable_get('audio_teaser_format', '!player<br />!filelength');

  return t($format, $params);
}

/**
 * Format the audio node for display
 */
function theme_audio_display($node) {
  $items = array();
  if ($player = audio_get_node_player($node)) {
    $items[] = $player;
  }
  foreach (audio_get_tag_settings() as $tag => $setting) {
    if (!$setting['hidden'] && $node->audio_tags[$tag]) {
      $items[] = '<strong>'. t(ucwords(str_replace('_', ' ', $tag))). ':</strong> '
        . theme('audio_format_tag', $tag, $node->audio_tags[$tag], $setting);
    }
  }
  $items[] = '<strong>'. t('Length') .':</strong> '. theme('audio_format_filelength', $node->audio_fileinfo);
  $items[] = '<strong>'. t('Format') .':</strong> '. theme('audio_format_fileformat', $node->audio_fileinfo);

  $output = "<div class='audio-node block'>\n";
  // give audio_image.module (or a theme) a chance to display the images.
  $output .= theme('audio_images', $node->audio_images);
  $output .= theme('item_list', $items, NULL, 'ul', array('class' => 'audio-info'));
  $output .= "</div>\n";

  return $output ;
}

/**
 * Format a metadata tag.
 */
function theme_audio_format_tag($tag, $value, $setting) {
  if ($setting['browsable']) {
    return l($value, "audio/by/$tag/". audio_clean_tag($value));
  }
  else {
    switch ($tag) {
      // url tags
      case 'url_file':
      case 'url_artist':
      case 'url_source':
      case 'url_station':
        return '<a href="'. check_url($value) .'" title="'. $tag .'">'. check_plain($value) .'</a>';

      default:
        return check_plain($value);
    }
  }
}

/**
 * Return a string describing the node's file size and play time.
 *
 * @param $fileinfo
 *   Audio node's audio_fileinfo array.
 */
function theme_audio_format_filelength($fileinfo) {
  $format = !empty($fileinfo['playtime']) ? '@playtime minutes (@filesize)' : '@filesize';
  $args = array('@playtime' => check_plain($fileinfo['playtime']), '@filesize' => format_size($fileinfo['filesize'])); 
  return strtr($format, $args);
}

/**
 * Return a string describing the node's file information.
 *
 * @param $fileinfo
 *   Audio node's audio_fileinfo array.
 */
function theme_audio_format_fileformat($fileinfo) {
  $format = '';
  $args = array();
  if (!empty($fileinfo['file_format'])) {
    $format .= '@fileformat ';
    $args['@fileformat'] = check_plain(strtoupper($fileinfo['fileformat']));
  }
  if (!empty($fileinfo['channel_mode'])) {
    $format .= '@channel_mode ';
    $args['@channel_mode'] = check_plain(ucfirst($fileinfo['channel_mode']));
  }
  if (!empty($fileinfo['sample_rate'])) {
    $format .= '@sampleratekHz ';
    $args['@samplerate'] = (integer) ($fileinfo['sample_rate'] / 1000);
  }
  if (!empty($fileinfo['bitrate'])) {
    $format .= '@bitrateKbps ';
    $args['@bitrate'] = (integer) ($fileinfo['bitrate'] / 1000);

    if (!empty($fileinfo['bitrate_mode'])) {
      $format .= '(@bitrate_mode)';
      $args['@bitrate_mode'] = check_plain(strtoupper($fileinfo['bitrate_mode']));
    }
  }
  return strtr($format, $args);
}

