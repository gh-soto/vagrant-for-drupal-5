<?php
/* $Id: send_views.inc,v 1.1.2.4 2008/02/25 05:45:06 vauxia Exp $ */

function _send_views_tables() {
  $tables = array();
  $tables['send_recipient'] = array(
    'join' => array(
      'left' => array(
        'table' => 'node',
        'field' => 'nid',
      ), 
      'right' => array(
        'field' => 'nid'
      ), 
    ),
    'fields' => array(
      'rid' => array(
        'name' => 'Send: Recipient',
        'handler' => '_send_views_handler_field_recipient', 
        'query_handler' => '_send_views_handler_recipient', 
      ),
      'rcount' => array(
        'name' => 'Send: Recipient count',
        'handler' => 'views_handler_field_int',
        'sortable' => true, 
      ),
      'nid' => array(
        'name' => 'Send: Node body',
        'sortable' => false, 
        'handler' => array(
          'views_handler_field_body'   => t('Full Text'),
          'views_handler_field_teaser' => t('Teaser')
        ),
        'help' => t('Display the body of the node sent in a message'),
      ),
    ),
    'filters' => array(
      'uid' => array(
        'name' => 'Send: Recipient',
        'operator' => 'views_handler_operator_or',
        'field' => 'rid',
        'list' => 'views_handler_filter_username', 
        'value-type' => 'array',
      ),
      'currentuid' => array(
        'field' => 'rid',
        'name' => t('Send: Recipient is Current User'),
        'operator' => 'views_handler_operator_eqneq',
        'list' => 'views_handler_filter_usercurrent',
        'list-type' => 'select',
      ),
    ),
    'sorts' => array(
      'rcount' => array(
        'name' => t('Send: Recipient count'),
        'help' => t('Sort results by the number of recipients in each message sent.'),
      )
    ),
  );

  $tables['send'] = array(
    'join' => array(
      'left' => array(
        'table' => 'send_recipient',
        'field' => 'sid',
      ), 
      'right' => array(
        'field' => 'sid'
      ), 
    ),
    'fields' => array(
      'module' => array(
        'name' => 'Send: Module',
        'handler' => '_send_views_handler_field_module',
        'sortable' => true, 
      ),
      'timestamp' => array(
        'name' => t('Send: Timestamp'),
        'sortable' => true,
        'handler' => views_handler_field_dates(),
        'option' => 'string',
      ),
      'uid' => array(
        'name' => 'Send: Sender',
        'handler' => '_send_views_handler_field_sender', 
        'query_handler' => '_send_views_handler_sender', 
        'sortable' => true, 
      ),
      'subject' => array(
        'name' => 'Send: Subject',
        'sortable' => true, 
      ),
      'message' => array(
        'name' => 'Send: Message',
        'sortable' => false, 
      ),
    ),
    'filters' => array(
      'module' => array(
        'name' => 'Send: Module',
        'operator' => 'views_handler_operator_or',
        'list' => 'send_modules', 
        'value-type' => 'array',
      ),
      'timestamp' => array(
        'name' => 'Send: Timestamp',
        'operator' => 'views_handler_operator_gtlt',
        'value' => views_handler_filter_date_value_form(),
        'handler' => 'views_handler_filter_timestamp',
        'option' => 'string',
      ),
      'uid' => array(
        'name' => 'Send: Sender',
        'operator' => 'views_handler_operator_or',
        'list' => 'views_handler_filter_username', 
        'value-type' => 'array',
      ),
    ),
    'sorts' => array(
      'module' => array(
        'name' => t('Send: Module'),
        'help' => t('Sort results by the module used to send the nodes.'),
      ),
      'timestamp' => array(
        'name' => t('Send: Timestamp'),
        'help' => t('Sort results by the times the node was sent.'),
        'handler' => 'views_handler_sort_date',
        'option' => views_handler_sort_date_options(),
      ),
      'uid' => array(
        'name' => t('Send: Sender'),
        'help' => t('Sort results by person who sent items.'),
      ),
    ),
  );
  $tables['send_totals'] = array(
    'name' => 'send_recipient',
    'join' => array(
      'left' => array(
        'table' => 'node',
        'field' => 'nid',
      ), 
      'right' => array(
        'field' => 'nid'
      ), 
    ),
    'fields' => array(
      'count' => array(
        'name' => 'Send: Total times sent',
        'handler' => 'views_handler_field_int',
        'query_handler' => '_send_views_handler_count',
        'sortable' => true, 
        'notafield' => TRUE,
      ),
      'rcount' => array(
        'name' => 'Send: Total recipient count',
        'handler' => 'views_handler_field_int',
        'query_handler' => '_send_views_handler_count',
        'sortable' => true, 
        'notafield' => TRUE,
      ),
    ),
    'sorts' => array(
      'count' => array(
        'name' => t('Send: Total times sent'),
        'help' => t('Sort by the number of times a node has been sent.'),
        'table' => 'send_recipient',
        'handler' => '_send_views_handler_sort_count',
      ),
      'rcount' => array(
        'name' => t('Send: Total recipient count'),
        'help' => t('Sort by total number of recipients.'),
        'table' => 'send_recipient',
        'handler' => '_send_views_handler_sort_count',
      ),
    ),
  );

  return $tables;
}

