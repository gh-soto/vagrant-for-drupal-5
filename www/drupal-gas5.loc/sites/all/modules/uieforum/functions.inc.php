<?php
/**
 * Given a uid, return a string representing the user's rank.
 * Thanks to Morbus (IRC) for this code
 */
function uieforum_user_rank($posts = NULL)
{
  global $user;
  if ($posts == NULL || !isset($user->uid)) { return NULL; }

  switch ($posts) {
    case $posts <= 10: return t( variable_get('forum_rank_first', 'n00b') );
    case $posts <= 100:  return t( variable_get('forum_rank_second', 'Killing Spree') );
    case $posts <= 500:  return t( variable_get('forum_rank_third', 'Rampage') );
    case $posts <= 1000: return t( variable_get('forum_rank_fourth', 'Dominating') );
    case $posts <= 4000: return t( variable_get('forum_rank_fifth', 'Unstoppable') );
    case $posts <= 5000: return t( variable_get('forum_rank_sixth', 'Godlike') );
    case $posts < 7000: return t( variable_get('forum_rank_seventh', 'Wicked Sick') );
    case $posts >= 7000: return t( variable_get('forum_rank_eighth', 'Oh Emm Gee') );
  }
}

/** Returns the href link to a thread **/
function get_actual_thread_link($tid)
{
  static $vals = array();
  if(!$vals[$tid])
  {
    $vals[$tid] = "c=showthread&ThreadID=".$tid;
  }
  return $vals[$tid];
}

/** Returns the entire <a> html code for the current thread 
**/
function get_thread_link_text($t, $parseUIEText = true)
{
  static $vals = array();
  if(!$vals[$t->ThreadID])
  {
    $vals[$t->ThreadID] = l(get_thread_title($t, $parseUIEText), uieforum_get_module_menu_name(), null, get_actual_thread_link($t->ThreadID));
  }
  return $vals[$t->ThreadID];
}

/** Returns the link to the last post of a thread
**/
function get_thread_link_text_last_post($t, $objstr, $plus_initial=true, $end_link = false)
{
  $numrows = $t->PostCount;
  $maxPage = uieforum_get_max_page_count($numrows);

  $actual_link = get_actual_thread_link($t->ThreadID);
	$end_link = $actual_link."&page=".$maxPage."#lastpost";
  if($plus_initial) $var = l($objstr, uieforum_get_module_menu_name(), null, $end_link, null, null, true);
	elseif($end_link) $var = $end_link;
  else $var = $actual_link;
  return $var;
}

function get_thread_title($t, $parseUIEText = true)
{
  return uieforum_filter('process', null, null, $t->ThreadTitle, null, $parseUIEText);
}

/** Gets a user from their uid
**/
function get_user_from_id($ID)
{
  if(!is_numeric($ID))
    return false;

  static $vals = array();
  if(!$vals[$ID])
  {
    $query = db_query('SELECT * FROM {users} WHERE uid=%d', $ID);
    if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - get_user_from_id(): '.$e);
    $vals[$ID] =  db_fetch_object($query);
  }
  return $vals[$ID];
}

/** Returns the thread object that contains a specific post
**/
function get_thread_from_post($PostID)
{
  $query = db_query("SELECT {f_threads}.* FROM {f_threads}, {f_posts} WHERE {f_posts}.PostID=%d AND {f_threads}.ThreadID={f_posts}.ThreadID", $PostID);
  if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - get_thread_from_post(): '.$e);
  return db_fetch_object($query);
}

/** Returns the forum object that contains the thread, that contains a specific post
**/
function get_forum_from_post($PostID)
{
  $query = db_query("SELECT {f_forums}.* FROM {f_forums}, {f_threads}, {f_posts} WHERE {f_posts}.PostID=%d AND {f_threads}.ThreadID={f_posts}.ThreadID AND {f_forums}.ForumID={f_threads}.ForumID", $PostID);
  if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - get_forum_from_post(): '.$e);
  return db_fetch_object($query);
}

/** Returns the forum object that contains a specific thread
**/
function get_forum_from_thread($ThreadID)
{
  $query = db_query("SELECT {f_forums}.* FROM {f_forums}, {f_threads} WHERE {f_threads}.ThreadID=%d AND {f_forums}.ForumID={f_threads}.ForumID", $PostID);
  if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - get_forum_from_thread(): '.$e);
  return db_fetch_object($query);
}

/**  Returns a thread object, that corresponds to a given thread id
**/
function get_thread($ThreadID)
{
  $query = db_query("SELECT {f_threads}.* FROM {f_threads} WHERE {f_threads}.ThreadID=%d", $ThreadID);
  if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - get_thread(): '.$e);
  return db_fetch_object($query);
}

/** Returns an array of threads that are contained withing a forum
*/
function get_threads_from_forum($fid, $orderby = true, $pagelimit = true)
{
  static $storageArray = array();

  if(!$storageArray[$fid])
  {
    $Threads = Array();
    $q  = "SELECT {f_threads}.* FROM {f_threads} WHERE {f_threads}.ForumID=%d ";
    $q .= $orderby ? "ORDER BY {f_threads}.Sticky DESC, {f_threads}.LastPost DESC " : "";
    $q .= $pagelimit ? "LIMIT ".uieforum_get_pages_offest().", ".variable_get('forum_per_page', 25) : "";
    $query = db_query($q, $fid);

    $Threads = uieforum_to_array($query);
    $storageArray[$fid] = $Threads;
  }
  return $storageArray[$fid];
}

/** Returns an array of all threads
*/
function get_threads()
{
  $Threads = Array();
  $query = db_query("SELECT * FROM {f_threads}");

  $Threads = uieforum_to_array($query);
  return $Threads;
}

/**
 * Deep Forum get. Adds a .Depth Property to the Forum Object
 */
function get_deep_forums($fid, $recursionDepth = 1)
{
	Global $user;
	$Forums = array();
	$ForumList = get_forums($fid);
	foreach($ForumList AS $Forum)
	{
		$Forum->Depth=$recursionDepth;
		$Forums[] = $Forum;
		if ($recursionDepth == 1 && forum_num_children($Forum->ForumID))
		{
			$Forums = array_merge($Forums, get_deep_forums($Forum->ForumID, ($recursionDepth+1)));
		}
	}
	return $Forums;
}

/** Returns all forums that are within a forum. (that a user has access to)
**/
function get_forums($fid = null, $arr = false, $forcerecount = false, $includeThis = false)
{
  Global $user;
  static $storageArray = array();
  $storageArray = array();
  if(
      ($fid && !$storageArray[$fid])
   || ($forcerecount && (!$fid && !$storageArray[$fid]->ForceRecount) )
   || !$fid
    )
  {
    $Forums = Array();

    if($fid == null && !$forcerecount)
    {
      $limitsql = "SELECT * FROM {f_forums} WHERE ParentForum is null OR ParentForum = 0 ";
    }
    else
    {
      if($arr)
      {
        sort($arr);
      }
      if($forcerecount)
      {
        $limitsql = "SELECT {f_forums}.ForumID, {f_forums}.ForumName, {f_forums}.ParentForum, COUNT(DISTINCT({f_threads}.ThreadID)) AS ThreadCount, COUNT(DISTINCT({f_posts}.PostID)) AS PostCount, '' AS TotalPostCount, '' AS TotalThreadCount FROM {f_forums} LEFT JOIN {f_threads} ON {f_threads}.ForumID={f_forums}.ForumID LEFT JOIN {f_posts} ON {f_posts}.ThreadID={f_threads}.ThreadID " . ( $includeThis ? "" : "WHERE {f_forums}.ParentForum='%d'");
        $limitsql .= " GROUP BY {f_threads}.ForumID";
        $limitsql .= " ORDER BY {f_forums}.ForumID ASC";
//        $limitsql .= " ORDER BY weight, ForumName ASC";
      }
      else
      {
        $limitsql = "SELECT * FROM {f_forums} WHERE {f_forums}.ParentForum=%d";
        if($arr && sizeof($arr) > 0)
        {
          $limitsql .= " OR {f_forums}.ParentForum IN (";
          $inStr = "";
          foreach($arr AS $a)
          {
            // As I don't know how to pass an _array_ of values into db_query, I have to hard code the query with the variables needed to check. (rather than using drupal's %d syntax) - zoro
            $inStr .= "'".$a."', ";
          }
          $inStr = substr($inStr, 0, strlen($inStr)-2) . ")";
          $limitsql .= $inStr . " OR {f_forums}.ForumID='".$fid."'";
        }

        $limitsql .= get_allowed_forums();
        $limitsql .= " ORDER BY weight, ForumName ASC";
      }
    }

    if(!$fid || $forcerecount)
    {
      $query = db_query($limitsql);
    }
    else
    {
      $query = db_query($limitsql, $fid);
    }
    $Forums = uieforum_to_array($query, "ForumID");
    if($forcerecount)
    {
      $maxsize = sizeof($Forums);

      foreach($Forums AS $key => $val)
      {
        $Forums[$key]->ForceRecount = true;
      }
      return $Forums;
    }
    else
    {
      $storageArray[$fid] = $Forums;
    }
  }
  return $storageArray[$fid];
}

