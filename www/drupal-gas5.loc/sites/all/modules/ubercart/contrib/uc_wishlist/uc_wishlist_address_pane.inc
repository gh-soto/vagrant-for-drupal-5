<?php
// $Id$

/**
 * this file has been hacked from uc_car/uc_cart_checkout_panes.inc
 */

/**
 * Get the delivery information.
 */
function uc_wishlist_address_pane($op, &$arg1, $arg2) {
  global $user;

/*  if (!uc_cart_is_shippable() && variable_get('uc_cart_delivery_not_shippable', TRUE)) {
    return;
  }
*/
  switch ($op) {
    case 'view':
      $description = t('Enter your delivery address and information here.');

      if ($user->uid) {
        $addresses = uc_select_address($user->uid, 'delivery', 'apply_address(\'delivery\', this.value);', t('Saved addresses'), TRUE);
        if (!empty($addresses)) {
          $contents['delivery_address_select'] = $addresses;
        }
      }

      if (uc_address_field_enabled('first_name')) {
        $contents['delivery_first_name'] = uc_textfield(uc_get_field_name('first_name'), $arg1->delivery_first_name, uc_address_field_required('first_name'));
      }
      if (uc_address_field_enabled('last_name')) {
        $contents['delivery_last_name'] = uc_textfield(uc_get_field_name('last_name'), $arg1->delivery_last_name, uc_address_field_required('last_name'));
      }
      if (uc_address_field_enabled('company')) {
        $contents['delivery_company'] = uc_textfield(uc_get_field_name('company'), $arg1->delivery_company, uc_address_field_required('company'), NULL, 64);
      }
      if (uc_address_field_enabled('street1')) {
        $contents['delivery_street1'] = uc_textfield(uc_get_field_name('street1'), $arg1->delivery_street1, uc_address_field_required('street1'), NULL, 64);
      }
      if (uc_address_field_enabled('street2')) {
        $contents['delivery_street2'] = uc_textfield(uc_get_field_name('street2'), $arg1->delivery_street2, uc_address_field_required('street2'), NULL, 64);
      }                                                                                                                        
      if (uc_address_field_enabled('city')) {
        $contents['delivery_city'] = uc_textfield(uc_get_field_name('city'), $arg1->delivery_city, uc_address_field_required('city'));
      }
      if (uc_address_field_enabled('country')) {
        $contents['delivery_country'] = uc_country_select(uc_get_field_name('country'), $arg1->delivery_country, NULL, 'name', uc_address_field_required('country'));
      }
      if (uc_address_field_enabled('zone')) {
        if (isset($_POST['panes']['delivery']['delivery_country'])) {
          $country_id = intval($_POST['panes']['delivery']['delivery_country']);
        }
        else {
          $country_id = $arg1->delivery_country;
        }
        $contents['delivery_zone'] = uc_zone_select(uc_get_field_name('zone'), $arg1->delivery_zone, NULL, $country_id, 'name', uc_address_field_required('zone'));
      }
      if (uc_address_field_enabled('postal_code')) {
        $contents['delivery_postal_code'] = uc_textfield(uc_get_field_name('postal_code'), $arg1->delivery_postal_code, uc_address_field_required('postal_code'), NULL, 10, 10);
      }
      if (uc_address_field_enabled('phone')) {
        $contents['delivery_phone'] = uc_textfield(uc_get_field_name('phone'), $arg1->delivery_phone, uc_address_field_required('phone'), NULL, 32, 16);
      }

      return array('description' => $description, 'contents' => $contents, 'theme' => 'address_pane');

    case 'process':
      $arg1->delivery_first_name = $arg2['delivery_first_name'];
      $arg1->delivery_last_name = $arg2['delivery_last_name'];
      $arg1->delivery_company = $arg2['delivery_company'];
      $arg1->delivery_street1 = $arg2['delivery_street1'];
      $arg1->delivery_street2 = $arg2['delivery_street2'];
      $arg1->delivery_city = $arg2['delivery_city'];
      $arg1->delivery_zone = $arg2['delivery_zone'];
      $arg1->delivery_postal_code = $arg2['delivery_postal_code'];
      $arg1->delivery_country = $arg2['delivery_country'];
      $arg1->delivery_phone = $arg2['delivery_phone'];
      return TRUE;

    case 'review':
      $review[] = array('title' => t('Address'), 'data' => uc_order_address($arg1, 'delivery', FALSE));
      if (uc_address_field_enabled('phone') && !empty($arg1->delivery_phone)) {
        $review[] = array('title' => t('Phone'), 'data' => $arg1->delivery_phone);
      }
      return $review;
  }
}

