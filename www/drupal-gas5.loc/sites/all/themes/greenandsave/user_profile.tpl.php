<?php
GLOBAL $user;
if($user->uid == $account->uid){
  $viewable = true;
}
//$editdirectory = false;
//$requiredname = false;
$editdirectory = false;

if($user->uid == '1'){
					if(is_bool($viewable) === false){
										print 'is false';
					}else{
										print 'is true';
					}

}
if($account->profile_first == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $requiredname = true;
    $firstname = '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit First Name">Edit</a>- ';
    $required = '<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit First Name">First name</a> | ';
  }
}else{
  $firstname = $account->profile_first;
}
  
if($account->profile_last == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $lastname = '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Last Name">Edit</a>- ';
    $required .= '<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Last Name">Last Name</a> | ';
    $requiredname = true;
  }
  }else{
  $lastname = $account->profile_last;
}
if($account->profile_company == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pcompany =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Company">Edit</a>- ';
    $required .= '<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Company">Company name</a> | ';
  }
}else{
  $pcompany = $account->profile_company;
}
if($account->profile_nl == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pabout =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit About">Edit</a>- ';
  }
}else{
  $pabout = $account->profile_nl;
}
if($account->profile_s == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pstate =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit State">Edit</a>- ';
  }
}else{
  $pstate = $account->profile_s;
}
if($account->profile_www == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pwww =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Website Address">Edit</a>- ';
    $required .= '<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Website Address">Website Address</a> | ';
  }
}else{
  $pwww = $account->profile_www;
}
if($account->profile_email == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pemail =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Email Address">Edit</a>- ';
    $required .= '<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Email Address">Email Address</a> | ';
  }
}else{
  $pemail = $account->profile_email;
}
if($account->profile_officenumber == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $poffice =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Office Number">Edit</a>- ';
    $required .= '<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Office Number">Office number</a> | ';
  }
}else{
  $poffice = $account->profile_officenumber;
}
if($account->profile_directnumber == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pdirect =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Direct Number">Edit</a>- ';
  }
}else{
  $pdirect = $account->profile_directnumber;
}
if($account->profile_cellnumber == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pcell =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Cell Number">Edit</a>- ';
  }
}else{
  $pcell = $account->profile_cellnumber;
}
if($account->profile_faxnumber == ''){
  if(is_bool($viewable) === true){
    $editdirectory = true;
    $pfax =  '-<a href="'.base_path().'user/'.$account->uid.'/edit/Directory" title="Edit Fax Number">Edit</a>- ';
  }
}else{
  $pfax = $account->profile_faxnumber;
}

$header == '';
$userinfo = ucfirst($firstname).' '.ucfirst($lastname);
$directorystate = preg_replace('/\s/', '+', $pstate);

if($user->uid == '1'){
					if(is_bool($viewable) === false){
										print 'is false';
					}else{
										print 'is true';
					}

					if(is_bool($editdirectory) === false){
										print '<p>dir is false';
					}else{
										print '<p>dir is true';
					}
}
  /* ROLES
    authenticated user
  simulcast
   
  general public course
  hec online cert
  hec online training
  hec online training and cert
  hec_forum
  home inspector   
  pro course
    
    service provider
  complimentary home inspector
  complimentary realtor
  complimentary realtor directory
  NARs Green Designation
  realtor
  trial realtor
  architect
  contractor
  
  
  main editor
  developer
  editor
  general admin
if (in_array('complimentary home inspector', $account->roles)){
  
}  */

$directorylink = '';
// Architect
if (in_array('architect', $account->roles)){
    $title = 'Architect';
}
if(in_array('realtor', $account->roles)){
  $title = "Realtor";
	$directorylink = 'directory/'.$directorystate.'/realtors';
}
if(in_array('contractor', $account->roles)){
  $title = "Contractor";
}
if(in_array('service provider', $account->roles)){
  $title = "Service Provider";
}
if(in_array('complimentary home inspector', $account->roles)){
  $title = "Home Inspector";
}
if(in_array('home inspector', $account->roles)){
  $title = "Home Inspector";
}
if(in_array('complimentary realtor directory', $account->roles)){
  $title = "Realtor";
	$directorylink = 'directory/'.$directorystate.'/realtors';
}
if(in_array('complimentary realtor', $account->roles)){
  $title = "Realtor";
	$directorylink = 'directory/'.$directorystate.'/realtors';
}
if(in_array('NARs Green Designation', $account->roles)){
  $title = "Realtor";
	$directorylink = 'directory/'.$directorystate.'/realtors';
}