function _send_views_arguments() {
  return array(
    'send_node' => array(
      'name' => t('Send: Node ID'),
      'handler' => '_send_views_arg_node',
    ),
    'send_module' => array(
      'name' => t('Send: Module'),
      'handler' => '_send_views_arg_module',
    ),
    'send_sender' => array(
      'name' => t('Send: Sender'),
      'handler' => '_send_views_arg_sender',
    ),
    'send_recipient' => array(
      'name' => t('Send: Recipient'),
      'handler' => '_send_views_arg_recipient',
    ),
    'send_sid' => array(
      'name' => t('Send: Message ID'),
      'handler' => '_send_views_arg_sid',
    ),
  );
}

function _send_views_arg_node($op, &$query, $argtype, $arg = null) {
  switch ($op) {
    case 'summary':
      return array('field' => 'send_recipient.nid');

    case 'sort':
      $query->add_orderby('send_recipient', 'nid', $argtype);
      return;

    case 'filter':
      $query->ensure_table('send_recipient');
      $query->add_where('send_recipient.nid = %d', $arg);
      return;

    case 'link':
      $node = node_load($query->send_recipient_nid);
      return l($node->title, $arg .'/'. $node->nid);

    case 'title':
      $node = node_load($query);
      return $node->title;
  }
}

function _send_views_arg_module($op, &$query, $argtype, $arg = null) {
  switch ($op) {
    case 'summary':
      return array('field' => 'send.module');

    case 'sort':
      $query->add_orderby('send', 'module', $argtype);
      return;

    case 'filter':
      $query->ensure_table('send');
      $query->add_where("send.module = '%s'", $arg);
      return;

    case 'link':
      $modules = send_modules();
      return l($modules[$query->send_module], $arg .'/'. $query->send_module);

    case 'title':
      $modules = send_modules();
      return $modules[$query];
  }
}

function _send_views_arg_sender($op, &$query, $argtype, $arg = null) {
  switch ($op) {
    case 'summary':
      return array('field' => 'send.uid');

    case 'sort':
      $query->add_orderby('send', 'uid', $argtype);
      return;

    case 'filter':
      $query->ensure_table('send');
      $query->add_where("send.uid = '%d'", $arg);
      return;

    case 'link':
      $account = user_load(array('uid' => $query->send_uid));
      if (!$account->uid) $account->name = variable_get('anonymous', t('Anonymous'));
      return l($account->name, $arg .'/'. $query->send_uid);

    case 'title':
      $account = user_load(array('uid' => $query));
      if (!$account->uid) $account->name = variable_get('anonymous', t('Anonymous'));
      return $account->name;
  }
}

