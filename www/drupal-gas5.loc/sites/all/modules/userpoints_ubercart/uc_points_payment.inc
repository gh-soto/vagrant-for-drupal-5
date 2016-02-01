<?php

/**
 * Implementation of hook_payment_method().
 */
 
function userpoints_ubercart_payment_method() {
  global $user;
  $curUserId = $user->uid;

  //how many points does this user have?
  $curUserPoints = userpoints_get_current_points($uid = $curUserId, $tid = NULL);
  $maxdisc = round($curUserPoints / (int)variable_get(USERPOINTS_UC_SPEND, 1));

  // make sure they have points in the first place
  if ($curUserPoints > 0 || request_uri() != '/cart/checkout') {
    $methods[]	= array(
      'id'	=> 'points',
      'name'	=> t('!Points', userpoints_translation()),
      'title'	=> t('!Points (Total available via !Points: ' . uc_currency_format($maxdisc) . ')', userpoints_translation()),
      'desc'	=> t('Pay by !Points.', userpoints_translation()),
      'callback'=> 'uc_payment_method_points',
      'weight'	=> 4,
      'checkout'=> TRUE,
      'backend'	=> TRUE,
    );

    return $methods;
  }
}

/**
 * Implementation of hook_order().
 */
 
function userpoints_ubercart_order($op, &$arg1, $arg2) {
  $order 	 = $arg1;
  $paymethod = strtolower($order->payment_method);
  switch ($op) {
    case 'total':
	  if (variable_get(USERPOINTS_DISCOUNT,0) != 0 && strtolower($order->payment_method) != 'points') {
	  	return ptdiscount_total($op, $arg1, $arg2);
	  }
	  break;

    case 'save':
	  if (variable_get(USERPOINTS_DISCOUNT,0) != 0 && strtolower($order->payment_method) != 'points') {
	  	ptdiscount_save($op, $arg1, $arg2);
	  }
	  break;

	case 'submit':
	  // fires when the order is submitted and adds/subtracts thier points
	  if ($paymethod == 'points') {
		userpoints_ubercart_payment($order);
	  }
	  else
	  {
		userpoints_ubercart_insert($order);
		if (variable_get(USERPOINTS_DISCOUNT,0) != 0) {
		  	ptdiscount_submit($op, $arg1, $arg2);
	  	}
	  }
	  break;
	case 'load':
	  //paypal specific hook
  	  ptdiscount_load($op, $arg1, $arg2);

	  if ($paymethod == 'paypal_wps' && strtolower(substr(request_uri(),1,22)) == 'uc_paypal/wps/complete') {
		if (variable_get(USERPOINTS_DISCOUNT,0) != 0) {
		  	ptdiscount_submit($op, $arg1, $arg2);
	  	}
		userpoints_ubercart_insert($order);
	  }
	  
	  break;
	case 'update':
	  // if the order is canceled we need to refund thier points because we are too lazy to do it by hand...
	  if ($arg2 == 'canceled') {
		if (strtolower($order->payment_method) == 'points') {
		  userpoints_ubercart_refund($order);
		}
		else
		{
		  userpoints_ubercart_delete($order);
		}
	  }
	  break;
	case 'delete':	
	  if (variable_get(USERPOINTS_DISCOUNT,0) != 0 && strtolower($order->payment_method) != 'points') {
	  	ptdiscount_delete($op, $arg1, $arg2);
		break;
	  }

	  if (strtolower($order->payment_method) == 'points') {
		userpoints_ubercart_refund($order);
	  }
	  else
	  {
		userpoints_ubercart_delete($order);
	  }
	  break;
	case 'can_delete';
	  return FALSE;
	  break;
  }
}

/*******************************************************************************
 * Callback Functions, Forms, and Tables
 ******************************************************************************/

function uc_payment_method_points($op, &$arg1) {
  global $user;
  $curUserId	 = $user->uid;
  $curUserPoints = (int)userpoints_get_current_points($uid = $curUserId, $tid = NULL);
  $pointExchange = (int)variable_get(USERPOINTS_UC_SPEND, 1);

  switch ($op) {
	case 'cart-process':
	//this would be verification that should be covered else where if possible
	// check thier points and make sure they have enough to pay for this purchase

	//order information
	$order_id 	  = $arg1->order_id;
	$result	  	  = db_query("select order_total from {uc_orders} where order_id = %d", $order_id);
	$item 	  	  = db_fetch_array($result);
	$orderTotal   = $item['order_total'];
	$pointsNeeded = (int)(round($orderTotal) * $pointExchange);


	if ($curUserPoints < $pointsNeeded) {
	  drupal_set_message('You do not have enough points to complete this purchase.  You have ' . $curUserPoints . ' but you need ' . $pointsNeeded . '.  Please select another payment method.', 'error');
	  return FALSE;
	}
	else
	{
	  return TRUE;
	}

  case 'settings':
	// Add a few points related fields to the payment methods settings form.
	$form['userpoints_ubercart_permission'] = array(
	  '#value' => '<div>'. t('Please adjust the settings for using points under the userpoints section <a href=\'/admin/settings/userpoints\'>(click here)</a>.  This area is for enabling points as payment method only.') .'</div>',
	);
	return $form;
  }
}