if($user->uid == '1'){
					//print '<h2>'.$editdirectory.'</h2>';
}


if($requiredname == false){
 $green_home_tips_pdf =                  '<tr><td><a href="'.base_path().'pdfs/pdf.php?aid='. $account->uid . '&info=' . $userinfo . '&title=' . $title. '" target="_blank">Personalized marketing material - Green Home Tips<img src="'.base_path().'files/adobe_pdf_icon.jpg" alt="Adobe PDF" align="absmiddle" /></a></td></tr>';
}else{
  $green_home_tips_pdf =                 '<tr><td><b>Personalized marketing material</b><br />The following directory fields require updating:  <div style="display:inline;color:#900">' . $firstname . ' '. $lastname.'</div></td></tr>';
}
$master_roi_pdf =                       '<tr><td><a href="'.base_path().'files/GREENandSAVE_HomeInspector_MasterROITable.pdf" target="_blank">Green home performance guides - Master ROI <img src="'.base_path().'files/adobe_pdf_icon.jpg" alt="Adobe PDF" align="absmiddle" /></a></td></tr>';
$greenandsave_registeredagent_logo =    '<tr><td><a href="'.base_path().'files/registered-agent_logo-GREENandSAVE.jpg" title="GREENandSAVE Logo" target="_blank">GREENandSAVE.com Registered Agent Logo</a></td></tr>';
$greenandsave_networkinspector_logo =   '<tr><td><a href="http://www.greenandsave.com/files/network_inspector_logo-GREENandSAVE.com.jpg" target="_blank">GREENandSAVE.com Network Inspector Logo</a></td></tr>';

if(is_bool($editdirectory) === true){
$form_nar_checkup_giftcert =            '<tr><td><form id="profileform" action="'.base_path().'pdfs/NAR-Home_Efficiency_Checkup-giftcertificate.php"  method="POST">
								<input type="hidden" name="username" value="'.$userinfo.'" />
								<input type="hidden" name="company" value="'.$pcompany.'" />
								<input type="hidden" name="email" value="'.$pemail.'" />
								<input type="hidden" name="picture" value="'.$account->picture.'" />
								<input type="hidden" name="number" value="'.$poffice.'" />
								<input type="hidden" name="www" value="'.$pwww.'" />
								<input class="profilesubmit" type="submit" value="Home Efficiency Checkup Gift Certificate" />
							<img src="'.base_path().'files/adobe_pdf_icon.jpg" alt="Adobe PDF" align="absmiddle" /></form></td></tr>';
}else{
  $form_nar_checkup_giftcert =          '<tr><td><b>NAR Home Efficiency Checkup Gift Certificate</b><br />The following directory fields require updating: <div style="display:inline;color:#900">' . $required. '</div></td></tr>';
}
if(is_bool($editdirectory) === true){
$form_nar_green_office_guide =          '<tr><td><form id="profileform" action="'.base_path().'pdfs/NAR_Green_Office_Guide.php"  method="POST">
								<input type="hidden" name="nar_username" value="'.$userinfo.'" />
								<input type="hidden" name="nar_company" value="'.$pcompany.'" />
								<input type="hidden" name="nar_email" value="'.$pemail.'" />
								<input type="hidden" name="nar_picture" value="'.$account->picture.'" />
								<input type="hidden" name="nar_number" value="'.$poffice.'" />
								<input type="hidden" name="nar_www" value="'.$pwww.'" />
								<input class="profilesubmit" type="submit" value="NAR Green Office Guide" />
							<img src="'.base_path().'files/adobe_pdf_icon.jpg" alt="Adobe PDF" align="absmiddle" /></form></td></tr>';
}else{
$form_nar_green_office_guide  =         '<tr><td><b>NAR Green Office Guide</b><br />The following directory fields require updating:  <div style="display:inline;color:#900">' . $required. '</td></tr>';
}