function _send_views_arg_recipient($op, &$query, $argtype, $arg = null) {
  switch ($op) {
    case 'summary':
      return array('field' => 'send_recipient.rid');

    case 'sort':
      $query->add_orderby('send_recipient', 'rid', $argtype);
      return;

    case 'filter':
      $query->ensure_table('send_recipient');
      $query->add_where("send_recipient.rtype = 'user'");
      $query->add_where("send_recipient.rid = '%d'", $arg);
      return;

    case 'link':
      $account = user_load(array('uid' => $query->send_recipient_rid));
      if (!$account->uid) $account->name = variable_get('anonymous', t('Anonymous'));
      return l($account->name, $arg .'/'. $query->send_recipient_rid);

    case 'title':
      $account = user_load(array('uid' => $query));
      if (!$account->uid) $account->name = variable_get('anonymous', t('Anonymous'));
      return $account->name;
  }
}

function _send_views_arg_sid($op, &$query, $argtype, $arg = null) {
  switch ($op) {
    case 'summary':
      return array('field' => 'send.sid');

    case 'sort':
      $query->add_orderby('send', 'sid', $argtype);
      return;

    case 'filter':
      $query->ensure_table('send');
      $query->add_where("send.sid = '%d'", $arg);
      return;

    case 'link':
      $ts = format_date(db_result(db_query("SELECT timestamp FROM {send} WHERE sid = %d", $query->send_sid)));
      return l($ts, $arg .'/'. $query->send_sid);

    case 'title':
      return db_result(db_query("SELECT subject FROM {send} WHERE sid = %d", $query));
  }
}

function _send_views_handler_count($field, $fieldinfo, &$query) {
  static $table;
  if (!$table) {
    $n = $query->add_table($field['table'], true, 1);
    $table = $query->get_table_name($field['table'], $n);
    $query->add_groupby($table .'.sid');
  }

  switch ($field['field']) {
    case 'count':
      $sql = 'COUNT('. $table .'.sid)';
      break;

    case 'rcount':
      $sql = 'SUM('. $table .'.rcount)';
      break;
  }

  $query->add_field($sql .' AS '. $field['queryname'], NULL);
}

function _send_views_handler_sort_count($op, &$query, $sortinfo, $sortdata) {
  // Make sure we've got the field to sort on.
  $sortinfo['queryname'] = $sortinfo['table'] .'_'. $sortinfo['field'];
  _send_views_handler_count($sortinfo, $sortdata, $query);

  $field = $sortinfo['table'] .'_'. $sortinfo['field'];
  $query->orderby[] = $field .' '. $sortdata['sortorder'];
}

function _send_views_handler_field_module($fieldinfo, $fielddata, $value, $data) {
  $modules = send_modules();
  return $modules[$value];
}

function _send_views_handler_sender($field, $fieldinfo, &$query) {
  $query->add_field('uid', $field['tablename'], $field['queryname']);
  $query->add_field('mail', $field['tablename'], $field['queryname'] .'_mail');

  // Smash and grab the user table's join info - so we can get a username.
  $table_data = _views_get_tables();
  $joininfo = $table_data['users']['join'];
  $joininfo['type'] = 'left';  // Don't use an inner join.
  $joininfo['left']['table'] = 'send';  // use send, not node table.

  $n = $query->add_table('users', false, 1, $joininfo);
  $table = $query->get_table_name('users', $n);
  $query->add_field('name', $table, $field['queryname'] .'_name');
}

function _send_views_handler_field_sender($fieldinfo, $fielddata, $value, $data) {
  if ($value) {
    $account = new stdClass();
    $account->name = $data->send_uid_name;
    $account->uid = $value;
    return theme('username', $account);
  }

  // Anonymous - return part of the email.
  return substr($data->send_uid_mail, 0, strpos($data->send_uid_mail, '@')) .' ('. variable_get('anonymous', t('Anonymous')) .')';
}

function _send_views_handler_recipient($field, $fieldinfo, &$query) {
  $query->add_field('rid', $field['tablename'], $field['queryname']);
  $query->add_field('rtype', $field['tablename'], $field['queryname'] .'_type');
  $query->add_field('mail', $field['tablename'], $field['queryname'] .'_mail');
}

