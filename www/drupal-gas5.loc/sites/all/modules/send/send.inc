<?php /* $Id: send.inc,v 1.9.2.6 2008/03/03 03:34:17 vauxia Exp $ */

/*
 * All-purpose settings form
 */ 
function _send_settings_form($module, $nodetype='', $node=null) {
  $type = $nodetype ? "_$nodetype" : "";
  
  $form["{$module}{$type}_fulltext"] = array (
    '#type'          => 'checkbox',
    '#title'         => t('Allow visitors to send full item text'),
    '#default_value' => _send_value('fulltext', $module, $nodetype, $node),
    '#description'   => t("Send the full body of the node via email.  If this setting is not enabled, messages will only contain a teaser view."),
  );
  $form["{$module}{$type}_linktext"] = array(
    '#type'          => 'textfield',
    '#title'         => t("Link text"),
    '#default_value' => _send_value('linktext', $module, $nodetype, $node),
    '#size'          => 30,
    '#maxsize'       => 100,
    '#description'   => t('The text for the link to the email form. Use <em>&lt;none&gt;</em> to hide this link.'),
  );
  $form["{$module}{$type}_subject"] = array(
    '#type'          => 'textfield',
    '#title'         => t("E-mail subject line"),
    '#default_value' => _send_value('subject', $module, $nodetype, $node),
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  $form["{$module}{$type}_message"] = array(
    '#type'          => 'textarea',
    '#title'         => t("Default message text"),
    '#default_value' => _send_value('message', $module, $nodetype, $node),
    '#cols'          => 70,
    '#rows'          => 10,
  );
  $form["{$module}{$type}_template"] = array (
    '#type'          => 'textarea',
    '#title'         => t('Email Template'),
    '#default_value' => _send_value('template', $module, $nodetype, $node),
    '#rows'          => 30,
    '#cols'          => 70,
    '#description'   => t("This template controls the appearance of HTML messages.  You can use the tokens %body and %message to place message content and include additional HTML, images and styles to format messages."),
  );
  if ($module_settings = module_invoke($module,'send', 'settings', $module)) {
    $form = array_merge($form, $module_settings);
  }
  return $form;
}

/**
 * module-specific settings
 */
function _send_module_form($module='default') {
  $form = _send_settings_form($module);
  $form['#submit']['_send_settings_submit'] = array();

  $form["{$module}_threshold"] = array (
    '#type'          => 'textfield',
    '#title'         => t('Send Threshold'),
    '#description'   => t('The maximum number of times per hour a user can send a message.  0 for unlimited'),
    '#default_value' => _send_value('threshold', $module),
    '#size'          => 3,
    '#maxlength'     => 3,
    '#weight'        => -9,
  );
  
  $form['module'] = array('#type'=>'value', '#value'=>$module);
  $form['submit'] = array('#type'=>'submit', '#value'=>t('Save configuration'));
  
  return $form;
}

/**
 * nodetype-specific settings
 */
function _send_nodetype_form($form) {
  $form['#submit']['_send_settings_submit'] = array();
  $form['send'] = array();

  if ($type = $form['#node_type']->type) {
    $form['send']['send_type'] = array( '#type' => 'value', '#value' => $type);
  }

  // goofy hack to get the buttons at the end of the array :(
  $form['submit']['#weight'] += 1;
  $form['delete']['#weight'] += 1;
      
  foreach(send_modules() as $module => $name) {
    $module_name = module_invoke($module, 'send', 'name', $module);
    $form['send'][$module] = _send_settings_form($module, $type);
    
    $form['send'][$module]['#title'] = $module_name;
    $form['send'][$module]['#type'] = 'fieldset';
    $form['send'][$module]['#collapsible'] = true;
    $form['send'][$module]['#collapsed']   = true;
    
    // enable this send type
    $form['send'][$module]["{$module}_{$type}"] = array(
      '#type'          => 'checkbox',
      '#weight'        => -10,
      '#title'         => t('Enable "%name" functionality', array('%name' => $module_name)),
      '#default_value' => variable_get("{$module}_{$type}",0),
      '#description'   => t("Adds a link to a form that generates an HTML-formatted e-mail from your site's content."),
    );
    
    // allow per-node overrides
    $form['send'][$module]["{$module}_{$type}_pernode"] = array(
      '#type'          => 'checkbox',
      '#weight'        => -9,
      '#title'         => t('Permit changes on every post'),
      '#default_value' => _send_value('pernode', $module, $type),
      '#description'   => t("Show this settings form on all nodes of this type, permitting you to cusomize the link text, template and other settings for each node."),
    );
  }
  return $form;
}

/**
 * node-specific settings
 */
function _send_node_form($form) {
  $type = $form['type']['#value']; 
  $node = $form['#node'];

  $form['send'] = array('#weight' => 50);

  foreach (send_modules() as $module => $name) {
    if (!variable_get("{$module}_{$type}", 0)) continue;
    if (!variable_get("{$module}_{$type}_pernode", 0)) continue;

    $form['send']['send_type'] = array('#type' => 'value', '#value' => $type);
    $form['send'][$module] = _send_settings_form($module, $type, $node);
    $form['send'][$module]['#title'] = module_invoke($module, 'send', 'name', $module);
    $form['send'][$module]['#type'] = 'fieldset';
    $form['send'][$module]['#collapsible'] = true;
    $form['send'][$module]['#collapsed']   = true;
  }
  return $form;
}

/*

function _send_node_save(&$node) {
  foreach(send_modules() as $m => $name) {
    if (!variable_get("{$m}_{$node->type}_pernode", 0)) continue;
    
    foreach ($node as $key => $value) {
      $name = str_replace("{$m}_{$node->type}_", '', $key);
      if ($name != $key) {
        _send_value($name, $m, $node->type, $node, $value);
      }
    }
  }
}
*/
  
function _send_settings_submit($form_id, $values) {
  /* We don't want to junk up the variable namespace with our module settings 
     since we're relying on our own cascading variable storage.
     Import the variables into our storage handler and then delete them
  */

  $type = isset($values['send_type']) ? $values['send_type'] : null;
  $node = isset($values['nid'])  ? (object)$values : null;

  foreach (send_modules() as $module => $name) {

    $namespace = "{$module}_";

    if ($type) {
      $namespace .= "{$type}_";

      if (!$node) {
        db_query("DELETE FROM {variable} WHERE name LIKE '%s_%%_%s'", $module, $type);
        // variables to keep in $conf
        foreach (array('linktext', 'pernode') as $var) {
          $varname = $namespace.$var;
          variable_set($varname, $values[$varname]);
        }
        $varname = $module.'_'.$type;
        variable_set($varname, $values[$varname]);
      }

    }

    foreach ($values as $varname => $value) {
      $name = str_replace($namespace, '', $varname);
      if ($name != $varname) {
        _send_value($name, $module, $type, $node, $value);
      }
    }
  }
}

function _send_page($module='send', $nids=array(), $mode='teaser') {

  $modules = send_modules();
  if (!isset($modules[$module])) return drupal_not_found();
  
  drupal_set_title(module_invoke($module, 'send', 'name', $module));
  
  // node id's from url path
  if($nids && !is_array($nids)) {
    $nids = is_numeric($nids) ? array($nids) : explode(' ', $nids);
  }
  if (!count($nids)) {
    return module_invoke($module, 'send', 'default action', $module);
  }
 
  // basic security checks
  foreach($nids as $n) {
    if (!is_numeric($n) || !node_access('view', $node = node_load($n))) { 
      drupal_set_message(t('There was a problem loading this item'),'error');
      drupal_goto();
    }
    elseif (!variable_get("{$module}_{$node->type}",0)) {
      drupal_set_message(t('Unable to send this item'),'error');
      drupal_goto();
    }
  }
  
  // check against threshold
  $threshold = _send_value('threshold', $module);
  if ($threshold && !flood_is_allowed("send $module", $threshold)) {
    drupal_set_message(t('You have exceeded the limit for sending items'), 'error');
    drupal_goto();
  }
  
  return drupal_get_form('_send_form', $module, $nids);
}

function _send_form($module, $nids=array()) {
  if ($form = module_invoke($module, 'send', 'form', $module)) {
    return $form;
  }
  
  global $user;
  
  $sender = array('first_name' => '', 'last_name'=> '');
  _send_crm_contact($user->mail, $sender);
  
  $mode = 'teaser';
  $node = count($nids) ? node_load($nids[0]) : null;
  $type = $node->type;

  if (count($nids) == 1) {
    if (_send_value('fulltext', $module, $type, $node)) $mode = 'body';
  }
  
  // recipient(s), from history if it's available
  $previous = _send_previous_recipients($user);
  
  $form = array(
    '#theme'         => 'send_form',
    '#module'        => $module,
  ); 
  $form['from'] = array();
  $form['from']['sender_mail'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Email'),
    '#default_value' => $user->mail,
    '#required'      => true,
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  $form['from']['sender_first_name'] = array(
    '#type'          => 'textfield',
    '#title'         => t('First Name'),
    '#default_value' => $sender['first_name'],
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  $form['from']['sender_last_name'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Last Name'),
    '#default_value' => $sender['last_name'],
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  $form['from']['sender_cc'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Send a copy to myself'),
    '#default_value' => 0,
  );
  $form['to'] = array();
  $form['to']['recipient_mail'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Email'),
    '#required'      => true,
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  $form['to']['recipient_first_name'] = array(
    '#type'          => 'textfield',
    '#title'         => t('First Name'),
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  $form['to']['recipient_last_name'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Last Name'),
    '#size'          => 30,
    '#maxsize'       => 100,
  );
  
  $subject = _send_value('subject', $module, $type, $node);
  $form['subject'] = array();
  $form['subject']['subject_view'] = array(
    '#type'          => 'markup',
    '#value'         => $subject,
  );
  $form['subject']['subject'] = array(
    '#type'          => 'value',
    '#title'         => t('E-mail Subject Line'),
    '#value'         => $subject,
  );
  
  $template = _send_value('template', $module, $type, $node);
  $form['template'] = array();
  $form['template']['template_view'] = array(
    '#type' => 'markup', '#value' => $template,
  );
  $form['template']['template'] = array(
    '#type' => 'value', '#value' => $template,
  );
  
  $body = _send_body('', $nids, $module, $mode);
  $message = _send_value('message', $module, $type, $node);
  $form['message'] = array();
  $form['message']['message'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Message Text'),
    '#cols'          => 70,
    '#rows'          => $body ? 10 : 30,  // longer if no body
    '#default_value' => $message,
  );
  
  $form['body'] = array();
  $form['body']['node_body_view'] = array(
    '#type'          => 'markup',
    '#value'         => $body,
  );
  $form['body']['node_body'] = array(
    '#type'          => 'value',
    '#value'         => $body,
    '#cols'          => 70,
    '#rows'          => 30,
  );
  
  $form['extra']['nids']   = array( '#type' => 'value', '#value' => $nids);
  $form['extra']['mode']   = array( '#type' => 'value', '#value' => $mode, );
  $form['extra']['module'] = array( '#type' => 'value', '#value' => $module, );

  // These values will be computed during the validate phase
  $form['values'] = array(
    'body'   => array('#type' => 'value', '#value' => ''),
    'sender' => array('#type' => 'value', '#value' => ''),
    'sid'    => array('#type' => 'value', '#value' => ''),
  );
  
  $form['buttons'] = array();
  $form['buttons']['submit'] = array(
    '#type' => 'submit', '#value' => t('Send'),
  );
  return $form;
}

function _send_form_prepare($values, $form) {
  $module  = $values['module'];
  $mail    = $values['sender_mail'];
  $first_name = $values['sender_first_name'];
  $last_name  = $values['sender_last_name'];
  ($name = trim($first_name.' '.$last_name)) || ($name = $values['sender_name']);

  // Create a $sender array for delivery functions
  $sender = array('mail' => $mail, 'name' => $name);
  if (!$sender['name']) $sender['name'] = $user->name;
  form_set_value($form['values']['sender'], $sender);
  
  // Fetch a themed body
  if (!$body = theme("{$module}_body", $values)) {
    $body = theme('send_body', $values);
  }
  form_set_value($form['values']['body'], $body);
  
}

function _send_form_validate($form_id, $values, $form) {
  $ret = true;
  _send_form_prepare($values, $form);

  if (!valid_email_address($values['sender_mail'])) {
    form_set_error('sender_mail',t('Invalid from address'));
    $ret = false;
  }
    
  if(isset($values['recipient_mail']) && !valid_email_address($values['recipient_mail'])) {
    form_set_error('recipient_mail',t('Invalid recipient address'));
    $ret = false;
  }
  return $ret;
}

function _send_form_submit($form_id, $values) {
  global $user;
    
  $module  = $values['module'];
  $uid     = $user->uid;
  $message = $values['message'];
  $subject = $values['subject'];
  $sender  = $values['sender'];

  // Generate a send id.  This is needed early on for other tracking
  if (!$values['sid']) {
    $values['sid'] = db_next_id("{send}_sid");
  }
  
  // log send action
  db_query("INSERT INTO {send} 
    ( sid ,module ,uid ,mail ,timestamp ,message, subject, name ) 
    VALUES ( %d ,'%s' ,%d ,'%s' ,%d ,'%s', '%s', '%s')"
    , $values['sid'], $module, $uid, $sender['mail'], time(), $message, $subject, $sender['name']);
  
  // log a CRM activity
  if ($contact = _send_crm_contact($mail, $values, $user, 'sender')) {
    $activity = array(
      'entity_table'    => 'civicrm_contact',
      'entity_id'       => $contact->id,
      'module'          => 'send',
      'activity_type'   => t('Sent %subject',array('%subject' => $subject)),
      'activity_date'   => date('YmdHis',time()),
      'activity_id'     => $values['sid'],
    );
    crm_create_activity_history($activity);
  }
  
  // get delivery callback
  if(!$deliver = module_invoke($module, 'send', 'deliver', $module)) {
    $deliver = module_invoke('send', 'send', 'deliver','send');
  }
  
  $recipients = array();
  // recipients are from a space/line-delimited textarea
  if (isset($values['recipients'])) {
    if (!is_array($recipients = $values['recipients'])) {
      $recipients = array();
      foreach (explode(',', preg_replace(array("/\s/ms","/,+/"), ',',$values['recipients'])) as $r) {
        if (valid_email_address($r)) {
          $recipients[] = array('mail' => $r);
        }
        else {
          $recipients[] = $r;
        }
      }
    }
  }
  
  // recipients expanded into separate form fields
  // e.g. recipient1_first_name, recipient2_mail,  and so-on
  if(!count($recipients)) {
    foreach ($values as $key => $value) {
      if ($key != ($name = preg_replace('/^recipient(\d*)_/', '\1 ', $key))) {
        list($i, $name) = explode(' ', $name);
        if (!$i) $i = 1;
        $i--;
        if (!isset($recipients[$i])) $recipients[$i] = array();
        $recipients[$i][$name] = $value;
      }
    }
  }

  // recipient type - for now, 'node' or 'user'
  ($rtype = module_invoke($module, 'send', 'recipient type', $module))
    || ($rtype = module_invoke('send', 'send', 'recipient type', $module));
  
  $ret = true;
  foreach ($recipients as $r) {
    $rid = 0;

    switch ($rtype) {
      case 'user':
        // try to log message as a crm activity for recipient
        if (is_array($r) && isset($r['mail'])) {
          $recipient_user = user_load(array('mail'=>$r['mail']));
          if ($contact = _send_crm_contact($r['mail'], $r, $recipient_user)) {
            crm_create_activity_history($arr = array_merge($activity, array(
              'entity_id' => $contact->id, 
              'activity_type' => t('Received %subject', array('%subject' => $subject)),
            )));
          }
          $rid = $recipient_user->uid;
      
          if (!$r['name']) {
            $r['name'] = trim($r['first_name'].' '.$r['last_name']);
            if (!$r['name']) $r['name'] = $recipient_user->name;
          }
          $r_name = $r['name'];
          $r_mail = $r['mail'];
        }
        break;
      case 'node':
        // when the type is "node" each recipient is a nid
        $rid    = $r;
        $r      = node_load($rid);
        $r_name = $r->title;
        $r_mail = '';
        break;
    }

    // Add querystrings to urls in the message body
    $qs = array();
    foreach (send_modules() as $m => $name) {
      if($callback = module_invoke($m, 'send', 'querystring', $module)) {
        $qs = array_merge($qs, call_user_func($callback, $sender, $r, $values));
      }
    }
      
    if (count($qs)) {
      foreach ($qs as $n => $v) $qs[$n] = urlencode($n).'='.urlencode($v);
      $qs = join('&', $qs);
      $body = preg_replace('/(<a[^>]+href=")([^"]*)/emi', '"\\1"._send_qs("\2","'.$qs.'")', $body);
    }
      
    // call the delivery callback
    $ret = (($count = call_user_func($deliver, $sender, $r, $values)) !== false ) 
      && $ret;

    // log the recipient if there are no nodes in the message
    if (!count($values['nids'])) {
      db_query("INSERT INTO {send_recipient} 
          ( sid ,nid ,rid ,rtype  ,mail, name, rcount ) 
          VALUES ( %d ,%d ,%d ,'%s', '%s', '%s', %d )",
        $values['sid'], 0, $rid, $rtype, $r_mail, $r_name, $count );
    }
    // log each node/user combination 
    foreach ($values['nids'] as $nid) {
      db_query("INSERT INTO {send_recipient} 
          ( sid ,nid ,rid ,rtype  ,mail, name, rcount ) 
          VALUES ( %d ,%d ,%d ,'%s', '%s', '%s', %d )",
        $values['sid'], $nid, $rid, $rtype, $r_mail, $r_name, $count );
    }
    
  }
  if ($ret) {
    flood_register_event("send $module");
        
    if ($values['sender_cc']) {
      $self = $user->uid ? $user : $sender; //if it's a registered user, use their "real" address

      mimemail($sender, $self, $subject, $values['body']);
      drupal_set_message(t('A copy of this message has also been sent to %mail',array('%mail'=>$values['sender_mail'])));
    }
  }
  
  // get confirmation callback
  if($callback = module_invoke($module, 'send', 'confirm', $module)) {
    return call_user_func($callback, $values);
  }
  return _send_confirm($values);
}
      
function _send_deliver($sender, $recipient, $values) {
  if (mimemail($sender, $recipient, $values['subject'], $values['body'])) {
    drupal_set_message(t('Message sent to %mail',array('%mail'=>$recipient['mail'])));
  }
  // return the count of recipients
  if (is_object($recipient) || isset($recipient['mail'])) return 1;
  return count($recipient);
}

function _send_confirm($values) {
  if (count($nids = $values['nids']) == 1) {
    drupal_goto('node/'.$nids[0]);
  }
  
  $out = '<h2>'.$values['subject'].'</h2>';
  $out .= $body;
  echo theme('page',$out);
  exit;
}

/**
 * build the body text
 */
function _send_body($message, $nids, $module, $mode) {
  $modules = send_modules();
  
  $body = '';
  
  foreach($nids as $nid) { 
    $node = node_load($nid);
    switch ($mode) {
      
      case 'title' :
        $body .= $node->title.'<br />';
        break;
        
      case 'teaser' :
        $text = node_view($node, true, false, false);
        $body .= _send_hook_modify($text, 'node', $module, $node->type, $node);
        break;
        
      case 'body' :
        $text = node_view($node, false, false, false);
        $body .= _send_hook_modify($text, 'node', $module, $node->type, $node);
        break;
    }
  }
  
  // let modules prepend / append stuff to the body, or otherwise modify it
  $body = _send_hook_modify($body, 'node', $module, $node->type, $node);
  return $body;
}

// callback for querystring preg
function _send_qs($url, $qs) {
  return $url . (strpos($url, '?') !== false ? '&' : '?') . $qs;
}

function _send_hook_modify($text, $op, $module, $nodetype='', $node=null) {
  $modules = send_modules();
  foreach ($modules as $m => $name) {
    $callback = module_invoke($m, 'send', $op, $nodetype, $node);
    if ($callback && !is_null($altered = call_user_func($callback, $text))) {
      $text = $altered;
    }
  }
  return $text;
}

/**
 * Set configuration variables for send
 */
function _send_value($name, $module='', $nodetype = '', $node = null, $value = null) {
  $nid = is_object($node) ? $node->nid : 0;
  $pernode = variable_get("{$module}_{$nodetype}_pernode", 0);
  
  // set a new value
  if (!is_null($value)) {
    if ($nid && !$pernode) {
      return;               // trying to set a per-node value but can't
    }

    db_query("DELETE FROM {send_setting} 
      WHERE name='%s' AND module='%s' AND nodetype='%s' AND nid=%d"
        , $name, $module, $nodetype, $nid);

    /* We override in this order: module, nodetype, node
       don't set this value if it's the same as the one above it in the stack
    */ 
    if ($nid) {
      // this node has the same value as its nodetype
      if ($value == _send_value($name, $module, $nodetype)) return;
    } 

    elseif ($nodetype) {
      // this nodetype has the same value as its module
      if ($value == _send_value($name, $module)) return;

      // for future consistency, delete children that would inherit this value
      db_query("DELETE FROM {send_setting} 
        WHERE name='%s' AND module='%s' AND nodetype='%s' AND value='%s'"
          , $name, $module, $nodetype, $value);
    }

    elseif ($module) {
      // this module has the same value as the default
      if ($value == _send_value($name)) return;

      // for future consistency, delete children that would inherit this value
      db_query("DELETE FROM {send_setting} 
        WHERE name='%s' AND module='%s' AND value='%s'"
          , $name, $module, $value);
    }
    
    // save the value
    db_query("INSERT INTO {send_setting} ( name, module, nodetype, nid, value )
        VALUES ('%s', '%s', '%s', %d, '%s')", $name, $module, $nodetype, $nid, $value);
    return;
  }
  
  // try getting value from variables to save resources
  if (!$pernode && $val = variable_get("{$module}_{$nodetype}_{$name}", FALSE)) {
    return $val;
  }
  
  // get value from database
  if ($val = db_result(db_query("SELECT value FROM {send_setting} 
      WHERE name='%s' AND 
      nid IN (0, %d) AND nodetype IN ('', '%s') AND module IN ('', '%s')
      ORDER BY (nid = 0), (nodetype = ''), (module = '')
      LIMIT 1", $name, $nid, $nodetype, $module))) {
      
    return $val;
  }
  
  // return module's default value
  if ($val = module_invoke($module, 'send', $name, $module, $nodetype, $node)) {
    return $val;
  }
  
  // return send's default value
  if ($val = module_invoke('send', 'send', $name, $module, $nodetype, $node)) {
    return $val;
  }
  return;
}

/**
 * load and/or update contact info in CiviCRM
 * 
 * $params can contain name/value pairs for civicrm's data fields, optionally
 * prepended by "{$scope}_".  The contact record will be created and/or filled
 * with the data from $params, and $params will similarly be filled with CRM data
 * for use by the rest of this module.
 */
function _send_crm_contact($mail, &$params, $user=null, $scope='') {
  if (!module_exists('civicrm')) return false;
  if ($scope) $scope .= '_';
  
  civicrm_initialize(true);
  
  // fields allowed in the contact record
  $contact_fields = array('first_name', 'last_name', 'email', 'source', 'prefix', 'suffix',
    'display_name', 'legal_identifier', 'external_identifier', 'job_title', 'gender', 
    'birth_date', 'is_deceased', 'preferred_communication_method', 'do_not_phone', 
    'do_not_email', 'do_not_mail', 'home_URL', 'image_URL', 'street_address' );
  
  // create an array of CRM data from $params.  Strip out the part of 
  // the variable that conveys scope (sender_ , recipient_) if you have to
  $record = array();
  foreach ($params as $key => $value) {
    if (!in_array($name = str_replace($scope, '', $key), $contact_fields)) continue;
    $record[$name] = $params[$key];
  }
  
  // find contact
  $search = array();
  if ($user && $id = crm_uf_get_match_id($user->uid)) {
    $search['id'] = $id;
  }
  elseif ($mail) {
    $search['email'] = $mail;
  }
  else {
    return;                      // we don't have enough to work with here
  }
  if ($res = crm_contact_search($search)) $contact = current($res[0]);
  
  if($contact['contact_id']) {
    $updates = array();
    foreach ($record as $key => $value) {
      // populate contact info if it's not there already
      if (!isset($contact[$key]) && $value) {
        $updates[$key] = $value;
      }
      // update our referenced array with CRM info
      else {
        $record[$key] = $contact[$key];
      }
    }
    $contact = crm_get_contact($contact);
    
    if (count($updates)) {
      $contact = crm_update_contact($contact, $updates);
    }
  }
  else {
    // no contact.  let's create one
    $contact = array_merge(array('email' => $mail), $record);
    $contact = crm_create_contact($contact);
  }
  
  // save data to the referenced $params array if it's not already set 
  foreach ($record as $key => $value) {
    if (!$params[$scope.$key])$params[$scope.$key] = $value;
  }

  return $contact;
}

function _send_previous_recipients($user) {
  $recipients = array();
  
  $res = db_query('SELECT r.mail, r.name, MAX(s.timestamp)
      FROM {send} s RIGHT JOIN {send_recipient} r ON r.sid = s.sid
      WHERE s.uid = %d GROUP BY r.mail, r.name, s.timestamp 
      ORDER BY r.mail, s.timestamp ASC',$user->uid);
      
  while ($row = db_fetch_object($res)) {
    $recipients[] = Array('mail' => $row->mail, 
      'name' => $row->name,
    );
  }
  return $recipients;
}

function theme_send_form(&$form) {
  $from    = drupal_render($form['from']);
  $to      = drupal_render($form['to']);
  $buttons = drupal_render($form['buttons']);
  $subject = drupal_render($form['subject']);
  $message = drupal_render($form['message']);
  $template= drupal_render($form['template']);
  $body    = drupal_render($form['body']);
  $extra   = drupal_render($form);
  
  $replace = array('%message', '%body');
  $values  = array($message, $body);
  $content = str_replace($replace, $values, $template);
  
  $headers = theme('table', array(t('From'),t('To')),array(array('data' => array($from, $to), 'valign' => 'top')),array('width'=>'100%'));
  
  return $headers.'<br class="clear" />'.$extra.$buttons.'<br class="clear" />'.$subject.'<br class="clear" />'.$content;
}

function theme_send_template() {
  $template = "%message\n%body\n";
  return $template;
}

function theme_send_body($values) {
  $body = '';
  $template .= $values['template'];
  if ($values['message']) {
    $message = '<div class="messages">'.check_markup($values['message']).'</div>';
  }
  $body = $values['node_body'];
  $replace = array('%message', '%body');
  $values  = array($message, $body);
  
  return str_replace($replace, $values, $template);
}
