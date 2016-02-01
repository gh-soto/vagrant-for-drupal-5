<div class="usernode<?php if ($sticky) { print " sticky"; } ?><?php if (!$status) { print " node-unpublished"; } ?>">

 <?php if ($teaser): ?>
<div class="content">
<?php print $teaser ?>

</div>
<?php else: ?>
  
<div class="content">
<?php
$nardesignation = '';
$narstyle = '';
$account = user_load(array('uid' => $node->uid));
if (in_array('NARs Green Designation', $account->roles)){
  $nardesignation = '<img src="'.base_path().path_to_theme().'/img/nargreendesignation.png" />';
  $narstyle = 'style="background-color:#EDF3ED;"';
}
?>
<div class="spacer_usernode" <?php print $narstyle; ?>><table cellpadding="0" cellspacing="0" width="100%"><tr>
<TD width="33%" align="center" valign="top" style="border-right: 1px solid black">
<?php if($node->user->picture != ''){ ?>
<img src="<?php print base_path().$node->user->picture ?>" alt="">
<?php } else {} ?></td>
<TD width="33%" align="center" valign="top" style="border-right: 1px solid black">
    
<?php if($nardesignation) { print $nardesignation.'<br />'; } ?>
<?php
$tehname = trim($node->user->profile_first).'\s(([A-Z]\.\s)|([A-Z]\s)|())'.trim($node->user->profile_last);
if(preg_match("/".$tehname."/i", $node->user->profile_nl)) {
  $myname = '';  
}else{
    $myname = $node->user->profile_first.' '.$node->user->profile_last.'<br /> ';
} ?>
<?php print $myname.$node->user->profile_nl ?>

</td>
<TD width="33%" align="center" valign="top" >
<?php if ($node->user->profile_officenumber){ ?>Office: <?php print $node->user->profile_officenumber; ?><br /><?php } ?>
<?php if ($node->user->profile_directnumber){ ?>Direct: <?php print $node->user->profile_directnumber; ?><br /><?php } ?>
<?php if ($node->user->profile_cellnumber){ ?>Cell: <?php print $node->user->profile_cellnumber; ?><br /><?php } ?>
<?php if ($node->user->profile_faxnumber){ ?>Fax: <?php print $node->user->profile_faxnumber; ?><br /><?php } ?>
<?php if ($node->user->profile_www){ ?><a href="<?php print $node->user->profile_www; ?>" target="_blank" rel="nofollow">Website</a><br /><?php } ?>
<?php if ($node->user->profile_email){ ?><A href="mailto:<?php print $node->user->profile_email; ?>?subject=GreenandSave.com Realtor Directory Inquiry">Email</A><?php } ?></td>
</tr></table>
</div>


</div>
<?php endif; ?>

</div>