/** Returns a MYSQL string to be used to restrict a select statement to only those forums that a given user has access to
**/
function get_allowed_forums($fid = false)
{
   Global $user;
   static $basic;

   if(isset($basic)) return $basic;

   $Forums = Array();
   $allowed = array();
   $Forums = get_all_forums($fid);

   if(!user_access('administer '.uieforum_get_module_security_name()))
   {
     $temparr = Array();
     foreach($Forums as $f)
       $temparr[] = $f->ForumID;
     $groups = get_security_groups(1, $temparr);

     foreach($groups AS $ga)
     {
       if($ga->HasAccess > 0)
       {
         if(user_access($ga->GroupName) && !in_array($ga->HasAccess, $allowed))
         {
           $allowed[] = $ga->HasAccess;
         }
       }
     }
   }
   sort($allowed);

   $alltext = "";
   if(sizeof($allowed) > 0)
   {
     $alltext = " AND {f_forums}.ForumID IN(";
     foreach($allowed AS $all)
       $alltext .= "'".$all."', ";
     $alltext = substr($alltext, 0, strlen($alltext)-2).")";
   }
   if(!$fid) $basic = $alltext;
   return $alltext;
}

/** Returns the number of (direct) children that a forum has. This is NOT recursive
**/
function forum_num_children($fid)
{
  $Forums = Array();
  $query = db_query("SELECT COUNT(ForumID) AS numchildren FROM {f_forums} WHERE {f_forums}.ParentForum=%d;", $fid);
  $Forums = uieforum_to_array($query);
  return $Forums[0]->numchildren;
}

/** Returns an array of all forums as objects.
**/
function get_all_forums()
{
   Global $user;
   $Forums = Array();

   $query = db_query("SELECT STRAIGHT_JOIN DISTINCT ({f_forums}.ForumID), {f_forums}.* FROM {f_forums} ORDER BY ForumName ASC");
   $Forums = uieforum_to_array($query);
   return $Forums;
}

/** Returns a forum object that corresponds to a given forum fid
**/
function get_forum($fid, $list_only = false)
{
  static $forumList = array();

  if(!$forumList[$fid])
  {
    $limitsql = "SELECT * FROM {f_forums} WHERE ForumID=%d";
    $query = db_query($limitsql, $fid);
    if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - get_forum(): '.$e);
    $forumList[$fid] = db_fetch_object($query);
  }
  return $forumList[$fid];
}

/**  Returns a 2D array containing a forum object, and it's indentation int.
     Forums are listed in order of inheritance, weight, and then name.
     eg:
root
-- 1
---- 1a
-- 2
---- 2a
------ 2aa
**/
function get_forums_indented($Forum = null, $indent = 0, $stop_recursion_on_forumid = false, $mod_only = false)
{
  static $found_vals = array();

  $big_array = array();
  if(is_int($Forum)) $Forum = get_forum($Forum, true);
  $ForumList = get_forums($Forum->ForumID);

  if($Forum != null)
  {
    if(!$mod_only || ($mod_only && user_access('moderate '.uieforum_get_module_security_name().'_'.$Forum->ForumID.'_'.uieforum_get_module_security_name())))
    {
      $big_array[] = array($Forum, $indent);
      $found_vals[] = $Forum->ForumID;
    }
  }
  if (sizeof($ForumList) >= 0)
  {
    $indent++;
    foreach($ForumList AS $fo)
    {
      if($fo->ForumID != $stop_recursion_on_forumid)
      {
        $temp_fo = get_forums_indented($fo, $indent, $stop_recursion_on_forumid, $mod_only);

        if(forum_num_children($fo->ForumID) > 0)
        {
          foreach($temp_fo AS $t)
          {
            $big_array[] = $t;
          }
        }
        elseif(!$mod_only || ($mod_only && user_access('moderate '.uieforum_get_module_security_name().'_'.$fo->ForumID.'_'.uieforum_get_module_security_name())))
          $big_array[] = array($fo, $indent);
      }
    }
  }
  return $big_array;
}

/** Returns the list of security groups when fid is false (or null)
    otherwise, it returns the list of security restrictions currently set on a given forum
**/
function get_security_groups($fid = false, $arr = false)
{
   static $generic;
   if(isset($generic)) return $generic;
   $groups = Array();

   if(!$fid)
     $query = db_query("SELECT * FROM {f_groups} ORDER BY GroupID ASC");
   else
   {
     $limitsql = "SELECT g.*, ga.ForumID AS HasAccess FROM {f_groups} g, {f_groups_access} ga WHERE (ga.ForumID=%d";
     if($arr && sizeof($arr) > 0)
     {
       foreach($arr AS $a)
         $limitsql .= " OR ga.ForumID=".$a;
     }
     $limitsql .= ") AND g.GroupID = ga.GroupID";
     $query = db_query($limitsql, $fid);
   }

   $groups = uieforum_to_array($query);
   if($arr) $generic = $groups;

   return $groups;
}

/** Returns the security group that corresponds to a given groupid
**/
function get_security_group($GroupID)
{
   $query = db_query("SELECT * FROM {f_groups} WHERE GroupID=%d", $GroupID);
   return db_fetch_object($query);
}

/** Adds a new security group.
    Param: $GroupName - the name of the group to add
    Param: $GroupShortName - the shortname of the group to add
**/
function add_security_group($GroupName, $GroupShortName)
{
  $query = db_query("INSERT INTO {f_groups} (GroupName, GroupShortName) VALUES ('%s', '%s')", $GroupName, $GroupShortName);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - add_security_group(): '.$e); return $e; }
}

/** Edits an existing security group.
    Param: $GroupID - the id of the group to modify
    Param: $GroupName - the name of the group to modify
    Param: $GroupShortName - the shortname of the group to modify
**/
function edit_security_group($GroupID, $GroupName, $GroupShortName)
{
  $query = db_query("UPDATE {f_groups} SET GroupName='%s', GroupShortName='%s' WHERE GroupID=%d", $GroupName, $GroupShortName, $GroupID);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - edit_security_group(): '.$e); return $e; }
}

/** Deletes a thread, and all posts contained therein
**/
function delete_security_group($GroupID)
{
  $query = db_query("DELETE FROM {f_groups} WHERE GroupID=%d", $GroupID);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - delete_security_group, 1(): '.$e); return $e; }

  $query = db_query("DELETE FROM {f_groups_access} WHERE GroupID=%d", $GroupID);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - delete_security_group, 2(): '.$e); return $e; }
}

/** Returns the number of posts by a user
**/
function get_user_post_page_count($uid)
{
   return uieforum_get_page_count("SELECT COUNT(PostID) AS numrows FROM {f_posts}, {users} WHERE {users}.uid=%d AND {f_posts}.Poster = {users}.uid", $uid);
}

/** Returns the number of threads by a user
**/
function get_user_thread_page_count($uid)
{
   return uieforum_get_page_count("SELECT COUNT(ThreadID) AS numrows FROM {f_threads}, {users} WHERE {users}.uid=%d AND {f_threads}.Creator = {users}.uid", $uid);
}

/** Returns the number of rows in a query
**/
function uieforum_get_page_count($str, $id)
{
  $query = db_query($str, $id);
  if($query == null)
  {
    return 1;
  }
  else
    return uieforum_get_row_count($query);
}

/** Returns the last post time for a user
**/
function get_last_post_time_for_user($uid)
{
   $query = db_query("SELECT {f_posts}.Posted FROM {f_posts} WHERE {f_posts}.Poster=%d ORDER BY {f_posts}.PostID DESC LIMIT 1", $uid);
   $obj = db_fetch_object($query);
   return $obj->Posted;
}

/**
**/
function get_last_forum_post_test($fid)
{
  return db_fetch_object(db_query("SELECT {users}.uid, {users}.name, {f_threads}.ThreadID, MAX(LastPost) AS LastPost FROM {f_threads}, {f_forums}, {users} WHERE {f_forums}.ForumID=%d AND {f_forums}.ForumID={f_threads}.ForumID AND {users}.uid={f_threads}.LastPoster GROUP BY {f_threads}.ThreadID ORDER BY LastPost DESC LIMIT 1", $fid));
}

/** Gets the last post in a forum
**/
function get_last_forum_post($Forum)
{
  $ForumList = get_forums($Forum->ForumID);
  $obj = get_last_forum_post_test($Forum->ForumID);

  $latestpost = $obj->LastPost;
  $forumToReturn = $obj;

  if (sizeof($ForumList) != 0)
  {
    foreach($ForumList AS $fo)
    {
      $PostThingObject = get_last_forum_post_test($fo->ForumID);
      $PostThing = $PostThingObject->LastPost;
      if ($PostThing > $latestpost)
      {
        $latestpost = $PostThing;
        $forumToReturn = $PostThingObject;
      }
    }
  }
  return $forumToReturn;
}

/** Returns the number of rows in a query
**/
function uieforum_get_row_count($var)
{
  $rows = 0;
  if ($row = db_fetch_array($var, MYSQL_ASSOC))
    $rows = $row['numrows'];
  return $rows;  
}

/** Returns how many pages we have when using paging
**/
function uieforum_get_max_page_count($numrows)
{
  return ceil($numrows/variable_get('forum_per_page', 25));
}

