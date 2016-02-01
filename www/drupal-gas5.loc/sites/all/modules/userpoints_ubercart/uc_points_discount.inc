<?php

/**
 * Implementation of hook_checkout_pane
 */
function userpoints_ubercart_checkout_pane() {
  global $user;
  $curUserId	 = $user->uid;
  $curUserPoints = (int)userpoints_get_current_points($uid = $curUserId, $tid = NULL);
  $discMethod	 = variable_get(USERPOINTS_DISCOUNT,0);
    
  if ($discMethod != 0 && ($curUserPoints > 0 || request_uri() != '/cart/checkout')) {
    $panes[] = array(
      'id' => 'up_uc_discounts',
      'title' => t('!Points Discounts', userpoints_translation()),
      'desc' => t('Show !Points discounts for the order', userpoints_translation()),
      'callback' => 'uc_checkout_pane_ucpoints',
      'weight' => 7,
    );
    return $panes;
  }
}


function uc_checkout_pane_ucpoints($op, &$arg1, $arg2) {
  // get point discounts to apply to this order
  global $user;
  $curUserId	 = $user->uid;

  switch ($op) {
    case 'view':
		$curUserPoints = (int)userpoints_get_current_points($uid = $curUserId, $tid = NULL);
		$maxdisc = round($curUserPoints / (int)variable_get(USERPOINTS_UC_SPEND, 1));
		
		$description = t('Enter the ammount to discount this order with !Points by (Total available via !Points: ' . uc_currency_format($maxdisc) . ')  This field will be ignored if paying with !Points.', userpoints_translation());
        $contents['ptamt'] = array(
          '#type' => 'textfield',
          '#title' => t('Discount Ammount'),
          '#description' => t(''),
          '#default_value' => $arg1->ptdiscount['amt'],
        );
		
		return array('description' => $description, 'contents' => $contents);
		
    case 'process':
		$arg1->ptdiscount['amt'] = $arg2['ptamt'];
		return TRUE;
  }
}


/*
 *  Hook Order Funtions
 *  Called from uc_points_payment.inc hook_order
 */
 
function ptdiscount_total($op, &$arg1, $arg2) {
	if ($arg1->payment_method != 'points') {
		$result = db_query("SELECT * FROM {uc_updiscounts} WHERE oid = %d", $arg1->order_id);
		if ($data = db_fetch_object($result)) {
			return -$data->ptamt;
		}
	}
	else
	{
		return;
	}
}

function ptdiscount_load($op, &$arg1, $arg2) {
	$arg1->ptdiscount['amt'] = '';
	$result = db_query("SELECT * FROM {uc_updiscounts} WHERE oid = %d", $arg1->order_id);
	if ($data = db_fetch_object($result)) {
		$arg1->ptdiscount['amt'] = $data->ptamt;
	}
}

function ptdiscount_save($op, &$arg1, $arg2) {
	global $user;
	$curUserId = $user->uid;
	$ptamt = $arg1->ptdiscount['amt'];

	db_query("DELETE FROM {uc_order_line_items} WHERE order_id=%d AND type='ptdiscount'", $arg1->order_id);

	if ((!empty($ptamt) || $ptamt != '') && $arg1->payment_method != 'points') {
		db_query('INSERT INTO {uc_updiscounts} (uid, oid, ptamt) VALUES (%d, %d, \'%f\')', $curUserId, $arg1->order_id, $ptamt);
		uc_order_line_item_add($arg1->order_id, 'ptdiscount', t('Discount order using !Points',userpoints_translation()), -$ptamt, 1);
	}
}

function ptdiscount_submit($op, &$arg1, $arg2) {
	global $user;
	$curUserId = $user->uid;
	$ptamt = $arg1->ptdiscount['amt'];

	userpoints_ubercart_discount_insert($arg1);
}

function ptdiscount_delete($op, $arg1, $arg2) {
	userpoints_ubercart_discount_delete($arg1);
	db_query("DELETE FROM {uc_order_line_items} WHERE order_id=%d AND type='ptdiscount'", $arg1->order_id);
}

function userpoints_ubercart_discount_insert($order) {
  global $user;
  $curUserId	= $user->uid;
  $multiplier 	= (int)variable_get(USERPOINTS_UC_SPEND, 0);
  $ptamt = $order->ptdiscount['amt'];

  // Payment completed
  if ($curUserId != 0) {
  // User id from the transaction
	  $points = (int)(round($ptamt) * $multiplier);
	  $points = -$points;
	  $params = array (
		'tid' => 0,
		'uid' => $curUserId,
		'points' => $points,
		'operation' => 'delete',
		'description' => 'User Discount, taking poings (Ubercart Order ID ' . $order->order_id . ')',
		'entity_id' => $order->order_id,
		'entity_type' => 'Ubercart Transaction'
	  );
	  userpoints_userpointsapi($params);
	  db_query("insert into {uc_uplog} (uid, oid, points, referrer, discount) values(%d, %d, %d, 0, 1)",$curUserId, $order->order_id, $points);
  }
}

function userpoints_ubercart_discount_delete($order) {
  global $user;
  $curUserId	= $user->uid;
  $oid			= $order->order_id;
  $result 		= db_query("select uplog_id, points from {uc_uplog} where oid = %d and uid = %d and referrer = 0 and discount =1", $oid, $curUserId);

  if ($result != FALSE) {
    $pointinfo = db_fetch_object($result);
    $points = (int)(($pointinfo->points) * ((int)(-1)));
    $description = 'User Discount (Ubercart Order ' . $oid . ') that has been deleted.';
   
	 $params = array (
		'tid' => 0,
		'uid' => $curUserId,
		'points' => $points,
		'operation' => 'delete',
		'description' => $description,
		'entity_id' => $oid,
		'entity_type' => 'Ubercart Transaction'
	 );

	 userpoints_userpointsapi($params);
	 db_query("delete from {uc_uplog} where uplog_id = %d", $pointinfo->uplog_id);
  }
}
?>