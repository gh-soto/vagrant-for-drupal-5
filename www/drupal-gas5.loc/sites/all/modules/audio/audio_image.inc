<?php
// $Id: audio_image.inc,v 1.5.2.1 2007/07/20 21:46:44 drewish Exp $

/**
 * Audio Image Functions.
 */

/**
 * Returns an array of all the different image types with "clean" descriptions.
 */
function audio_image_type_clean_array($index = NULL) {
  $ret = array(
    0x00 => 'other',
    0x01 => 'file_icon',
    0x02 => 'other_file_icon',
    0x03 => 'cover_front',
    0x04 => 'cover_back',
    0x05 => 'leaflet',
    0x06 => 'media',
    0x07 => 'lead_artist',
    0x08 => 'artist',
    0x09 => 'conductor',
    0x0A => 'band_orch',
    0x0B => 'comp',
    0x0C => 'lyricist',
    0x0D => 'rec_loc',
    0x0E => 'recording',
    0x0F => 'performance',
    0x10 => 'screen_cap',
    0x11 => 'fish',
    0x12 => 'illust',
    0x13 => 'band_logo',
    0x14 => 'pub_logo'
  );
  return is_null($index) ? $ret : $ret[$index];
}

/**
 * Returns an array of all the different image types with "dirty" descriptions.
 */
function audio_image_type_dirty_array($index = NULL) {
  $ret = array(
    0x00 => t('Other'),
    0x01 => t("32x32 pixels 'file icon' (PNG only)"),
    0x02 => t('Other file icon'),
    0x03 => t('Cover (front)'),
    0x04 => t('Cover (back)'),
    0x05 => t('Leaflet page'),
    0x06 => t('Media (e.g. label side of CD)'),
    0x07 => t('Lead artist/lead performer/soloist'),
    0x08 => t('Artist/performer'),
    0x09 => t('Conductor'),
    0x0A => t('Band/Orchestra'),
    0x0B => t('Composer'),
    0x0C => t('Lyricist/text writer'),
    0x0D => t('Recording Location'),
    0x0E => t('During recording'),
    0x0F => t('During performance'),
    0x10 => t('Movie/video screen capture'),
    0x11 => t('A bright coloured fish'),
    0x12 => t('Illustration'),
    0x13 => t('Band/artist logotype'),
    0x14 => t('Publisher/Studio logotype'),
  );
  return is_null($index) ? $ret : $ret[$index];
}

/**
 * Creates an audio image array from a filepath and pic type.
 * 
 * The image is cropped to a square and then resized to the image size setting.
 * 
 * @param $filepath
 *   Full path to the image file.
 * @param $pictype
 *   Integer pictype indexes from audio_image_type_clean_array() or 
 *   audio_image_type_dirty_array().
 * @return
 *   An array with image info.
 */
function audio_image_from_file($filepath, $pictype) {
  if ($image = image_get_info($filepath)) {
    // Crop to a square.
    $size = min($image['width'], $image['height']);
    if ($image['width'] != $image['height']) {
      image_crop($filepath, $filepath, 0, 0, $size, $size);
    }

    // Resize to the maximum dimension.
    $maxsize = variable_get('audio_image_size', 170);
    if ($size > $maxsize) {
      image_resize($filepath, $filepath, $maxsize, $maxsize);
      $size = $maxsize;
    }

    // Changing the image dimensions will affect the file size. Clear out 
    // PHP's cached value so we can find the new size.
    clearstatcache();

    return array(
      'pictype'   => $pictype,
      'filepath'  => $filepath,
      'filemime'  => $image['mime_type'],
      'filesize'  => filesize($filepath),
      'extension' => $image['extension'],
      'width'     => $size,
      'height'    => $size,
    );
  }
  return FALSE;
}

/**
 * Creates the image's filename in the form directory/prefix_imagetype.ext
 *
 * @param $prefix The prefix of the filename, probably should be the nid.
 * @param $mimetype the image's mime type. jpeg, png and gif are the only
 *   formats allowed.
 * @param $pictype
 * @param $in_tempdir Should this file be in the temp directory
 * @return full filepath or null in case of an error.
 */
function _audio_image_filename($prefix, $mimetype, $pictype = 0x03, $in_tempdir = FALSE) {
  $directory = audio_get_directory() . (($in_tempdir) ? '/temp' : '/images');
  file_check_directory($directory, TRUE);

  //get the clean image type
  $image_type = audio_image_type_clean_array($pictype);

  switch ($mimetype) {
    case 'image/jpeg':
      $ext = '.jpg';
      break;
    case 'image/png':
      $ext = '.png';
      break;
    case 'image/gif':
      $ext = '.gif';
      break;
    default:
      // don't return a filename for any other extensions
      return;
  }

  // combine the path and file extension from the mimetype
  return $directory .'/'. $prefix .'_'. $image_type . $ext;
}