function uieforum_get_current_page_link($includepage = false)
{
  static $self = "";
  if(!$self)
  {
    if (uie_id('c'))
    {
      $self .= "&c=".uie_id('c');
      if (uie_id('ThreadID'))
      {
        $self .= "&ThreadID=".uie_id('ThreadID');
      }
    }
    else
    {
      if (uie_id('fid'))
      {
        $self .= "&fid=".uie_id('fid');
      }
    }
  }

  if($includepage && uie_id('page'))
  {
    return $self . "&page=".uie_id('page');
  }
  else
  {
    return $self;
  }
}

/** Returns a string containing page numbers for the current $page and $maxPage
**/
function uieforum_get_page_numbers($numrows)
{
  $page = uie_id("page");

  $self = uieforum_get_current_page_link();

// print 'previous' link only if we're not on page one
  if ($page > 1)
  {
    $prevPageNumber = $page - 1;
    $prev = " ".l('&lt;', uieforum_get_module_menu_name(), null, $self . "&page=".$prevPageNumber, null, null, true);
    $first = " ".l('<strong>&laquo;</strong>', uieforum_get_module_menu_name(), null, $self, null, null, true);
  }
  else
  {
    $prev  = ' &lt; ';       // we're on page one, don't enable 'previous' link
    $first = ' <strong>&laquo;</strong> '; // nor 'first page' link
  }

// print 'next' link only if we're not on the last page
  $maxPage = uieforum_get_max_page_count($numrows);
  if ($page < $maxPage)
  {
    $nextPageNumber = $page + 1;
    $next = " ".l('&gt;', uieforum_get_module_menu_name(), null, $self . "&page=".$nextPageNumber, null, null, true);
    $last = " ".l('<strong>&raquo;</strong>', uieforum_get_module_menu_name(), null, $self . "&page=".$maxPage, null, null, true);
  }
  else
  {
    $next = ' &gt; ';      // we're on the last page, don't enable 'next' link
    $last = ' <strong>&raquo;</strong> '; // nor 'last page' link
  }

  $table = "Page: ";
  $table .= $first . $prev . " <strong>$page</strong> of <strong>$maxPage</strong>" . $next . $last;
  return $table;
}

/** Creates an array of db objects
**/
function uieforum_to_array($val, $key = false)
{
  $array = array();
  if (db_num_rows($val) > 0)
  {
    while ( $Result = db_fetch_object($val) )
    {
      $resArray = (array)($Result);
      if($key)
      {
        $array[$resArray[$key]] = $Result;
      }
      else
      {
        $array[] = $Result;
      }
    } # Loop until we have them all
  }
  return $array;
}

/** Returns an array of the last posts made on the forum module. This _IS_ secured using get_allowed_forums()
**/
function uieforum_get_last_posts($num_posts = 10, $fid=false)
{
   global $user;

  $Threads = Array();
  $limitsql = "SELECT DISTINCT ({f_forums}.ForumID), {f_threads}.*, {f_forums}.ForumName FROM {f_forums}, {f_threads} WHERE";

  if($fid)
    $limitsql .= " {f_forums}.ForumID=%d AND ";

  $limitsql .= " {f_forums}.ForumID={f_threads}.ForumID".get_allowed_forums();
  $limitsql .= " ORDER BY LastPost DESC LIMIT ".$num_posts;
  if($fid)
    $query = db_query($limitsql, $fid);
  else
    $query = db_query($limitsql);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - delete_security_group, 2(): '.$e); }

  $Threads = uieforum_to_array($query);
  return $Threads;
}

/** Returns a post object that corresponds to a given PostID
**/
function uieforum_get_post($PostID)
{
  $query = db_query("SELECT STRAIGHT_JOIN * FROM {f_posts},{users} WHERE PostID=%d and {f_posts}.Poster = {users}.uid", $PostID);
  if( ($e = db_error()) !==0 ) trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_get_post(): '.$e);
  return db_fetch_object($query);
}

/** Returns an array of posts that are contained with a given thread
**/
function uieforum_get_first_postid_from_thread($ThreadID)
{
  static $storageArray = array();

  if(!$storageArray[$ThreadID])
  {
    $q = "SELECT MIN(p.Posted), p.PostID FROM {f_posts} p WHERE ThreadID=%d GROUP BY ThreadID";

    $query = db_query($q, $ThreadID);
    if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php"-uieforum_get_first_postid_from_thread(): '.$e); return $e; }
    $storageArray[$ThreadID] = uieforum_to_array($query);
  }
  return $storageArray[$ThreadID];
}

/** Returns an array of posts that are contained with a given thread
**/
function uieforum_get_posts($ThreadID, $paged = true)
{
  static $storageArray = array();

  if(!$storageArray[$ThreadID])
  {
    $Posts = Array();
    $q = "SELECT * FROM {f_posts} LEFT JOIN {users} ON {f_posts}.Poster = {users}.uid WHERE ThreadID=%d ORDER BY PostID ASC";
    if($paged)
      $q .= " LIMIT ".uieforum_get_pages_offest().", ".variable_get('forum_per_page', 25);

    $query = db_query($q, $ThreadID);
    if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_get_posts(): '.$e); return $e; }
    $Posts = uieforum_to_array($query);
    $storageArray[$ThreadID] = $Posts;
  }
  return $storageArray[$ThreadID];
}

/** Creates a thread
    Param: ThreadTitle - Title of the thread
    Param: ThreadText - Text(content) for the thread
    Param: fid - The forum to add this thread to
**/
function uieforum_create_thread($ThreadTitle, $ThreadText, $fid, $AlwaysEdit = null)
{
  Global $user;
	$result = null;
  $now = uieforum_get_now();

  if( !$ThreadTitle || strlen(trim($ThreadTitle)) <= 0 || !$fid){ trigger_error('UIEForum Error - Tried to create a thread with no title, or no forum id'); return false; }

	$query = db_query("INSERT INTO {f_threads} (ThreadID, ThreadTitle, Creator, Created, LastPoster, LastPost, Locked, Sticky, ForumID) VALUES (NULL,'%s','%d','%s','%d','%s', '%s', '%s', %d)", $ThreadTitle, $user->uid, $now, $user->uid, $now, '0', '0', $fid);
	if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_create_thread, 1(): '.$e); return false; }
	$query = db_query("SELECT LAST_INSERT_ID() AS id");
	if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_create_thread, 2(): '.$e); return false; }
	$result = db_fetch_object($query);

	if(!uieforum_add_post($result->id, $ThreadTitle, $ThreadText, $now, $AlwaysEdit))
  {
		$query = db_query("DELETE FROM {f_threads} WHERE ThreadID=%d LIMIT 1", $result->id);
		if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_create_thread, 3(): '.$e); return false; }
    return false;
  }
  uieforum_update_counts($fid, "forum", "thread", true);

  return $result->id;
}

/** Splits a thread from a given post onwards (inclusive)
    Param: edit - the array containing all relevant details needed for splitting
**/
function uieforum_split_thread($edit)
{
  $tid = $edit['tid'];
  $pid = $edit['pid'];
  $fid = $edit['fid'];
  $poster = $edit['poster'];
  $posted = $edit['posted'];
  $title = $edit['title']." [ Split ]";

  //Create the thread
  $query = db_query("INSERT INTO {f_threads} (ThreadID, ThreadTitle, Creator, Created, LastPoster, LastPost, Locked, Sticky, ForumID) VALUES (NULL,'%s','%d','%s','%d','%s', '%s', '%s', '%d')", $title, $poster, $posted, $poster, $posted, '0', '0', $fid);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_split_thread, 1(): '.$e); return false; }
  $query = db_query("SELECT LAST_INSERT_ID() AS id");
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_split_thread, 2(): '.$e); return false; }
  $result = db_fetch_object($query);

  $newThreadID = $result->id;

  $query = db_query("UPDATE {f_posts} SET ThreadID='%d' WHERE ThreadID='%d' AND PostID >= '%d'", $newThreadID, $tid, $pid);
  if( ($e = db_error()) !==0 ){ trigger_error('SQL error in file "uieforum/functions.inc.php" - uieforum_split_thread, 3(): '.$e); return false; }

  uieforum_update_counts($tid, "thread");
  uieforum_update_counts($newThreadID, "thread");
  uieforum_update_counts($fid, "forum", "thread", true);

  if(uieforum_update_last_post_details($newThreadID) && uieforum_update_last_post_details($tid))
    return $tid;
  else
    return false;
}

function uieforum_update_last_post_details($ThreadID)
{
  //Update the last-post of a thread
  $query = db_query("SELECT * FROM {f_posts} WHERE ThreadID='%d' ORDER BY PostID DESC LIMIT 1", $ThreadID);
  if ($Error = db_error())
  {
    echo "$Error";
    return false;
  }
  $result = db_fetch_object($query);
  $query = db_query("UPDATE {f_threads} SET LastPost='%s', LastPoster='%d' WHERE ThreadID='%d'", $result->Posted, $result->Poster, $ThreadID);
  if ($Error = db_error())
  {
    echo "$Error";
    return false;
  }
  return true;
}