$rss_feed =                             '<tr><td><a href="'.base_path().'rss/greennews/feed" target="_blank" /> <img src="'.base_path().'files/rss.png" height="20" width="20" border="0" align="absmiddle" />Green News RSS Feed</a></td></tr>';
$nar_family_guide =                     '<tr><td><a href="'.base_path().'pdfs/NAR_Green_Home_Guide_031609.pdf">NARs Green Designation Family Guide <img src="'.base_path().'files/adobe_pdf_icon.jpg" alt="Adobe PDF" align="absmiddle" /></a></td></tr>';
if(is_bool($editdirectory) === true){
$green_family_guide = '<tr><td><form id="profileform" action="'.base_path().'pdfs/Family_Guide_to_Going_Green.php"  method="POST">
									<input type="hidden" name="username" value="'.$userinfo.'" />
								<input type="hidden" name="company" value="'.$pcompany.'" />
								<input type="hidden" name="email" value="'.$pemail.'" />
								<input type="hidden" name="picture" value="'.$account->picture.'" />
								<input type="hidden" name="number" value="'.$poffice.'" />
								<input type="hidden" name="www" value="'.$pwww.'" />
               <input class="profilesubmit" type="submit" value="Family Guide to Going Green" /><img src="'.base_path().'files/adobe_pdf_icon.jpg" alt="Adobe PDF" align="absmiddle" />
		</form></td></tr>';
}else{
$green_family_guide  =                  '<tr><td><b>Green Family Guide</b><br />The following directory fields require updating:  <div style="display:inline;color:#900">' . $required. '</td></tr>'; 
}
if(in_array('authenticated user', $account->roles)){
  $title = "Files";
  $header = $account->name;
}
// Architect
if (in_array('architect', $account->roles)){
  if($user->paid > time()){ //paid returns unix timestamp
    $title = 'Architect';
    $header = 'Architect';
    $output = $green_home_tips_pdf.$master_roi_pdf;
    
  }
}

if(in_array('realtor', $account->roles)){
  // Realtor by LM PayPal subscription, admin/lm_paypal
  $sql = "SELECT uid, subid, status, kind FROM {lm_paypal_subscribers} WHERE uid = %d AND subid = %d AND status = 1 AND (kind = 0 OR kind = 2)"; // kind 0 is a role subscription, kind 2 is a grou
  $uid = $account->uid; 

  $subid = '1';
  $subs = db_query($sql, $uid, $subid);

  if(db_num_rows($subs) <= 0) {
    $realtor = false;
  }else{
    $realtor = true;
  $header = 'Realtor';
  $title = "Realtor";
  $output =   $green_home_tips_pdf.$master_roi_pdf.$form_nar_checkup_giftcert.$nar_family_guide.$greenandsave_registeredagent_logo.$rss_feed;
  }  
}

if(in_array('realtor', $account->roles)){
  // Realtor by LM PayPal subscription, admin/lm_paypal
  $sql = "SELECT uid, subid, status, kind FROM {lm_paypal_subscribers} WHERE uid = %d AND subid = %d AND status = 1 AND (kind = 0 OR kind = 2)"; // kind 0 is a role subscription, kind 2 is a grou
  $uid = $account->uid; 

  $subidtrial = '3';
  $substrial = db_query($sql, $uid, $subidtrial);

  if(db_num_rows($substrial) <= 0) {
    $realtor = false;
  }else{
    $realtor = true;
    $header = 'Realtor';
    $title = "Realtor";
    $output =   $green_home_tips_pdf.$master_roi_pdf.$form_nar_checkup_giftcert.$nar_family_guide.$greenandsave_registeredagent_logo.$rss_feed;

  }
}

if(in_array('realtor', $account->roles)){
  $title = "Realtor";
  $header = 'Realtor';
  $realtor = true;
  $output =   $green_home_tips_pdf.$master_roi_pdf.$form_nar_checkup_giftcert.$nar_family_guide.$greenandsave_registeredagent_logo.$rss_feed;
}

