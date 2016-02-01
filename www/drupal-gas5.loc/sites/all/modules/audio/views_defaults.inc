<?php
// $Id: views_defaults.inc,v 1.1.2.1 2007/07/19 23:31:51 drewish Exp $

function audio_views_default_views() {
    $view = new stdClass();
  $view->name = 'audio';
  $view->description = 'Audio nodes';
  $view->access = array (
  0 => '1',
  1 => '2',
);
  $view->view_args_php = '';
  $view->page = TRUE;
  $view->page_title = 'Audio';
  $view->page_header = '';
  $view->page_header_format = '1';
  $view->page_footer = '';
  $view->page_footer_format = '1';
  $view->page_empty = '';
  $view->page_empty_format = '1';
  $view->page_type = 'teaser';
  $view->url = 'audio';
  $view->use_pager = TRUE;
  $view->nodes_per_page = '10';
  $view->menu = TRUE;
  $view->menu_title = 'Audio';
  $view->menu_tab = FALSE;
  $view->menu_tab_default = FALSE;
  $view->menu_tab_weight = '0';
  $view->block = TRUE;
  $view->block_title = 'Recent audio';
  $view->block_header = '';
  $view->block_header_format = '1';
  $view->block_footer = '';
  $view->block_footer_format = '1';
  $view->block_empty = '';
  $view->block_empty_format = '1';
  $view->block_type = 'list';
  $view->nodes_per_block = '5';
  $view->block_more = TRUE;
  $view->block_use_page_header = FALSE;
  $view->block_use_page_footer = FALSE;
  $view->block_use_page_empty = FALSE;
  $view->sort = array (
    array (
      'tablename' => 'node',
      'field' => 'created',
      'sortorder' => 'DESC',
      'options' => 'normal',
    ),
  );
  $view->argument = array (
    array (
      'type' => 'rss_feed',
      'argdefault' => '2',
      'title' => '',
      'options' => '',
      'wildcard' => '',
      'wildcard_substitution' => '',
    ),
  );
  $view->field = array (
    array (
      'tablename' => 'node',
      'field' => 'title',
      'label' => '',
      'handler' => 'views_handler_field_nodelink_with_mark',
      'options' => 'link',
    ),
  );
  $view->filter = array (
    array (
      'tablename' => 'node',
      'field' => 'type',
      'operator' => 'OR',
      'options' => '',
      'value' => array (
  0 => 'audio',
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
  $view->requires = array(node);
  $views[$view->name] = $view;



  $view = new stdClass();
  $view->name = 'audio_random';
  $view->description = 'Random audio nodes';
  $view->access = array (
  0 => '1',
  1 => '2',
);
  $view->view_args_php = '';
  $view->block = TRUE;
  $view->block_title = 'Random audio';
  $view->block_header = '';
  $view->block_header_format = '1';
  $view->block_footer = '';
  $view->block_footer_format = '1';
  $view->block_empty = '';
  $view->block_empty_format = '1';
  $view->block_type = 'list';
  $view->nodes_per_block = '5';
  $view->block_more = FALSE;
  $view->block_use_page_header = FALSE;
  $view->block_use_page_footer = FALSE;
  $view->block_use_page_empty = FALSE;
  $view->sort = array (
    array (
      'tablename' => 'node',
      'field' => 'random',
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
      'field' => 'type',
      'operator' => 'OR',
      'options' => '',
      'value' => array (
  0 => 'audio',
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
  $view->requires = array(node);
  $views[$view->name] = $view;




  $view = new stdClass();
  $view->name = 'audio_user';
  $view->description = 'Audio user feed';
  $view->access = array (
  0 => '1',
  1 => '2',
);
  $view->view_args_php = '';
  $view->page = TRUE;
  $view->page_title = 'User\'s Audio';
  $view->page_header = '';
  $view->page_header_format = '1';
  $view->page_footer = '';
  $view->page_footer_format = '1';
  $view->page_empty = '';
  $view->page_empty_format = '1';
  $view->page_type = 'teaser';
  $view->url = 'audio/user';
  $view->use_pager = TRUE;
  $view->nodes_per_page = '10';
  $view->sort = array (
    array (
      'tablename' => 'node',
      'field' => 'created',
      'sortorder' => 'DESC',
      'options' => 'normal',
    ),
  );
  $view->argument = array (
    array (
      'type' => 'uid',
      'argdefault' => '1',
      'title' => '%1\'s Audio',
      'options' => '',
      'wildcard' => '',
      'wildcard_substitution' => '',
    ),
    array (
      'type' => 'rss_feed',
      'argdefault' => '2',
      'title' => '%1\'s Audio',
      'options' => '',
      'wildcard' => '',
      'wildcard_substitution' => '',
    ),
  );
  $view->field = array (
  );
  $view->filter = array (
    array (
      'tablename' => 'node',
      'field' => 'type',
      'operator' => 'OR',
      'options' => '',
      'value' => array (
  0 => 'audio',
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
  $view->requires = array(node);
  $views[$view->name] = $view;



  $view = new stdClass();
  $view->name = 'audio_bc_feed_user';
  $view->description = 'Audio user feed (for old URL backwards compatiblity)';
  $view->access = array (
    0 => '1',
    1 => '2',
  );
  $view->view_args_php = '';
  $view->page = TRUE;
  $view->page_title = 'User\'s Audio Feed';
  $view->page_header = '';
  $view->page_header_format = '1';
  $view->page_footer = '';
  $view->page_footer_format = '1';
  $view->page_empty = '';
  $view->page_empty_format = '1';
  $view->page_type = 'views_rss';
  $view->url = 'audio/feed/user';
  $view->use_pager = FALSE;
  $view->nodes_per_page = '10';
  $view->sort = array (
    array (
      'tablename' => 'node',
      'field' => 'created',
      'sortorder' => 'DESC',
      'options' => 'normal',
    ),
  );
  $view->argument = array (
    array (
      'type' => 'uid',
      'argdefault' => '1',
      'title' => '%1\'s Audio',
      'options' => '',
      'wildcard' => '',
      'wildcard_substitution' => '',
    ),
  );
  $view->field = array (
  );
  $view->filter = array (
    array (
      'tablename' => 'node',
      'field' => 'type',
      'operator' => 'OR',
      'options' => '',
      'value' => array (
        0 => 'audio',
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
  $view->requires = array(node);
  $views[$view->name] = $view;


  return $views;
}
