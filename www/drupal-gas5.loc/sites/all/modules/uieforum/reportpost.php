<?php
  $allowGuestReport = variable_get('forum_report_post_guest_allow', 0);

  if(!$allowGuestReport && (!$user || !$user->uid))
  {
    drupal_access_denied();
    die();
  }

  $post_header = $post_footer = array(
    array('data' => '', 'colspan' => '1', 'class' => 'created', 'style' => 'font-weight: bold; text-align: center')
  );
  $post_content = array(
    array('data' => '', 'colspan' => '1', 'class' => 'post_text', 'style' => 'vertical-align: middle; text-align: center')
  );

  $post_header [0]['data'] = t('Report Post');

  $referer = false;
  $refererText = 'the parent forum';
  $refererLinkage = uieforum_get_module_menu_name();
  if($_POST['ReportPostReferer']) {
    $referer = true;
    $refererLinkage = $_POST['ReportPostReferer'];
  }
  elseif($_SERVER['HTTP_REFERER']) {
    $referer = true;
    $refererLinkage = $_SERVER['HTTP_REFERER'];
    $refererText = 'where you came from';
  }
  $returnText = t('Click here to return to '.$refererText);
  $returnToForum = l($returnText, $refererLinkage, null, $referer ? null : 'fid='.uie_id('fid'));

  if($_POST['confirm'])
  {

      $reportEmail = variable_get('forum_report_post_email', '');
      if($reportEmail)
      {
        $subject = '** '.t('Post Reported').' - '.uie_id('PostID');
//        echo "<pre>";
        $userby = user_load(array('uid'=>$CurrentPost->Poster));
        $message  = $user->name . " has reported a post.\n\n";
        $message .= "Posted By: ".$userby->name."\n";
        $message .= "Posted Time: ".$CurrentPost->Posted."\n";
        $message .= "PostID: ".$CurrentPost->PostID."\n";
        $message .= "ThreadID: ".l($CurrentPost->ThreadID, uieforum_get_module_menu_name(), null, "c=showthread&ThreadID=".$CurrentPost->ThreadID."#".$CurrentPost->PostID, null, true, true)."\n";
        $message .= $_POST['ReportPostMessage'] ? 'Reason: '.$_POST['ReportPostMessage']."\n\n" : null;
        $message .= "Message Content Follows:\n\n".$CurrentPost->Content;
//        echo $message;
//        die();

        $header  = "From: ".$user->name." <".$user->mail.">\n";
//        $header .= "Cc: thing@thing.com\n";
        drupal_mail('uieforum_reportpost', $reportEmail, $subject, $message, $header);
    
        $post_content[0]['data'] = variable_get('forum_report_post_msg', t('Post reported').'<br/><br/>'.t('Thank you').'<br/><br/>') . $returnToForum;
      }
      else
      {
        $post_content[0]['data'] = t('Please contact a site administrator').'<br />'.t('No email address has been configured for post reporting').'<br/><br/>'.$returnToForum;
      }
  }
  else
  {
      $post_content[0]['data']  = t('Are you sure?');
      $post_content[0]['data'] .= drupal_get_form('uieforum_submit_report_post_form');
      $post_content[0]['data'] .= $returnToForum;
  }
  $ForumTable .= "<center><div class=\"post-container\" style=\"width: 50%\">".theme('table', null, array($post_header, $post_content, $post_footer), array('class' => 'forum', 'cellspacing' => '0', 'cellpadding' => '0'))."</div></center>";