if(in_array('contractor', $account->roles)){
  $title = "Contractor";
  $header = 'Contractor';
  $contractor_role = true;
  $output =  $green_home_tips_pdf.$master_roi_pdf;
}
if(in_array('service provider', $account->roles)){
  $title = "Service Provider";
  $header = "Service Provider";
  $service_provider_role = true;
  $output =   $green_home_tips_pdf.$master_roi_pdf;
}

if(in_array('complimentary home inspector', $account->roles)){
  $title = "Home Inspector";
  $header = "Complimentary Home Inspector";
  $complimentary_home_inspector_role = true;
  $output =   $green_home_tips_pdf.$master_roi_pdf.$greenandsave_networkinspector_logo;
}
if(in_array('home inspector', $account->roles)){
  $title = "Home Inspector";
  $header = "Home Inspector";
  $home_inspector_role = true;
  $output =   $green_home_tips_pdf.$master_roi_pdf.$greenandsave_networkinspector_logo;
}
if(in_array('complimentary realtor directory', $account->roles)){
  $title = "Realtor";
  $header = "Complimentary Realtor Directory";
  $complimentary_realtor_dir_role = true;
  $output = '';
}
if(in_array('complimentary realtor', $account->roles)){
  $title = "Realtor";
  $header = "Complimentary Realtor";
  $complimentary_realtor_dir_role = true;
  $output =  $green_home_tips_pdf.$master_roi_pdf.$form_nar_checkup_giftcert.$nar_family_guide.$greenandsave_registeredagent_logo.$rss_feed;
}
if(in_array('NARs Green Designation', $account->roles)){
  $title = "NARs Green Designation";
  $header = "NARs Green Designation";
  $complimentary_realtor_dir_role = true;
  $output =   $green_home_tips_pdf.$master_roi_pdf.$form_nar_green_office_guide.$form_nar_checkup_giftcert.$nar_family_guide.$greenandsave_registeredagent_logo.$rss_feed;
}
$output .= $green_family_guide;


if($header == ''){
 $profilefiles = '';
}else{
   
 $profilefiles = '<table width="600" border="0" cellpadding="6">
  <tr bgcolor="#71A342">
    <th colspan="2"><h2 style="color:#fff">'.$header.' Files</h2></th>
  </tr><tr><td></td><td rowspan="8">Tips for personalizing your guides<p><ol><li>Upload your photo</li><li>Fill in your directory information.  Minimal is first/last name and company.</li><li>Click submit and view your guide</li></ol>
  For fun, you can play with those fields. Change first/last name to "To" "Uncle", company field to "Happy Earth Day!" and upload a photo of your pet.</td></tr>
  
  '. $output.'

</table><br />
<h3>Green Guide Layout</h3>
  <table cellpadding="6"><tr valign="top"><td><img src="'.base_path().'files/greenguide/greenguide-example.jpg" alt="Green Guide with photo" title="Green Guide with photo" />
  <br />Green Guide with photo, first/last name, email address, company, office number and website address (optional).  The photo automatically becomes the link for the website address.</td>
  <td><img src="'.base_path().'files/greenguide/greenguide-example-without-photo.jpg" alt="Green Guide without photo" title="Green Guide without photo" /><br />Green Guide without photo.
  Includes first/last name, company, email address, office number, and web link.</td></tr>
  <tr><td colspan="2"><hr />Not interested in personalizing the guide, but want to view it first?  <a href="http://www.greenandsave.com/greenguide/family.html" title="View online">View online instead!</a></td></tr></table>';
}


if($user->uid == '1'){

}
?>  
    