function _send_views_handler_field_recipient($fieldinfo, $fielddata, $value, $data) {
  if ($data->send_recipient_rid_type == 'user') {
    $account = user_load(array('uid' => $value));
    // Anonymous - return part of the email.
    if (!$account->uid) {
      $account->name = substr($data->send_recipient_rid_mail, 0, strpos($data->send_recipient_rid_mail, '@')) . variable_get('anonymous', t('Anonymous'));
      $account->name =  substr($data->send_recipient_rid_mail, 0, strpos($data->send_uid_mail, '@')) .' ('. variable_get('anonymous', t('Anonymous')) .')';
    }
    return theme('username', $account);
  }

  if ($data->send_recipient_rid_type == 'node') {
    $node = node_load($value);
    return l($node->title, 'node/'. $value);
  }
}

function _send_views_default_views() {
  // TODO:
  // top users (??)
  // items sent to "me"
  // items sent by "me"
  // message history overview

  // "Email history" tab for each node.
  $view = new stdClass();
  $view->name = 'send_history_node';
  $view->description = t('The email history for a particular node');
  $view->access = array ();
  $view->view_args_php = '';
  $view->page = TRUE;
  $view->page_title = '';
  $view->page_header = '';
  $view->page_header_format = '1';
  $view->page_footer = '';
  $view->page_footer_format = '1';
  $view->page_empty = '';
  $view->page_empty_format = '1';
  $view->page_type = 'table';
  $view->url = 'node/$arg/send';
  $view->use_pager = TRUE;
  $view->nodes_per_page = '10';
  $view->menu = TRUE;
  $view->menu_title = 'Email history';
  $view->menu_tab = TRUE;
  $view->menu_tab_weight = '0';
  $view->menu_tab_default = FALSE;
  $view->menu_tab_default_parent = NULL;
  $view->menu_tab_default_parent_type = 'tab';
  $view->menu_parent_tab_weight = '0';
  $view->menu_parent_title = '';
  $view->sort = array (
    array (
      'tablename' => 'send',
      'field' => 'timestamp',
      'sortorder' => 'DESC',
      'options' => '',
    ),
  );
  $view->argument = array (
    array (
      'type' => 'send_node',
      'argdefault' => '1',
      'title' => '%1',
      'options' => '',
      'wildcard' => '',
      'wildcard_substitution' => '',
    ),
  );
  $view->field = array (
    array (
      'tablename' => 'send',
      'field' => 'timestamp',
      'label' => 'Timestamp',
      'handler' => 'views_handler_field_date_small',
      'sortable' => '1',
    ),
    array (
      'tablename' => 'send',
      'field' => 'module',
      'label' => 'Module',
      'sortable' => '1',
    ),
    array (
      'tablename' => 'send',
      'field' => 'uid',
      'label' => 'Sender',
      'sortable' => '1',
    ),
    array (
      'tablename' => 'send',
      'field' => 'subject',
      'label' => 'Subject',
      'sortable' => '1',
    ),
    array (
      'tablename' => 'send',
      'field' => 'message',
      'label' => 'Message',
    ),
  );
  $view->filter = array (
  );
  $view->exposed_filter = array (
  );
  $view->requires = array(send);
  $views[$view->name] = $view;


  // "Most Emailed" block and page.
  $view = new stdClass();
  $view->name = 'send_most_emailed';
  $view->description = 'A page and block displaying the most frequently-sent items.';
  $view->access = array (
);
  $view->view_args_php = '';
  $view->page = TRUE;
  $view->page_title = 'Most Emailed';
  $view->page_header = '';
  $view->page_header_format = '1';
  $view->page_footer = '';
  $view->page_footer_format = '1';
  $view->page_empty = '';
  $view->page_empty_format = '1';
  $view->page_type = 'teaser';
  $view->url = 'send/most-emailed';
  $view->use_pager = TRUE;
  $view->nodes_per_page = '10';
  $view->block = TRUE;
  $view->block_title = 'Most emailed';
  $view->block_header = '';
  $view->block_header_format = '1';
  $view->block_footer = '';
  $view->block_footer_format = '1';
  $view->block_empty = '';
  $view->block_empty_format = '1';
  $view->block_type = 'list';
  $view->nodes_per_block = '10';
  $view->block_more = TRUE;
  $view->block_use_page_header = FALSE;
  $view->block_use_page_footer = FALSE;
  $view->block_use_page_empty = FALSE;
  $view->sort = array (
    array (
      'tablename' => 'send_totals',
      'field' => 'count',
      'sortorder' => 'DESC',
      'options' => '',
    ),
  );
  $view->argument = array (
  );
  $view->field = array (
    array (
      'tablename' => 'node',
      'field' => 'title',
      'label' => '',
      'handler' => 'views_handler_field_nodelink',
      'options' => 'link',
    ),
  );
  $view->filter = array (
    array (
      'tablename' => 'send',
      'field' => 'timestamp',
      'operator' => '>',
      'options' => '-2592000',
      'value' => 'now',
    ),
    array (
      'tablename' => 'node',
      'field' => 'distinct',
      'operator' => '=',
      'options' => '',
      'value' => array (
  0 => 'distinct',
),
    ),
    array (
      'tablename' => 'node',
      'field' => 'status',
      'operator' => '=',
      'options' => '',
      'value' => '1',
    ),
  );
  $view->exposed_filter = array (
  );
  $view->requires = array(send_totals, node, send);
  $views[$view->name] = $view;

  // "Recently Emailed" page/block
  $view = new stdClass();
  $view->name = 'send_recently_emailed';
  $view->description = 'A page and block displaying the most recently-sent items.';
  $view->access = array (
);
  $view->view_args_php = '';
  $view->page = TRUE;
  $view->page_title = 'Recently Emailed';
  $view->page_header = '';
  $view->page_header_format = '1';
  $view->page_footer = '';
  $view->page_footer_format = '1';
  $view->page_empty = '';
  $view->page_empty_format = '1';
  $view->page_type = 'teaser';
  $view->url = 'send/recently-emailed';
  $view->use_pager = TRUE;
  $view->nodes_per_page = '10';
  $view->block = TRUE;
  $view->block_title = 'Recently emailed';
  $view->block_header = '';
  $view->block_header_format = '1';
  $view->block_footer = '';
  $view->block_footer_format = '1';
  $view->block_empty = '';
  $view->block_empty_format = '1';
  $view->block_type = 'list';
  $view->nodes_per_block = '10';
  $view->block_more = TRUE;
  $view->block_use_page_header = FALSE;
  $view->block_use_page_footer = FALSE;
  $view->block_use_page_empty = FALSE;
  $view->sort = array (
    array (
      'tablename' => 'send',
      'field' => 'timestamp',
      'sortorder' => 'DESC',
      'options' => 'day',
    ),
    array (
      'tablename' => 'node',
      'field' => 'nid',
      'sortorder' => 'ASC',
      'options' => '',
    ),
  );
  $view->argument = array (
  );
  $view->field = array (
    array (
      'tablename' => 'node',
      'field' => 'title',
      'label' => '',
      'handler' => 'views_handler_field_nodelink',
      'options' => 'link',
    ),
  );
  $view->filter = array (
    array (
      'tablename' => 'node',
      'field' => 'distinct',
      'operator' => '=',
      'options' => '',
      'value' => array (
  0 => 'distinct',
),
    ),
    array (
      'tablename' => 'node',
      'field' => 'status',
      'operator' => '=',
      'options' => '',
      'value' => '1',
    ),
    array (
      'tablename' => 'send',
      'field' => 'timestamp',
      'operator' => '<=',
      'options' => '',
      'value' => 'now',
    ),
  );
  $view->exposed_filter = array (
  );
  $view->requires = array(send, node);
  $views[$view->name] = $view;

  return $views;
}
