 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>
<div class="content">

<b>Date:</b> <?php print check_plain($node->field_ec_date[0]['value']) ?> - 
<b>Location:</b> <?php print check_plain($node->field_ec_location[0]['value']) ?><br />
<?php 
print check_markup($node->field_teaser[0]['value']);
$search = '/type="submit"/';
$replace = 'type="image" src="'.base_path().path_to_theme().'/img/gctc/cart_enrolltoday.png" ';
$subject = $node->content['add_to_cart']['#value'];
print preg_replace($search, $replace, $subject);
?>
</div>

<?php else: ?>
<div class="content">

<?php

if($node->nid == '3444' || $node->nid == '3719'){
  
    $search = '/type="submit"/';
    $replace = 'type="image" src="'.base_path().path_to_theme().'/img/gctc/cart_enrolltoday.png" ';
    $subject = $node->body;
    print preg_replace($search, $replace, $subject);
    
}else{
  
  if($node->taxonomy[1849]){ // General Public Course
  
    if(mktime(check_plain($node->field_ec_eventdate[0]['value']))){
  
      $rightnow = time();
      $oneweek = 7*24*60*60;
      $referencedate = strtotime($node->field_ec_eventdate[0]['value']);

      $difference = abs($rightnow - $referencedate);
      $weeks = abs(ceil($difference / $oneweek));
      $predatetxt = 'Enroll before ';
      if($weeks == 0){
  
        $displaydate = $referencedate;
        $enrolltext =  '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $995.00</h3>';
  
      }elseif($weeks == 1){
  
        $displaydate = $referencedate - $oneweek;
        $enrolltext = '<h3>Enroll before '.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }elseif($weeks == 2){
  
        $displaydate = $referencedate - $oneweek;
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }elseif($weeks == 3){
  
        $displaydate = $referencedate - $oneweek;
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }elseif($weeks > 4){
  
        $displaydate = $referencedate - ($oneweek * 4);
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }//end if weeks

    }//end ec event date
    
    $predatetxt = 'Enroll before ';
    $ec_title = '<h3 class="greenfg">General Public Course - 2 Days</h3>';
    $ec_minimum = '<b>Minimum Requirements:</b> High School and College Graduates Preferred.<br /> ';
    $ec_length = 'Get qualified within 30 Days';
    
    print $ec_title; ?>
    <ul>
    <b>Date:</b> <?php print check_plain($node->field_ec_date[0]['value']) ?>, 8:00 a.m.- 5:00 p.m <br /> 
    <b>Location:</b> <?php print check_plain($node->field_ec_location[0]['value']) ?><br /> 
    <?php print $ec_minimum; ?>
    </ul>

    <?php // print $enrolltext; ?>

    No Risk Guarantee - Satisfied or Money Back
    <h3 class="greenfg">CALL NOW to learn more: 610.628.1300</h3>
    <ul><li><?php print $ec_length; ?></li>
      <li>No prior experience necessary</li>
    </ul>
    <p>
    <div class="greenbg">Our diverse curriculum and Eco programs produce consultants, field agents, installers, and new business owners that share in our commitment to secure high paying jobs and make America the leaderin environmental sustainability and energy independence.</div>

  <?php 

  //preg_match('/\<div\sclass\=\"add_to_cart\"\>.*\<\/form\>\s\<\/div\>/', $node->body, $matches);
  $search = '/type="submit"/';
  $replace = 'type="image" src="'.base_path().path_to_theme().'/img/gctc/cart_enrolltoday.png" ';
  $subject = $node->content['add_to_cart']['#value'];
  print preg_replace($search, $replace, $subject);

  }elseif($node->taxonomy[1850]){ // Pro Course
      if(mktime(check_plain($node->field_ec_eventdate[0]['value']))){
  
      $now = time();
      $oneweek = (7*24*60*60);
      $referencedate = strtotime(check_plain($node->field_ec_eventdate[0]['value']));

      $difference = ($now - $referencedate);
      $weeks = abs(ceil($difference / $oneweek));
      $predatetxt = 'Enroll before ';

      if($weeks == 0){
  
        $displaydate = $referencedate;
        $enrolltext =  '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $795.00</h3>';
  
      }elseif($weeks == 1){
  
        $displaydate = $referencedate - $oneweek;
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }elseif($weeks == 2){
  
        $displaydate = $referencedate - ($oneweek * 2);
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }elseif($weeks == 3){
  
        $displaydate = $referencedate - ($oneweek * 3);
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $695.00</h3>';
  
      }elseif($weeks > 4){
  
        $displaydate = $referencedate - ($oneweek * 4);
        $enrolltext = '<h3>'.$predatetxt.date("l, F jS, Y", $displaydate).' for $595.00</h3>';
  
      }//end if weeks

    }//end ec event date
    $ec_title = '<h3 class="greenfg">Pro Course - 1 Day</h3>';
    $ec_minimum = '<b>Minimum Requirements:</b> High School and College Graduates Preferred.<br />This one day course is ONLY for licensed Home Inspectors, General Contractors, Plumbers, Electricians, or HVAC installers.';
    $ec_length = 'Get qualified in just 1 day.';
    
    print $ec_title; ?>
    <ul>
    <b>Date:</b> <?php print check_plain($node->field_ec_date[0]['value']) ?> <br /> 
    <b>Location:</b> <?php print check_plain($node->field_ec_location[0]['value']) ?><br /> 
    <?php print $ec_minimum; ?>
    </ul>

    <?php print $enrolltext; ?>

    No Risk Guarantee - Satisfied or Money Back
    <h3 class="greenfg">CALL NOW to learn more: 484-588-5407</h3>
    <ul><li><?php print $ec_length; ?></li>
      <li>Make $1,000 - $3,000 Weekly</li>
      <li>No prior experience necessary</li>
    </ul>
    <p>
    <div class="greenbg">Our diverse curriculum and Eco programs produce consultants, field agents, installers, and new business owners that share in our commitment to secure high paying jobs and make America the leaderin environmental sustainability and energy independence.</div>

  <?php 

  //preg_match('/\<div\sclass\=\"add_to_cart\"\>.*\<\/form\>\s\<\/div\>/', $node->content['#children'],$matches);
  $search = '/type="submit"/';
  $replace = 'type="image" src="'.base_path().path_to_theme().'/img/gctc/cart_enrolltoday.png" ';
  $subject = $node->content['add_to_cart']['#value'];
  print preg_replace($search, $replace, $subject);

  }else{
    $search = '/type="submit"/';
    $replace = 'type="image" src="'.base_path().path_to_theme().'/img/gctc/cart_enrolltoday.png" ';
    $subject = $node->body;
    print preg_replace($search, $replace, $subject);
  }
}//end special node


?>



</div>
<?php endif; ?>

</div>