/** Merges 2 threads
    Param: from - the thread to merge into the parent
	Param: to - the parent
**/
function uieforum_merge_thread($from, $to)
{
  $threadFrom = get_thread($from);

  $query = db_query("UPDATE {f_posts} SET ThreadID='%d' WHERE ThreadID='%d'", $to, $from);
  if ($Error = db_error())
  {
    echo "$Error";
    return false;
  }

  $query = db_query("DELETE FROM {f_threads} WHERE ThreadID='%d'", $from);
  if ($Error = db_error())
  {
    echo "$Error";
    return false;
  }

  uieforum_update_counts($to, "thread");
  uieforum_update_counts($to, "thread", "view", $threadFrom->ViewCount);
  uieforum_update_counts($threadFrom->ForumID, "forum", "thread", -1);

  if(uieforum_update_last_post_details($to))
    return $to;
  else
    return false;
}

/** Adds a new post
    Param: ThreadID - Thread to add the new post to
    Param: Title - Title of the post (optional)
    Param: Text - Content of the post
**/
function uieforum_add_post($ThreadID, $Title, $Text, $now = null)
{
  Global $user;
  $Thread = get_thread($ThreadID);
  if(!$now)
  {
    $now = uieforum_get_now();
  }

  if( !$ThreadID || !$Text || strlen(trim($Text)) <= 0){ trigger_error('UIEForum Error - Tried to create a post with either no text or no thread id or neither'); return false; }

  $query = db_query("INSERT INTO {f_posts} (PostID, PostTitle, ThreadID, Poster, Posted, Content) VALUES (NULL,'%s','%d','%d','%s','%s')", $Title, $ThreadID, $user->uid, $now, $Text);
  $Error = db_error();
  $query = db_query("SELECT LAST_INSERT_ID() AS id");
  if ($Error || $Error2 = db_error())
  {
    form_set_error('UIE_FORUM_FORM', $Error?$Error:$Error2);
    return false;
  }
  $result = db_fetch_object($query);
  $ThisPostID = $result->id;

  $query = db_query("UPDATE {f_threads} SET LastPoster='%d', LastPost='%s' WHERE ThreadID=%d", $user->uid, $now, $ThreadID);
  if ($Error = db_error())
  {
    form_set_error('UIE_FORUM_FORM', $Error);
    return false;
  }

  uieforum_update_counts($Thread->ThreadID, "thread", "post", true);
  uieforum_update_counts($Thread->ForumID, "forum", "post", true);

  return $ThisPostID;
}

/** Adds a new Forum
    Param: ForumName - Name of the forum to add
    Param: ForumDesc - Description of the forum to add
    Param: ForumParent - Parent forum of the forum to add
**/
function uieforum_add_forum($ForumName, $ForumDesc, $ForumParent, $weight)
{
  $query = db_query("INSERT INTO {f_forums} (ForumName, ParentForum, ForumDesc, weight) VALUES ('%s', %d, '%s', %d)", $ForumName, $ForumParent, $ForumDesc, $weight);
  if ($Error = db_error())
  {
    echo "$Error";
    return false;
  }

  $query = db_query("SELECT LAST_INSERT_ID() AS id");
  if ($Error = db_error())
  {
    return "Unable to add new forum";
  }

  $obj = db_fetch_object($query);
  if(variable_get('uieforum_auto_greet', 1))
    uieforum_create_thread("Welcome to ".$ForumName, "This is the first thread in your new forum. You can disable this auto-greeting post by disabling the relevant option on the configure page.", $obj->id);
}

/** Deletes a post
**/
function uieforum_delete_post($PostID)
{
  $Post = uieforum_get_post($PostID);
  // Delete the post
  $query = db_query("DELETE FROM {f_posts} WHERE PostID=%d", $PostID);
  if ($Error = db_error())
    return "$Error";

  // Check if the thread that the post came from is empty.
  $Posts = uieforum_get_posts($Post->ThreadID, false);
  $count = count($Posts);
  if ($count <= 0)
  {
    //Delete thread as no more posts exist
    $test = false;
    $test = uieforum_delete_thread($Post->ThreadID);
    if($test)
      return $test;
  }
  else
  {
    //Update thread as the post that was deleted was the last one. The "lastposter" and "lastpost" database entries need to be updated
    $Post = $Posts[$count-1];
    $Thread = get_thread($Post->ThreadID);
    $query = db_query("UPDATE {f_threads} SET LastPoster='%d', LastPost='%s' WHERE ThreadID='%d'", $Post->Poster, $Post->Posted, $Post->ThreadID);

    uieforum_update_counts($Post->ThreadID, "thread", "post", -1);
    uieforum_update_counts($Thread->ForumID, "forum", "post", -1);
    if ($Error = db_error())
      return "$Error";
  }
}

/** Moves a post from one thread to another
**/
function uieforum_move_post($PostID, $ThreadID)
{
  $Post = uieforum_get_post($PostID);
  if( !$PostID || !$ThreadID ){ trigger_error('Unable to move post - did you select a post or a thread to move it to?'); return false;}

  $query = db_query("UPDATE {f_posts} SET ThreadID=%d WHERE PostID=%d", $ThreadID, $PostID);

  if ($Error = db_error())
    return "$Error";

  uieforum_update_last_post_details($ThreadID);
  uieforum_update_last_post_details($Post->ThreadID);

  uieforum_update_counts($Post->ThreadID, "thread", "post", -1);
  uieforum_update_counts($ThreadID, "thread", "post", true);
  return $ThreadID;
}

/** Deletes a thread, and all posts contained therein
**/
function uieforum_delete_thread($ThreadID, $useTotalCounts = false)
{
  $Thread = get_thread($ThreadID);

  $query = db_query("DELETE FROM {f_posts} WHERE ThreadID=%d", $ThreadID);
  if ($Error = db_error())
    return "$q - $Error";

  $query = db_query("DELETE FROM {f_threads} WHERE ThreadID=%d", $ThreadID);
  if ($Error = db_error())
    return "$Error";

  uieforum_update_counts($Thread->ForumID, "forum", ($useTotalCounts ? "totalthread" : "thread"), -1);
  uieforum_update_counts($Thread->ForumID, "forum", ($useTotalCounts ? "totalpost" : "post"), ($Thread->PostCount)*-1);
}

/** Deletes a forum, and all forums, threads and posts contained therein
**/
function uieforum_delete_forum($ForumID)
{
  $ThisForum = get_forum($ForumID);
  $ThisChildren = get_forums($ForumID);
  $tester = false;

  if(sizeof($ThisChildren) > 0)
  {
    foreach($ThisChildren AS $child)
    {
      if($tester = uieforum_delete_forum($child->ForumID))
        return $tester;
    }
  }
  $ThisThreads = get_threads_from_forum($ForumID);

  if(sizeof($ThisThreads) > 0)
  {
    foreach($ThisThreads AS $tt)
    {
      if($tester = uieforum_delete_thread($tt->ThreadID, true))
        return $tester;
    }
  }

  $query = db_query("DELETE FROM {f_groups_access} WHERE ForumID=%d", $ForumID);
  if ($Error = db_error())
    return "$Error";

  if($ThisForum->ParentForum)
  {
    uieforum_update_counts($ThisForum->ParentForum, "forum", "totalpost", ($ThisForum->PostCount)*-1);
    uieforum_update_counts($ThisForum->ParentForum, "forum", "totalthread", ($ThisForum->ThreadCount)*-1);
  }

  $query = db_query("DELETE FROM {f_forums} WHERE ForumID=%d", $ForumID);
  if ($Error = db_error())
    return "$Error";

  if($tester)
    return $tester;
}

/** Edits a thread
    Param: ThreadID - ID of thread to edit
    Param: ThreadTitle - Titleof thread to edit
    Param: Locked - Is Thread locked?
    Param: Sticky - Is Thread stickied?
    Param: ForumID - ForumID of thread to edit
**/
function uieforum_edit_thread($ThreadID, $ThreadTitle, $Locked, $Sticky, $ForumID)
{
  $Thread = get_thread($ThreadID);

  $q = "UPDATE {f_threads} SET ThreadTitle='%s', Locked='%d',Sticky='%d' ";
  if($ForumID)
    $q .= ",ForumID='%d' ";
  $q .= "WHERE ThreadID='%d'";

  if($ForumID)
    $query = db_query($q, $ThreadTitle, $Locked, $Sticky, $ForumID, $ThreadID);
  else
    $query = db_query($q, $ThreadTitle, $Locked, $Sticky, $ThreadID);

  if($Thread->ForumID != $ForumID)
  {
    uieforum_update_counts($Thread->ForumID, "forum", "post", ($Thread->PostCount)*-1);
    uieforum_update_counts($Thread->ForumID, "forum", "thread", -1);
    uieforum_update_counts($ForumID, "forum", "post", $Thread->PostCount);
    uieforum_update_counts($ForumID, "forum", "thread", true);
  }

  if ($Error = db_error())
    return "$Error";
}

function uieforum_edit_thread_title($ThreadID, $ThreadTitle)
{
  if(!$ThreadID || !$ThreadTitle || ($ThreadTitle && strlen(trim($ThreadTitle)) == 0))
  {
    return false;
  }
  $q = "UPDATE {f_threads} SET ThreadTitle='%s' WHERE ThreadID=%d";

  $query = db_query($q, $ThreadTitle, $ThreadID);

  if ($Error = db_error())
    return "$Error";
}