/*******************************************************************************
 * Functions to work with points
 ******************************************************************************/

function userpoints_ubercart_insert($order) {
  // gives the user points for thier purchase, but checks via sql if they paid with points.  if so they get nothing also dont give points for tax and shipping.  it may fail if the user does not come back to our site after completing the paypal portion before leaving, no errors will be produced except that no points will be given

  global $user;
  $curUserId	= $user->uid;
  $multiplier 	= (int)variable_get(USERPOINTS_UC_EARN, 0);
  $min 			= variable_get(USERPOINTS_MIN_PURCHASE, '0.00');
  $result 		= db_query("select sum(price) from {uc_order_products} p, {uc_orders} o where p.order_id = o.order_id and o.order_id = %d and o.uid = %d", $order->order_id, $curUserId);
  $total		= db_result($result, $row = 0);

  // Payment completed
  if ($curUserId != 0) {
  // User id from the transaction

	if ($total < $min) {
	  $points = 0;
	}
	else
	{
	  $points = (int)(round($total) * $multiplier);
	  $params = array (
		'tid' => 0,
		'uid' => $curUserId,
		'points' => $points,
		'operation' => 'insert',
		'description' => 'User made a purchase, awarding poings (Ubercart Order ID ' . $order->order_id . ')',
		'entity_id' => $order->order_id,
		'entity_type' => 'Ubercart Transaction'
	  );
	  userpoints_userpointsapi($params);
	  db_query("insert into {uc_uplog} (uid, oid, points, referrer) values(%d, %d, %d, 0)",$curUserId, $order->order_id, $points);
	}
  }
}

function userpoints_ubercart_delete($order) {
  global $user;
  $curUserId	= $user->uid;
  $oid			= $order->order_id;
  $result 		= db_query("select uplog_id, points from {uc_uplog} where oid = %d and uid = %d and referrer = 0", $oid, $curUserId);

  if ($result != FALSE) {
    $pointinfo = db_fetch_object($result);
    $points = (int)(($pointinfo->points) * ((int)(-1)));
    $description = 'User made a purchase (Ubercart Order ' . $oid . ') that has been deleted.';
   
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

function userpoints_ubercart_payment($order) {
  global $user;
  $curUserId	= $user->uid;
  $pointExch	= (int)variable_get(USERPOINTS_UC_SPEND, 1);
  $orderTotal	= $order->order_total;
  $points		= (int)((round($orderTotal) * $pointExch) * ((int)(-1)));
  $description	= 'User made a purchase and paid with points (Ubercart Order ' . $order->order_id . ')';

  if ($order === FALSE || uc_order_status_data($order->order_status, 'state') != 'in_checkout') {
	print t('An error has occurred during payment.  Please contact us to ensure your order has submitted.');
	exit();
  }

  if ($order->payment_method == 'points') {  
	$params = array (
	  'tid' => 0,
	  'uid' => $curUserId,
	  'points' => $points,
	  'operation' => 'delete',
	  'description' => $description,
	  'entity_id' => $order->order_id,
	  'entity_type' => 'Ubercart Transaction'
	);
	userpoints_userpointsapi($params);
	uc_payment_enter($order->order_id, 'points', $orderTotal, $curUserId, NULL, $description);
	db_query("insert into {uc_uplog} (uid, oid, points, referrer) values(%d, %d, %d, 0)",$curUserId, $order->order_id, $points);
	uc_order_update_status($order_id, 'payment_received');
  }
}

function userpoints_ubercart_refund($order) {
  global $user;
  $curUserId	= $user->uid;
  $oid 			= $order->order_id;
  $result 		= db_query("select uplog_id, points from {uc_uplog} where oid = %d and uid = %d and referrer = 0", $oid, $curUserId);
  $orderTotal	= $order->order_total;
  
  if ($result != FALSE) {
    $pointinfo = db_fetch_object($result);
    $points = (int)(($pointinfo->points) * ((int)(-1)));
	$description	= 'User purchase (Ubercart Order ' . $order->order_id .') has been canceled.';

	db_query("delete from {uc_payment_receipts} where method = 'Points' and order_id = %d", $oid);
	db_query("delete from {uc_uplog} where uplog_id = %d", $pointinfo->uplog_id);
	
	$params = array (
	  'tid' => 0,
	  'uid' => $curUserId,
	  'points' => $points,
	  'operation' => 'insert',
	  'description' => $description,
  	  'entity_id' => $oid,
	  'entity_type' => 'Ubercart Transaction'
    );
    userpoints_userpointsapi($params);
  }
}

?>