<h2>We're Updating Your Profile Page!</h2>

 <table width="600" border="0" cellpadding="4">
  <tr bgcolor="#71A342">
    <th colspan="3"><h2 style="color:#fff"><?php print $account->name; ?>'s Directory Information</h2></th>
  </tr>
  <tr valign="top">
    <td width="90" align="right">Name:</td>
    <td><?php print $firstname.' '.$lastname; ?></td>
    <td width="130" rowspan="10">
    <?php  if($account->picture) {
        print theme('user_picture', $account);
        if(is_bool($viewable) === true){ 
          print '<br />Update your photo <a href="'.base_path().'user/'.$account->uid.'/edit" title="Update photo here">here</a>.<p>';
					if($editdirectory){
					  if($directorylink != ''){ ?>
					  <br /><a href="<?php print base_path().$directorylink; ?>" title="My Directory Link"> Directory Link</a>
						<?php }
					}
        }
        print 'Member since: <br />'.(format_date($account->created, 'medium')).'<br />('.(format_interval(time() - $account->created)).')';
        }else{
        if(is_bool($viewable) === true){
          print 'Update your photo <a href="'.base_path().'user/'.$account->uid.'/edit" title="Update photo here">here</a>.<p>';
        }
        print 'Member since: <br />'.(format_date($account->created, 'medium')).'<br />('.(format_interval(time() - $account->created)).')';
      } ?>  </td>
  </tr>
  <tr valign="top">
    <td width="90" align="right">Company: </td>
    <td><?php print $pcompany; ?></td>
  </tr>  <?php
if(is_bool($viewable) === true){ ?>
  <tr valign="top">
    <td width="90" align="right">State: </td>
    <td><?php print $pstate; ?></td>
  </tr>
<?php } ?>
  <tr valign="top">
    <td width="90" align="right">Website: </td>
    <td><?php print $pwww ?></td>
  </tr>
  <?php
if(is_bool($viewable) === true){ ?>
  <tr valign="top">
    <td width="90" align="right">Email: </td>
    <td><?php print  $pemail; ?></td>
  </tr>
  <tr valign="top">
    <td width="90" align="right">Office Number: </td>
    <td><?php print $poffice; ?></td>
  </tr>
  <tr valign="top">
    <td width="90" align="right">Direct Number: </td>
    <td><?php print  $pdirect; ?></td>
  </tr>
  <tr valign="top">
    <td width="90" align="right">Cell Number: </td>
    <td><?php print $pcell; ?></td>
  </tr>
  <tr valign="top">
    <td width="90" align="right">Fax Number: </td>
    <td><?php print $pfax; ?></td>
  </tr>
<?php } ?>
  <tr valign="top">
    <td width="90" align="right">About:</td>
    <td><?php print $pabout; ?></td>
  </tr>

</table>
 
<?php

if(is_bool($viewable) === true){
  print $profilefiles;
}

 
  $output = '';
  $uid = $account->uid;  
  $limit = 10;

  $cresult = db_query_range(db_rewrite_sql("SELECT DISTINCT n.nid, n.title, c.cid, c.subject FROM node n INNER JOIN comments c ON n.nid = c.nid WHERE c.uid = %d AND c.status = 0 ORDER BY c.timestamp DESC"), $uid, 0, $limit);
  $clist = node_title_list($cresult);
 
  
  $presult = db_query_range(db_rewrite_sql("SELECT n.created, n.title, n.nid, n.changed FROM {node} n WHERE n.uid = %d AND n.status = 1 ORDER BY n.changed DESC"), $uid, 0, $limit);
  $plist = node_title_list($presult);
  
  $output .= '<table width="600" border="0" cellpadding="6">
    <tr bgcolor="#71A342">
    <th colspan="2"><h2 style="color:#fff">Recent Posts</h2></th></tr><tr valign="top" >
    <td width="300">';

if( in_array('main editor', $account->roles) ||
    in_array('developer', $account->roles) ||
    in_array('editor', $account->roles) ||
    in_array('general admin', $account->roles) ){
      $output .= '<h3>Content</h3>' .$plist;
}else{
  //display 10 news articles
    $view = views_get_view('news_mostpopular');
    $output .= '<h3>Recent Green News</h3>'. views_build_view('block', $view, $current_view->args, false, 10);
}

    $output .=  '</td><td width="300"><h3>'.$account->name.'\'s Comments</h3>'.$clist.'<td></tr></table>';
  print $output;
  ?>