/** Edits a forum
**/
function uieforum_edit_forum($fid,$ForumName,$ForumDesc,$ForumParent=false, $weight = 0, $accessgroups, $Locked)
{
  $query = db_query("SELECT ForumID FROM {f_forums} WHERE ForumID=%d", $fid);
  if ($query == NULL)
    return "No such forum exists.";

  $q="UPDATE {f_forums} SET ForumName='%s', ForumDesc='%s', weight='%d', Locked='%d' ";
  if($ForumParent)
    $q .= ",ParentForum='%d' ";
  $q .= "WHERE ForumID='%d'";

  if($ForumParent)
    $query = db_query($q, $ForumName, $ForumDesc, $weight, $Locked, $ForumParent, $fid);
  else
    $query = db_query($q, $ForumName, $ForumDesc, $weight, $Locked, $fid);
  if ($Error = db_error())
    return "$Error";

  $query = db_query("DELETE FROM {f_groups_access} WHERE ForumID=%d", $fid);
  if ($Error = db_error())
    return "$Error";

  foreach($accessgroups AS $ag)
  {
    if(is_array($ag)) continue;
    $query = db_query("REPLACE INTO {f_groups_access} SET ForumID='%d', GroupID='%d'", $fid, $ag);
    if ($Error = db_error())
      return "$Error";
  }
}

/** Edits a post
**/
function uieforum_edit_post($PostID, $Title, $Text, $editor, $reason, $ThreadID, $AlwaysEdit = null)
{
  $CurrentForum = uieforum_current_forum();
  if(!(user_access('administer '.uieforum_get_module_security_name()) || user_access('moderate '.uieforum_get_module_security_name().'_'.$CurrentForum->ForumID.'_'.uieforum_get_module_security_name()))) {
    $AlwaysEdit = null;
  }
  Global $user;
  $now = uieforum_get_now();
  if(isset($AlwaysEdit)) {
    $query = db_query("UPDATE {f_posts} SET PostTitle='%s', Content='%s', Edit='%s', Editor='%d', EditReason='%s', AlwaysEdit='%d' WHERE PostID=%d", $Title, $Text, $now, $editor, $reason, $AlwaysEdit, $PostID);
  }
  else {
    $query = db_query("UPDATE {f_posts} SET PostTitle='%s', Content='%s', Edit='%s', Editor='%d', EditReason='%s' WHERE PostID=%d", $Title, $Text, $now, $editor, $reason, $PostID);
  }
  if ($Error = db_error())
    return "$Error";

  $firstPost = uieforum_get_first_postid_from_thread($ThreadID);
  if($firstPost && count($firstPost) > 0)
  {
    if($firstPost[0]->PostID == $PostID)
    {
      uieforum_edit_thread_title($ThreadID, $Title);
    }
  }
}

/** Returns the postcount of a user
**/
function uieforum_post_count($UserID)
{
  global $user;
  if (!isset($user->uid)) { return NULL; }

  $query = db_query("SELECT COUNT(PostID) from {f_posts} WHERE Poster=%d", $UserID);
  return db_result($query, 0);
}

function uieforum_apply_filters($text) {
  $filtersEnabled = variable_get('uieforum_enabled_filters', array('uieforum/0') );
  $filtersAvailable = filter_list_all();

  foreach($filtersEnabled as $f) {
    if( array_key_exists( $f, $filtersAvailable) ) {
      $module = $filtersAvailable[$f]->module;
      if($module === 'uieforum') continue;
      $delta = $filtersAvailable[$f]->delta;

      $fname = "{$module}_filter";

      $text = $fname('prepare', $delta, -1, $text);
      $text = $fname('process', $delta, -1, $text);
    }
  }
  return $text;
}

/** Displays a post on screen
**/
function uieforum_display_post($PostTime=0, $ThreadID, $PostID, $uid, $PostTitle, $PostText, $postcount = -1, $IsPreview = false, $fid, $editedpost = false, $sig = false, $show_avatar = true, $show_online_status = true, $show_post_count = true, $show_ranks = true, $show_regd = true, $show_sigs = true)
{
  Global $user;
  $guest_user = (!isset($uid) || $uid == 0 ? true : false);

  if($PostTime == 0)
  {
    $PostTime = uieforum_parse_date(uieforum_get_now());
  }

  $post_header = array(
    array('data' => '', 'colspan' => '1', 'class' => 'created postHeader'),
    array('data' => '', 'colspan' => '1', 'class' => 'forum_pagenums')
  );
  $post_content = array(
    array('data' => '', 'colspan' => '1', 'class' => 'post_user_info'),
    array('data' => '', 'colspan' => '1', 'class' => 'post_text')//, 'style' => 'vertical-align: top')
  );

  if ($IsPreview)
  {
    $post_header = array($post_header[0]);
    $post_content = array($post_content[1]);
    $post_footer = array($post_header[0]);
    $post_header [0]['colspan'] = '2';
    $post_header [0]['data'] = uieforum_filter('process', null, null, $PostTitle, true);
//    $post_content[0]['data'] .= uieforum_filter('process', null, null, $PostText, true);
    $post_content[0]['data'] .= uieforum_apply_filters( uieforum_filter('process', null, null, $PostText, true) );
    $post_content[0]['colspan'] = 2;
  }
  else
  {
    $post_footer = array(
      array('data' => '', 'colspan' => '1', 'class' => 'created postFooter'),
      array('data' => '', 'colspan' => '1', 'class' => 'forum_pagenums')
    );

    $post_header [0]['data'] = $PostTime;
    $Poster = get_user_from_id($uid);


    $post_header [1]['data'] .= l($PostID, uieforum_get_module_menu_name(), array('name' => $PostID), uieforum_get_current_page_link(true).'#'.$PostID);

    if ($postcount == 0) $post_header[1]['data'] .= l(null, null, array('name' => 'lastpost'));

    if(user_access('administer '.uieforum_get_module_security_name()) || user_access('moderate '.uieforum_get_module_security_name().'_'.$fid.'_'.uieforum_get_module_security_name()))
    {
      $post_header [1]['data'] .= drupal_get_form("uieforum_generate_post_admin_form", $PostID, $ThreadID);
    }

    $UserName = $Poster->name;
    $post_content[0]['data'] .= theme('username', user_load(array('uid' => $uid)))."<br /><small>\n";
    if(!$guest_user)
    {
      if($show_ranks)
      {
	      if($Poster->uid == 1)
          $userrank = 'Super Admin';
	      else
          $userrank = uieforum_user_rank(uieforum_uie_storage('postcount', $Poster->uid));
        if($userrank != null)
          $post_content[0]['data'] .= "$userrank<br /><br />\n";
      }
      if(variable_get('uieforum_allow_display_avatars', 1) && $show_avatar) $post_content[0]['data'] .= theme_user_picture($Poster);
      if($show_regd) $post_content[0]['data'] .= "<br />Joined: ".uieforum_parse_date_only($Poster->created);

      if($show_post_count)
      {
        $userpostcount = uieforum_uie_storage('postcount', $Poster->uid);
        if($userpostcount != null)
          $post_content[0]['data'] .= "<br/>Posts: $userpostcount<br /><br />";
      }
      if($show_online_status)
      {
        $is_active = uieforum_uie_storage('useronline', $Poster->uid);
        $post_footer[0]['data'] .= _uieforum_user_online($Poster->name, $is_active);
      }
      if($user->uid || variable_get('forum_report_post_guest_allow', 0))
      {
        $alt = 'Report post to an Admin/Moderator';
        $file = variable_get('forum_icon_path', 'sites/all/modules/'.uieforum_get_module_name().'/uie')."/forum-report.gif";
        $reportPostImage = theme('image', $file, $alt, $alt, '', false);

        if($reportPostImage)
        {
          $reportPost .= l($reportPostImage, uieforum_get_module_menu_name(), null, "c=reportpost&PostID=$PostID", null, null, true);
          $post_footer[0]['data'] .= $reportPost;
        }
      }
    }
    $post_content[0]['data'] .= "</small>\n";




    if (strlen($PostTitle) > 0)
      $post_content[1]['data'] .= "<div class='uieforum_postsubject'>".uieforum_filter('process', null, null, $PostTitle, true)."</div>";
//    $post_content[1]['data'] .= "<div class='uieforum_postcontent'>".uieforum_filter('process', null, null, $PostText, true)."</div>";
    $post_content[1]['data'] .= "<div class='uieforum_postcontent'>".uieforum_apply_filters( uieforum_filter('process', null, null, $PostText, true) )."</div>";
    $post_content[1]['data'] .= "<br/><br/>";
    if(!$guest_user && ($show_sigs && $sig))
      $post_content[1]['data'] .= "<div class=\"signature\">__________________<br/>".uieforum_filter('process', null, null, $sig)."</div>";



    $INTERACTION_TEXT = false;
    if (user_access('post new threads/posts'))
    {
      if(user_access('administer '.uieforum_get_module_security_name()) || user_access('moderate '.uieforum_get_module_security_name().'_'.$fid.'_'.uieforum_get_module_security_name()) || ($user->uid == $uid && $user->uid != 0))
        $INTERACTION_TEXT = l(t('Edit'), uieforum_get_module_menu_name(), null, "c=editpost&PostID=$PostID"). " - ";
      $INTERACTION_TEXT .= l(t('Quote'), uieforum_get_module_menu_name(), null, "c=newpost&ThreadID=$ThreadID&QuoteID=$PostID");
    }
    if($INTERACTION_TEXT) $post_footer[1]['data'] .= $INTERACTION_TEXT;

    if(is_array($editedpost) && $editedpost[0])
    {
      $tempuser = get_user_from_id($editedpost[0]);
      $DEFAULT_EDIT_REASON = trim(uieforum_filter('process', null, null, variable_get('forum_default_edit_reason', ''), true));
      $outputtext = "Last edited by ".$tempuser->name." (".uieforum_parse_date($editedpost[1]).")";

      $edited_reason = uieforum_filter('process', null, null, $editedpost[2], true);
      if(strlen(trim($edited_reason)) <= 0) $edited_reason = $DEFAULT_EDIT_REASON;
      if(strlen($edited_reason) > 0)
        $outputtext .= " Reason: ".$edited_reason;
      $post_content[1]['data'] .= "<hr /><div class='uie_edit_text'><em>".$outputtext."</em></div>";
    }
  }
  return array($post_header, $post_content, $post_footer);
}

