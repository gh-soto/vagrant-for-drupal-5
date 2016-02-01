<?php

$user_is_admin = user_access('administer '.uieforum_get_module_security_name());
$user_is_mod = user_access('moderate '.uieforum_get_module_security_name().'_'.uie_id('fid').'_'.uieforum_get_module_security_name());
$is_original_poster = $CurrentPost->Poster == $user->uid ? true : false;
$timeNow = time();
$minused = $timeNow - $CurrentPost->Posted;
$edit_expired = !($CurrentPost->AlwaysEdit) && variable_get('forum_edit_timeout', 3600) && ($minused > variable_get('forum_edit_timeout', 3600) ? true : false);
$user_can_post = user_access('post new threads/posts');
if
(
  //preliminary checks - 1 - is user an admin/mod
  (!$user_is_admin && !$user_is_mod)
  &&
  (
    //extra checks - 1 - is forum/thread locked - is edit time expired, can user actually post at all
    $CurrentForum->Locked || $CurrentThread->Locked || !$user_can_post
    ||
    (
      //extra checks - 2
      uie_id('PostID') && $CurrentPost && ($edit_expired || !$is_original_poster)
    )
  )
)
{
  drupal_access_denied();
  die();
}
else
{
/** Used to show the input form **/
  $SHOW_POST_INCLUDE = true;

  $CurrentThread = uieforum_current_thread();
  if (form_get_errors() != null || ( (!uie_id('ThreadID') && !uie_id('fid')) || ((!uie_id('ThreadID') && uie_id('PostID')) || (!uie_id('ThreadID') && uie_id('QuoteID')))))
//    form_set_error('unreal.ie', t('Error, something is not quite right - please contact a site developer with this error.'));
    $Error = true;
/*
    echo "<pre>";
    print_r($_POST);
die();
*/
  if (form_get_errors() == null && isset($_POST['NewPost']))
  {
     $ThreadTitle = uieforum_get_post_title();
     $ThreadText = uieforum_get_post_text();
     $AlwaysEdit = uieforum_get_post_always_edit();

     $timeout = variable_get('forum_newpost_timeout', 15);
     /** Post timer - can't post within 15 secs (for example) of the users's last post **/
     if(!$user_is_admin && !$user_is_mod && !uieforum_is_edit_post() && $val = uieforum_posted_too_soon($timeout))
     {
       form_set_error('UIE_FORUM_FORM', t('Forum administrators require at least '.$timeout.' seconds between posts. You last posted '.$val.' seconds ago'));
     }
     elseif ((!uie_id('ThreadID') && !$ThreadID = uieforum_create_thread($ThreadTitle, $ThreadText, uie_id('fid'), $AlwaysEdit)) || (uie_id('PostID') && $thingy = uieforum_edit_post(uie_id('PostID'), $ThreadTitle, $ThreadText, $user->uid, uieforum_get_edit_reason(), uie_id('ThreadID'), $AlwaysEdit)) || (uie_id('ThreadID') && !uie_id('PostID') && !$NewPostID = uieforum_add_post(uie_id('ThreadID'),$ThreadTitle, $ThreadText, null, $AlwaysEdit)))
     {
       $ErrorMsg = 'Problem creating new ';
       $ErrorMsg .= uie_id('ThreadID') ? 'thread' : 'post';
       $ErrorMsg .= $thingy;
       form_set_error('UIE_FORUM_FORM', t($ErrorMsg));
     }
     else
       $SHOW_POST_INCLUDE = false;

     if($ThreadID)
       uie_id('ThreadID', $ThreadID);

     if(uie_id('ThreadID'))
       $CurrentThread = get_thread(uie_id('ThreadID'));
  }
  elseif (isset($_POST['PreviewPost']))
  {
    /** It's a preview **/
    if(form_get_errors() == null)
      $ForumTable .= "<div class=\"post-container\">".theme('table', null, uieforum_display_post(null, null, null, null, uieforum_get_post_title(), uieforum_get_post_text(), null, true, false, uie_id('fid')), array('class' => 'forum'))."</div>";
  }

  if($SHOW_POST_INCLUDE)
  {
    $bc_txt = uie_id('ThreadID') ? t('Reply') : t('New Thread');
    $bc_txt = uie_id('QuoteID') ? t('Reply (Quoted)') : $bc_txt;
    $bc_txt = uie_id('PostID') ? t('Edit Post') : $bc_txt;
    $breadcrumbs[] = t('<strong>[ %newval ]</strong>', array('%newval' => $bc_txt));
    drupal_set_title($bc_txt);

    if (!isset($textarea))
      $textarea = $PostText;

    if ((!isset($_POST['PreviewPost']) && !isset($_POST['NewPost'])) && (uie_id('QuoteID') || uie_id('PostID')))
    {
      if(uie_id('QuoteID')) $temp = "[quote=$QuotedPost->name]";
      $temp .= "$QuotedPost->Content";
      if(uie_id('QuoteID')) $temp .= "[/quote]\n";

      $textarea = $temp.$textarea;
    }
    include "post_include.php";
		if(variable_get('forum_show_reversed_thread_on_reply', 0))
    {
      //# Generate reverse-Threadview for responses
      $reverseThreadDisplay = true;
      include("showthread.php");
	  }
  }
  else
  {
    /** Post submitted successfully, so forward user to it **/
    drupal_goto(uieforum_get_module_menu_name(), get_thread_link_text_last_post($CurrentThread, null, false, true));
  }
}
