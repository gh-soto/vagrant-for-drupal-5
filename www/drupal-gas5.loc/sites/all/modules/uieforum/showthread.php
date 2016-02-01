<?php
if(!isset($reverseThreadDisplay))
{
  $reverseThreadDisplay = false;
}

if(!uie_id('ThreadID'))
{
  /** Error **/
}
else
{
	if(!$reverseThreadDisplay)
  {
    drupal_add_js(drupal_get_path('module', uieforum_get_module_name()) .'/js/admin_tools.js');

    /** Set user's history activity to include this thread **/
    uieforum_tag_new(uie_id('ThreadID'));
  }
  $CurrentThread = uieforum_current_thread();

	if(!$reverseThreadDisplay)
  {
    drupal_set_title(check_plain(uieforum_get_post_title(get_thread_title($CurrentThread, false))));
  }
  $Posts = uieforum_get_posts(uie_id('ThreadID'));

  if($Error )
    echo "<p class=\"Error\">$Error</p>\n";
  else
  {
    if(!$reverseThreadDisplay)
    {
      /** Increase thread view-count **/
      uieforum_update_counts(uie_id('ThreadID'), "thread", "view", true);
    }
    /** Get page numbers for this thread **/
    $pagenumbers = array(
      array(
        array('data' => $NEW_REPLY_TEXT.'&nbsp;&nbsp;'.$NEW_THREAD_TEXT, 'colspan' => '1', 'class' => 'created', 'style' => 'width: 150px; text-align: left'),
        array('data' => uieforum_get_page_numbers($CurrentThread->PostCount), 'class' => 'forum_pagenums')
      )
    );

    if(!$reverseThreadDisplay)
    {
      $pagestable = "<div class=\"post-container\">".theme('table', null, $pagenumbers, array('class' => 'forum', 'cellspacing' => '0', 'cellpadding' => '0'))."</div>";
    }
    uieforum_uie_storage(null, null, true);


    $ForumTable .= $pagestable;
    $ii = 0;
    /** Loop through each post and display it **/
    $show_online_status = variable_get('uieforum_online_status', 1);
    $show_post_counts = variable_get('uieforum_user_post_counts', 1);
    $show_ranks = variable_get('uieforum_ranks', 1);
    $show_regd = variable_get('uieforum_show_registered', 1);
    $show_avatar = variable_get('user_pictures', 0);
    $show_sigs = variable_get('uieforum_show_sigs', 1);
    $Forum = uie_id('fid');

    $numPostsOnPage = sizeof($Posts);
    if($reverseThreadDisplay)
    {
      $Posts = array_reverse($Posts);
    }
    foreach ( $Posts as $Post )
    {
      $sig = $Post->signature;
      $temp = array();
      $temp = uieforum_display_post(uieforum_parse_date($Post->Posted), uie_id('ThreadID'), $Post->PostID, $Post->Poster, $Post->PostTitle, $Post->Content, --$numPostsOnPage, null, $Forum, array($Post->Editor, $Post->Edit, $Post->EditReason), $sig, $show_avatar, $show_online_status, $show_post_counts, $show_ranks, $show_regd, $show_sigs);

      if($reverseThreadDisplay)
      {
        $temp = array($temp[1]);
      }

      $ForumTable .= "<div class=\"post-container\">".theme('table', null, $temp, array('class' => 'forum', 'cellspacing' => '0', 'cellpadding' => '0'))."</div>";
    }
    $ForumTable .= $pagestable;
  }
}