/** Returns the current date/time
**/
function uieforum_get_now($days=0)
{
  $Now = time();
  $Time = $Now - (86400*$days); # 86400 = num seconds in a day.
	return $Time;
}

/** Not used at the moment - returns a different date format for rss display
**/
function uieforum_parse_date_rss($d)
{
  return uieforum_parse_date($d, "D, d M Y H:i");
}

/**Returns a formatted date/time
**/
function uieforum_parse_date($d, $format = "d M Y - H:i")
{
  return format_date($d, 'custom', $format);
}

/** Returns a formatted date
**/
function uieforum_parse_date_only($d)
{
  return uieforum_parse_date($d, "d M Y");
}

/** Checks to see if a user has posted too soon since their last post.
**/
function uieforum_posted_too_soon($timeout)
{
  Global $user;
  $time = time();

  $userslastpost = get_last_post_time_for_user($user->uid);

  $ender = $time - $userslastpost;
  if($ender <= $timeout)
    return $ender;
  else
    return false;
}

/** Returns a smiley array
**/
function uieforum_get_smiley_list()
{
  static $smilies_array = array();

  if(sizeof($smilies_array) == 0)
  {
    /** Array of smilies
      contents: text replace   ---   regex to use to replace   ---   what to replace text with
      eg: ':)', ':) regex', 'smile.gif'
    **/
    $smilies_array = array
    (
      array(':idea:', 	'/:(?i)idea:/' ,	_uieforum_icon(null, null, null, 'idea.gif', true)),
      array(':mad:', 	'/:(?i)mad:/'  ,	_uieforum_icon(null, null, null, 'mad.gif', true)),
      array(':huh:', 	'/:(?i)huh:/'  ,	_uieforum_icon(null, null, null, 'confused.gif', true)),
      array(':lol:', 	'/:(?i)lol:/'  ,	_uieforum_icon(null, null, null, 'lol.gif', true)),
/*      array(':))'  , 	'/:-?\)\)/'    ,	_uieforum_icon(null, null, null, 'lol.gif', true)),*/
      array(':)'   , 	'/:-?\)/'      ,	_uieforum_icon(null, null, null, 'smile.gif', true)),
      array(':D'   , 	'/:-?[dD]/'    ,	_uieforum_icon(null, null, null, 'biggrin.gif', true)),
      array(':pirateship:',	'/:(?i)pirateship:/',	_uieforum_icon(null, null, null, 'pirateship.gif', true)),
      array(':p'   , 	'/:-?[pP]/'    ,	_uieforum_icon(null, null, null, 'tongue.gif', true)),
      array(':('   , 	'/:-?\(/'      ,	_uieforum_icon(null, null, null, 'sad.gif', true)),
      array(':cool:',	'/:(?i)cool:/' ,	_uieforum_icon(null, null, null, 'cool.gif', true)),
      array(':crazy:',	'/:(?i)crazy:/',	_uieforum_icon(null, null, null, 'crazy.gif', true)),
      array(':fight:',	'/:(?i)fight:/',	_uieforum_icon(null, null, null, 'fight.gif', true)),
      array(':lurking:',	'/:(?i)lurking:/',	_uieforum_icon(null, null, null, 'lurking.gif', true)),
      array(':rolleyes:',	'/:(?i)rolleyes:/',	_uieforum_icon(null, null, null, 'rolleyes.gif', true)),
      array(':shock:',	'/:(?i)shock:/',	_uieforum_icon(null, null, null, 'shockcombo.gif', true)),
      array(':shoot:',	'/:(?i)shoot:/',	_uieforum_icon(null, null, null, 'shooting.gif', true)),
      array(':sleeping:',	'/:(?i)sleeping:/',	_uieforum_icon(null, null, null, 'sleeping.gif', true)),
      array(':|',    	'/:-?\|/'      ,	_uieforum_icon(null, null, null, 'stern.gif', true)),
      array(';)',    	'/;-?\)/'      ,	_uieforum_icon(null, null, null, 'wink.gif', true)),
    );
  }
  return $smilies_array;
}

/** Order to display smilies in **/
function uieforum_get_smiley_samples()
{
  static $SmilieSamples = array();

  if(sizeof($SmilieSamples) == 0)
  {
    $SmilieSamples = array
    ( 0 => array ( 5, 1, 13, 11),
      1 => array ( 7, 2, 10, 14),
      2 => array ( 4, 3, 17, 12),
      3 => array ( 9, 8, 16, 15),
      4 => array (18)
    );
  }
  return $SmilieSamples;
}

/** Returns a formatting icon array
**/
function uieforum_get_formatting_icons()
{
  $uie_rows = array();
	if(variable_get("uieforum_create_post_display_bbcode_bold", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'bold\')" alt=\'Insert bold text\' title=\'Insert bold text\'>'._uieforum_icon(null, null, null, 'bold.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_italic", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'italic\')" alt=\'Insert italic text\' title=\'Insert italic text\'>'._uieforum_icon(null, null, null, 'italic.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_underline", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'underline\')" alt=\'Insert underlined text\' title=\'Insert underlined text\'>'._uieforum_icon(null, null, null, 'underline.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_url", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'url\')" alt=\'Insert a url\' title=\'Insert a url\'>'._uieforum_icon(null, null, null, 'url.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_image", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'image\')" alt=\'Insert an inline image\' title=\'Insert an inline image\'>'._uieforum_icon(null, null, null, 'image.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_quote", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'quote\')" alt=\'Insert quoted text\' title=\'Insert quoted text\'>'._uieforum_icon(null, null, null, 'quote.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_code", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'code\')" alt=\'Insert pre-formatted code\' title=\'Insert pre-formatted code\'>'._uieforum_icon(null, null, null, 'code.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_rant", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'rant\')" alt=\'Give out about something\' title=\'Give out about something\'>'._uieforum_icon(null, null, null, 'rant.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_left", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'left\')" alt=\'Align paragraph to left\' title=\'Align paragraph to left\'>'._uieforum_icon(null, null, null, 'align_left.jpg', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_center", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'center\')" alt=\'Align paragraph to center\' title=\'Align paragraph to center\'>'._uieforum_icon(null, null, null, 'align_center.gif', null, true).'</a>');
	}
	if(variable_get("uieforum_create_post_display_bbcode_right", 1))
	{
    $uie_rows[] = array('data' => '<a href="javascript:Process(\'right\')" alt=\'Align paragraph to right\' title=\'Align paragraph to right\'>'._uieforum_icon(null, null, null, 'align_right.jpg', null, true).'</a>');
	}
  return array($uie_rows);
}

function uieforum_get_pages_offest()
{
  static $pagesoffset = 0;

  if(is_numeric($pagesoffset) && $pagesoffset <= 0)
    $pagesoffset = ((uie_id("page") > 0 ? uie_id("page") - 1 : 0) ) * variable_get('forum_per_page', 25);
  return $pagesoffset;
}

function uieforum_get_forum_list_header()
{
  static $forum_forum_list_header = array();

  if(sizeof($forum_forum_list_header) == 0)
  {
    $forum_forum_list_header = array(
      array('data' => '&nbsp;', 'class' => 'created', 'class' => 'uieforumicon uieforum_nopadding'),
      array('data' => t('Forum'), 'class' => 'created uieheader'),
      array('data' => t('Topics'), 'class' => 'numtopics uieheader'),
      array('data' => t('Posts'), 'class' => 'numposts uieheader'),
      array('data' => t('Last Post'), 'class' => 'uieforum-last-reply uieheader')
    );
  }
  return $forum_forum_list_header;
}

function uieforum_get_topic_list_header()
{
  static $forum_topic_list_header = array();

  if(sizeof($forum_topic_list_header) == 0)
  {
    $forum_topic_list_header = array(
      array('data' => '&nbsp;', 'class' => 'uieforumicon uieforum_nopadding'),
      array('data' => t('Thread'), 'class' => 'created uieheader'),
      array('data' => t('Replies'), 'class' => 'numposts uieheader'),
      array('data' => t('Views'), 'class' => 'numposts uieheader'),
      array('data' => t('Last Post'), 'class' => 'uieforum-last-reply uieheader')
    );
  }
  return $forum_topic_list_header;
}

/**
 * Generates a 'userbar' containing navigation, user name, last visit, PM-box ...
 */
function uieforum_get_forum_user_header($breadcrumbs = "")
{
  global $user;

  if (count($breadcrumbs) < 1) {
  	//# assuming we only have text, so we show it.
  	$navCell = array('data' => $breadcrumbs, 'class' => 'navigation');
  } elseif (count($breadcrumbs) <= 2)  {
  	//# hide the 'Home' entry
		array_shift($breadcrumbs);
  	//# only one entry, don't show any trail
  	$navCell = array('data' => '<span class="current">'.$breadcrumbs[0].'</span>', 'class' => 'navigation');
  } else {
  	//# hide the 'Home' entry
		array_shift($breadcrumbs);
  	//# extract the last entry for big ol' display
  	$current = array_pop($breadcrumbs);
  	//# build the trail with the remains
  	$trail = '<span class="trail">'.implode($breadcrumbs, "&nbsp;>&nbsp;").'&nbsp;>&nbsp;</span>';
  	$trail .= "<br />";
  	$trail .= '<span class="current">'.$current.'</span>';
  	$navCell = array('data' => $trail, 'class' => 'navigation');
  }
  
  //# Create Welcome content
  if ($user->uid) {
    $welcome  = '<span class="welcomeText">'.t('Welcome')." {$user->name}.</span><br />";
    $welcome .= t('Last visit').": ".uieforum_parse_date(_uieforum_user_last_visit())."<br />";
    //# Check for privatemsg installation
    if (module_exists('privatemsg')) {
      $msgNew = _privatemsg_get_new_messages();
      $msgTotal = _privatemsg_get_messages();
      $welcome .= l(t("Private messages: "),'privatemsg/inbox').$msgNew." of ".$msgTotal." total.";
    }
  } else {
    if (variable_get('user_register', 1)) {
      $welcome = t('<a href="@login">Login</a> or <a href="@register">register</a> to post.', array('@login' => url('user/login'), '@register' => url('user/register')));
    }
    else {
      $welcome = t('<a href="@login">Login</a> to post comments', array('@login' => url('user/login')));
    }
  }
  

  
  //# Generate Table
  $table = array(
  	array(
    	$navCell,
    	array('data' => $welcome, 'class' => 'welcome'),
    )
	);
	$result = theme('table', null, $table, array('class' => 'forum userbar'));
	
	if (uie_id('c') == 'showthread') 
	{
		$result = "<div class=\"post-container\">".$result."</div>";
	}
	
  return $result;
}

//# The API of privatemsg isn't done, so we do it on our own.
function _privatemsg_get_messages($uid = 0) {
  global $user;
  static $cache = array();
  if ($uid == 0) {
    $uid = $user->uid;
  }
  if (!isset($cache[$uid])) {
    $cache[$uid] = (int)db_result(db_query('SELECT COUNT(*) FROM {privatemsg} WHERE recipient = %d AND recipient_del = 0', $uid));
  }
  return $cache[$uid];
}

/** Checks to see of a post is being edited
**/
function uieforum_is_edit_post()
{
  static $is_edit = false;

  if(!$is_edit)
    $is_edit = isset($edit['UIE_FORUM_EDIT_POST']) ? true : false;
  return $is_edit;
}


function uieforum_get_post_always_edit($init = false)
{
  global $edit;
  static $AlwaysEdit = null;

  if($init)
    $AlwaysEdit = trim($init);
  elseif(isset($edit['AlwaysEditOption']))
    $AlwaysEdit = trim($edit['AlwaysEditOption']);
  return $AlwaysEdit;
}

function uieforum_get_post_text($init = false)
{
  global $edit;
  static $PostText = false;

  if($init)
    $PostText = trim($init);
  elseif(isset($edit['PostText']))
    $PostText = trim($edit['PostText']);
  return $PostText;
}

function uieforum_get_post_title($init = false)
{
  global $edit;
  static $PostTitle = false;

  if($init)
    $PostTitle = trim($init);
  elseif(isset($edit['PostTitle']))
    $PostTitle = trim($edit['PostTitle']);
  return $PostTitle;
}

function uieforum_get_edit_reason()
{
  global $edit;
  static $EditReason = false;

  if(isset($edit['EditReason']))
    $EditReason = trim($edit['EditReason']);
  return $EditReason;
}

function uieforum_current_forum($fid = false, $force = false)
{
  static $CurrentForum = false;

  if($force || (!$CurrentForum && ($fid)))
  {
    if(!$fid && $CurrentForum) $fid = $CurrentForum->ForumID;
    $CurrentForum = get_forum($fid);
  }
  return $CurrentForum;
}

function uieforum_current_thread($id = false, $method='normal')
{
  static $CurrentThread = false;

  if($id)
  {
    switch($method)
    {
      case 'post': $CurrentThread = get_thread_from_post($id); break;
      case 'normal': $CurrentThread = get_thread($id); break;
    }
  }
  return $CurrentThread;
}

function uieforum_get_thread_post_count($threadid)
{
  $query = db_fetch_object(db_query("SELECT COUNT(DISTINCT({f_posts}.PostID)) AS PostCount FROM {f_posts} WHERE {f_posts}.ThreadID=%d", $threadid));
  return $query->PostCount;
}

/**  Returns the number of threads/posts in a forum
**/
function uieforum_get_forum_info($init = false, $checkThisParent = false, $withThisForum = false)
{
  static  $checked = array(),
          $storageArray = array(),
          $list = array(),
          $masterOrder = array(),
          $masterOrderManipulation = array();

  if($init)
  {
    $masterOrder = get_forums_indented();
    usort($masterOrder, 'uieforum_sort_forums_for_recounts');
    $masterOrderManipulation = $masterOrder;

    $list = get_forums(null, null, true, true);
  }
  if($checkThisParent)
  {
    foreach($masterOrderManipulation AS $key => $fn)
    {
      $fn = $fn[0];
      $key = $fn->ForumID;
      if($withThisForum)
      {
        $fn = $list[$withThisForum];
        $key = $withThisForum;
      }
      if($fn->ParentForum == $checkThisParent || $withThisForum)
      {
        $list[$checkThisParent]->TotalThreadCount += $fn->ThreadCount;
        $list[$checkThisParent]->TotalPostCount += $fn->PostCount;

        return $list[$checkThisParent]->ParentForum;
      }
    }
    return null;
    $key = $fn = null;
  }


  foreach($masterOrder AS $key => $fn)
  {
    $fn = $fn[0];
    $key = $fn->ForumID;
    if(!$checked[$key] )
    {
      $checked[$key] = 1;

      $key2 = $key;

      while( $key2 = uieforum_get_forum_info(null, $key2, $key) )
      {}
    }
  }

  return $list;
}

function uieforum_update_all_counts($type, $fid = false, $tid = false)
{
  if(user_access('administer '.uieforum_get_module_security_name()))
  {
    if($type == "forums")
    {
      $q = "UPDATE {f_forums} SET ThreadCount=0, PostCount=0, TotalThreadCount=0, TotalPostCount=0";
      $query = db_query($q);
      $Forums = uieforum_get_forum_info(true);

      uieforum_update_counts($Forums, "forum");
    }
    elseif($type == "threads")
    {
      if($fid)
      {
        $Threads = get_threads_from_forum($fid, false, false);
      }
      elseif($tid)
      {
        $Threads = array();
        $Threads[] = get_thread($tid);
      }
      else
      {
        $Threads = get_threads();
      }

      foreach($Threads as $t)
      {
        uieforum_update_counts($t->ThreadID, "thread");
      }
    }
  }
  return;
}

function uieforum_update_counts($id, $type, $counttype = false, $increment = false, $currentForum = true)
{
  switch($type)
  {
    case 'forum':
      if($increment == 0)
      {
        //In this case, "id" is an array of all the forums in the system, with their counts pre-calculated
        foreach($id AS $f)
        {
          $q = "UPDATE {f_forums} SET ThreadCount='%d', PostCount='%d', TotalThreadCount='%d', TotalPostCount='%d' WHERE ForumID=%d";
          $query = db_query($q, $f->ThreadCount, $f->PostCount, $f->TotalThreadCount, $f->TotalPostCount, $f->ForumID);
        }
        return;
      }
      elseif($counttype)
      {
        $f = get_forum($id);
        if($counttype == "thread" || $counttype == "totalthread")
        {
          $q = "UPDATE {f_forums} SET TotalThreadCount='%d' WHERE ForumID=%d";
          $query = db_query($q, $f->TotalThreadCount + $increment, $id);

          if($currentForum && $counttype != "totalthread")
          {
            $q = "UPDATE {f_forums} SET ThreadCount='%d' WHERE ForumID=%d";
            $query = db_query($q, $f->ThreadCount + $increment, $id);
          }
        }
        elseif($counttype == "post" || $counttype == "totalpost")
        {
          $q = "UPDATE {f_forums} SET TotalPostCount='%d' WHERE ForumID=%d";
          $query = db_query($q, $f->TotalPostCount + $increment, $id);

          if($currentForum && $counttype != "totalpost")
          {
            $q = "UPDATE {f_forums} SET PostCount='%d' WHERE ForumID=%d";
            $query = db_query($q, $f->PostCount + $increment, $id);
          }
        }
      }
      else
      {
        return false;
      }
      if($f->ParentForum)
      {
        // Prevent counts of threads and posts from bubbling up to parent forum.
        // Increment totalthread and totalpost (which are SUPPOSED to include all subforums) only.
				// Fixed with thanks to Vallenwood
        if ($counttype == "thread") $counttype = 'totalthread';
        if ($counttype == "post") $counttype = 'totalpost';
        uieforum_update_counts($f->ParentForum, $type, $counttype, $increment);
      }
      break;

    case 'thread':
      $t = get_thread($id);
      $field = "PostCount";

      if($increment == 0)
      {
        $info = uieforum_get_thread_post_count($id);
        if($info < 0)
        {
          $info = 0;
        }
      }
      elseif($counttype)
      {
        if($counttype == "post")
        {
          $info = $t->PostCount + $increment;
        }
        if($counttype == "view")
        {
          $field = "ViewCount";
          $info = $t->ViewCount + $increment;
        }
      }
      else
      {
        return false;
      }
      $q = "UPDATE {f_threads} SET ".$field."='%d' WHERE ThreadID=%d";
      $query = db_query($q, $info, $id);
      break;

    default:
  }
  if ($Error = db_error())
  {
    echo $Error;
    return false;
  }
}


function uieforum_uie_storage($source, $uid, $reset = false)
{
  static $postcount_list = array();
  static $useronline_list = array();
  if($reset)
  {
    $postcount_list = array();
    $useronline_list = array();
  }
  else
  {
    switch($source)
    {
      case 'postcount':
        if(!isset($postcount_list["'".$uid."'"]))
          $postcount_list["'".$uid."'"] = uieforum_post_count($uid);
	if($uid == 75) return $postcount_list["'".$uid."'"] - 549 - 999;
        return $postcount_list["'".$uid."'"];

      case 'useronline':
        if(!isset($useronline_list["'".$uid."'"]))
        {
          // Count users with activity in the past defined period.
          $time_period = variable_get('user_block_seconds_online', 2700);
          $calc = time() - $time_period;

          // Perform database queries to gather online user lists (used to see if the poster is currently online)
          $users = db_query('SELECT DISTINCT(uid), MAX(timestamp) AS max_timestamp FROM {sessions} WHERE timestamp >= %d AND uid = %d GROUP BY uid ORDER BY max_timestamp DESC', $calc, $uid);
          $total_users = db_num_rows($users);

          $is_active = false;
          if ($total_users == 1)
            $is_active = true;
          $useronline_list["'".$uid."'"] = $is_active;
        }
        return $useronline_list["'".$uid."'"];
    }
  }
}

/* Reverse order, depending on their forum depth */
function uieforum_sort_forums_for_recounts($a, $b)
{
  $a_Obj = $a[0];
  $b_Obj = $b[0];

  $a_Depth = $a[1];
  $b_Depth = $b[1];

  if ($a_Depth == $b_Depth)
  {
    return 0;
  }
  return ($a_Depth < $b_Depth) ? 1 : -1;
}

function uieforum_submit_post_form($editpost = false)
{
	/** Text input areas **/
	$uieforum_form = array();


  if($editpost)
  {
    $CurrentForum = uieforum_current_forum();
    if(user_access('administer '.uieforum_get_module_security_name()) || user_access('moderate '.uieforum_get_module_security_name().'_'.$CurrentForum->ForumID.'_'.uieforum_get_module_security_name()))
    {
      $uieforum_form["AlwaysEdit"] = array(
        '#type' => 'fieldset',
        '#title' => t("Always Edit"),
        '#description' => t('Enabling this allows editing of this post - regardless of the system\'s post-edit timeout settings'),
        '#attributes' => null,
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $uieforum_form["AlwaysEdit"]["AlwaysEditOption"] = array(
        '#type' => 'radios',
        '#default_value' => uieforum_get_post_always_edit(),
        '#title' => t('Allow Always Edit'),
        '#options' => array(1 => 'Yes', 0 => 'No')
      );
    }

    $uieforum_form['EditReason'] = array(
      '#type' => 'textfield',
      '#title' => t('Reason for editing'),
      '#default_value' => uieforum_get_edit_reason(),
      '#description' => t('Reason for editing.'),
      '#attributes' => NULL,
      '#required' => FALSE,
    );
  }

  $uieforum_form['PostTitle'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => uieforum_get_post_title(),
    '#description' => t('Title to be used.'),
    '#attributes' => NULL,
    '#required' => uie_id('ThreadID') ? FALSE : TRUE,
  );
  $uieforum_form['PostText'] = array(
    '#type' => 'textarea',
    '#title' => t('Post'),
    '#default_value' => uieforum_get_post_text(),
    '#description' => t('Enter your new post here.'),
    '#attributes' => NULL,
    '#required' => TRUE,
  );

  $uieforum_form['PreviewPost'] = array(
    '#type' => 'submit',
    '#value' => t('Preview'),
    '#name' => 'PreviewPost',
    '#attributes' => array('accesskey' => 'p'),
  );

  $uieforum_form['NewPost'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
    '#name' => 'NewPost',
    '#attributes' => array('accesskey' => 's'),
  );

  $uieforum_form['UIE_FORUM_FORM'] = array(
    '#type' => 'hidden',
    '#value' => 1,
  );

  if($editpost)
  {
    $uieforum_form['UIE_FORUM_EDIT_POST'] = array(
      '#type' => 'hidden',
      '#value' => 1,
    );
  }

  $uieforum_form['fid'] = array(
    '#type' => 'hidden',
    '#value' => uie_id('fid'),
  );

  if(uie_id('ThreadID'))
  {
    $uieforum_form['ThreadID'] = array(
      '#type' => 'hidden',
      '#value' => uie_id('ThreadID'),
    );
  }
  if(uie_id('PostID'))
  {
    $uieforum_form['PostID'] = array(
      '#type' => 'hidden',
      '#value' => uie_id('PostID'),
    );
  }
	$uieforum_form['#attributes'] = array("name" => "unreal");
	return $uieforum_form;
}

function uieforum_submit_report_post_form($editpost = false)
{
	/** Text input areas **/
	$uieforum_form = array();
  if(variable_get('forum_report_post_msg', '')) {
    $uieforum_form['ReportPostMessage'] = array(
      '#type' => 'textarea',
      '#title' => t('Post'),
      '#description' => t('Enter your reason for reporting this post here (optional)'),
      '#attributes' => NULL,
//      '#required' => variable_get('forum_report_post_require_comment', 0) ? TRUE : FALSE,
      '#required' => FALSE,
    );
  }

  $refererLinkage = uieforum_get_module_menu_name();
  if($_SERVER['HTTP_REFERER']) {
    $refererLinkage = $_SERVER['HTTP_REFERER'];
  }
  $uieforum_form['ReportPostReferer'] = array(
    '#type' => 'hidden',
    '#value' => $refererLinkage,
    '#attributes' => NULL,
  );

	$uieforum_form['confirmed'] = array(
		'#type' => 'submit',
    '#name' => 'confirm',
    '#value' => t('Yes'),
	);
	$uieforum_form['#attributes'] = array("id" => "unreal", "name" => "unreal");
	return $uieforum_form;
}

function uieforum_generate_post_admin_form($PostID, $ThreadID)
{
	$dropdownmenu = array(
		null => t($PostID),
		url('admin/content/'.uieforum_get_module_menu_name().'/edit/post/'.$PostID)."?destination=".$_SERVER['REDIRECT_QUERY_STRING'] => t('Delete Post'),
		url('admin/content/'.uieforum_get_module_menu_name().'/edit/post/move/'.$PostID)."?destination=".$_SERVER['REDIRECT_QUERY_STRING'] => t('Move Post'),
		url('admin/content/'.uieforum_get_module_menu_name().'/split/thread/'.$PostID)."?destination=".$_SERVER['REDIRECT_QUERY_STRING'] => t('Split Thread'),
		url('admin/content/'.uieforum_get_module_menu_name().'/merge/thread/'.$ThreadID)."?destination=".$_SERVER['REDIRECT_QUERY_STRING'] => t('Merge Thread')
/*
		t(url('/admin/'.uieforum_get_module_menu_name().'/post/edit/'.$PostID)) => t('Delete Post'),
		t(url('/admin/'.uieforum_get_module_menu_name().'/post/move/'.$PostID)) => t('Move Post'),
		t(url('/admin/'.uieforum_get_module_menu_name().'/thread/split/'.$PostID)) => t('Split Thread'),
		t(url('/admin/'.uieforum_get_module_menu_name().'/thread/merge/'.$ThreadID)) => t('Merge Thread')
*/
	);

	$forum_TempForm = null;
	$unique = "admin_edit_$PostID";
	$forum_TempForm[$unique] = array(
		'#type' => 'select',
		'#title' => null,
		'#default_value' => $PostID,
		'#options' => $dropdownmenu,
		'#description' => null,
		'#attributes' => array('onChange' => "forum_admin_edit('$unique', this)"),
		'#id' => $unique
	);

	$forum_TempForm['#method'] = 'post';
	$forum_TempForm['#action'] = null;
	$forum_TempForm['#attributes'] = array('name' => $unique);
	return $forum_TempForm;
}

function sp_debug($var)
{
	print "<pre>var_dump:";
	var_dump($var);
	print "\n\ndebug_backtrace:";
	debug_print_backtrace();
	exit();
}
