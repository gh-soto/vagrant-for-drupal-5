<?php
// $Id: bbcode-filter.inc,v 1.2.2.1.2.4 2008/01/09 22:50:17 zoro Exp $

function _uieforum_bbcode_filter_process(&$body, $format = -1) {
  
  $quote_text = t('Quote');
  $quote_user = t('Quote \\1');

  $preg = array(
    
    // Font, text and alignment
    '#\[color=(.*?)(?::\w+)?\](.*?)\[/color(?::\w+)?\]#si'   => '<span style="color:\\1">\\2</span>',
    '#\[size=(.*?)(?::\w+)?\](.*?)\[/size(?::\w+)?\]#si'     => '<span style="font-size:\\1">\\2</span>',
    '#\[font=(.*?)(?::\w+)?\](.*?)\[/font(?::\w+)?\]#si'     => '<span style="font-family:\\1">\\2</span>',
    '#\[align=(.*?)(?::\w+)?\](.*?)\[/align(?::\w+)?\]#si'   => '<div style="text-align:\\1">\\2</div>',
    '#\[float=(.*?)(?::\w+)?\](.*?)\[/float(?::\w+)?\]#si'   => '<div style="float:\\1">\\2</div>',
    '#\[b(?::\w+)?\](.*?)\[/b(?::\w+)?\]#si'                 => '<span style="font-weight:bold">\\1</span>',
    '#\[i(?::\w+)?\](.*?)\[/i(?::\w+)?\]#si'                 => '<span style="font-style:italic">\\1</span>',
    '#\[u(?::\w+)?\](.*?)\[/u(?::\w+)?\]#si'                 => '<span style="text-decoration:underline">\\1</span>',
    '#\[center(?::\w+)?\](.*?)\[/center(?::\w+)?\]#si'       => '<div style="text-align:center">\\1</div>',
    '#\[left(?::\w+)?\](.*?)\[/left(?::\w+)?\]#si'           => '<div style="text-align:left">\\1</div>',
    '#\[right(?::\w+)?\](.*?)\[/right(?::\w+)?\]#si'         => '<div style="text-align:right">\\1</div>',
    
    // Links without a protocol, with a protocol and with good looking text
    '#\[url(?::\w+)?\]www\.(.*?)\[/url(?::\w+)?\]#si'        => '<a href="http://www.\\1" class="bb-url" target="_blank">\\1</a>',
    '#\[url(?::\w+)?\](.*?)\[/url(?::\w+)?\]#si'             => '<a href="\\1" class="bb-url" target="_blank">\\1</a>',
    '#\[url=(.*?(?::\w+)?)\](.*?)\[/url(?::\w+)?\]#si'       => '<a href="\\1" class="bb-url" target="_blank">\\2</a>',

    // Pre, Code + Rant
    //'#\[pre(?::\w+)?\](.*?)\[/pre(?::\w+)?\]#si'                 => '<div class="uie_pre">\\1</div>',
    //'#\[code(?::\w+)?\](.*?)\[/code(?::\w+)?\]#si'                 => '<div class="uie_pre">'._uieforum_bbcode_code_tag('\\1').'</div>',
    '#\[rant(?::\w+)?\](.*?)\[/rant(?::\w+)?\]#si'                 => '<div class="rant">\\1</div>',

    // Images without or with client-side sizing
    '#\[img(?::\w+)?\](.*?)\[/img(?::\w+)?\]#si'             => '<img src="\\1" class="bb-image" />',
    '#\[img=(.*?)\]#si' => '<img src="\\1" class="bb-image" />',
    
    // Quoting with or without specifying the source
//    '#\[quote(?::\w+)?\](?:[\r\n])*(.*?)\[/quote(?::\w+)?\]#si'         => '<div class="quote">'.$quote_text.':<br />\\1</div>',
//    '#\[quote=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\](?:[\r\n])*(.*?)\[/quote(?::\w+)?\]#si'   => '<div class="quote">'.$quote_user.':<br />\\2</div>',

    '#\[quote=(.+?)\]#si'                                    => '<div class="quote">'.$quote_user.':<br />',
    '#\[quote\]#si'                                          => '<div class="quote">'.$quote_text.':<br />',
    '#\[\/quote\]#si'                                        => '</div>',

    
    // Different kinds of lists and list items
    '#\[\*(?::\w+)?\](.+)(?=\[\*(?::\w+)?\]|\[/list)#Usi'    => '<li>\\1</li>',
    '#\[list(?::\w+)?\](.*?)\[/list(?::\w+)?\]#si'           => _uieforum_bbcode_list_tag(),
    '#\[list(?::\w+)?\](.*?)\[/list:u(?::\w+)?\]#s'          => _uieforum_bbcode_list_tag(),
    '#\[list(?::\w+)?\](.*?)\[/list:o(?::\w+)?\]#s'          => _uieforum_bbcode_list_tag('decimal'),
    '#\[list=1(?::\w+)?\](.*?)\[/list(?::\w+)?\]#si'         => _uieforum_bbcode_list_tag('decimal'),
    '#\[list=i(?::\w+)?\](.*?)\[/list(?::\w+)?\]#s'          => _uieforum_bbcode_list_tag('lower-roman'),
    '#\[list=I(?::\w+)?\](.*?)\[/list(?::\w+)?\]#s'          => _uieforum_bbcode_list_tag('upper-roman'),
    '#\[list=a(?::\w+)?\](.*?)\[/list(?::\w+)?\]#s'          => _uieforum_bbcode_list_tag('lower-alpha'),
    '#\[list=A(?::\w+)?\](.*?)\[/list(?::\w+)?\]#s'          => _uieforum_bbcode_list_tag('upper-alpha'),
    
    // Clean up the list output a bit
    '#<ol(.*?)>(?:.*?)<li(.*?)>#si'         => '<ol\\1><li\\2>',
    '#<ul(.*?)>(?:.*?)<li(.*?)>#si'         => '<ul\\1><li\\2>'
  );
  $body = preg_replace(array_keys($preg), array_values($preg), $body);

  // We cannot evaluate the variable in callback function because there is no way to pass the $format variable
  if (variable_get("bbcode_encode_mailto_$format", 1)) {
    // Replacing email addresses with encoded html
    $body = preg_replace_callback('#\[email(?::\w+)?\](.*?)\[/email(?::\w+)?\]#si', '_uieforum_bbcode_encode_mailto', $body);
    $body = preg_replace_callback('#\[email=(.*?)(?::\w+)?\](.*?)\[/email(?::\w+)?\]#si', '_uieforum_bbcode_encode_mailto', $body);
  }
  else {
    $body = preg_replace(
      array('#\[email(?::\w+)?\](.*?)\[/email(?::\w+)?\]#si','#\[email=(.*?)(?::\w+)?\](.*?)\[/email(?::\w+)?\]#si'),
      array('<a href="mailto:\\1" class="bb-email">\\1</a>', '<a href="mailto:\\1" class="bb-email">\\2</a>'),
      $body);
  }

  // Horizontal delimiter
  $body = str_replace("[hr]", '<hr class="bb-hr" />', $body);

  // Find all [code] tags and check if they contain a newline. In case we find a newline,
  // that [code] should be rendered as a block, otherwise it will still be inline
  if (preg_match_all('#\[code(?::\w+)?\](.*?)\[/code(?::\w+)?\]#si', $body, $code_tags, PREG_SET_ORDER)) {
      foreach ($code_tags as $code_tag) {
          if (strpos($code_tag[1], "\n") !== FALSE) {
              $code_tag[1] = str_replace(array("\n ", "  ", "\n"), array("<br />&nbsp;", " &nbsp;", "<br />"), trim($code_tag[1]));
              $code_block = '<div class="bb-code-block">%s</div>';
          } else {
              $code_block = '<code class="bb-code">%s</code>';
          }
          $body = str_replace($code_tag[0], sprintf($code_block, $code_tag[1]), $body);
      }
  }
  
  /**
   * - Goes through the given string, and replaces xxxx://yyyy with an HTML <a> tag linking
   *  to that URL
   * - Goes through the given string, and replaces www.xxxx.yyyy[zzzz] with an HTML <a> tag linking
   *  to http://www.xxxx.yyyy[/zzzz]
   * - Goes through the given string, and replaces xxxx@yyyy with an HTML mailto: tag linking
   *    to that email address
   * - Only matches these 2 patterns either after a space, or at the beginning of a line
   *
   * Notes: the email one might get annoying - it's easy to make it more restrictive, though.. maybe
   * have it require something like xxxx@yyyy.zzzz or such. We'll see.
   */

  // pad it with a space so we can match things at the start of the 1st line.
  $ret = ' ' . $body;

  // matches an "xxxx://yyyy" URL at the start of a line, or after a space.
  // xxxx can only be alpha characters.
  // yyyy is anything up to the first space, newline, comma, double quote or <
  $ret = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3">\2://\3</a>', $ret);

  // matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
  // Must contain at least 2 dots. xxxx contains either alphanum, or "-"
  // zzzz is optional.. will contain everything up to the first space, newline,
  // comma, double quote or <.
  $ret = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3">\2.\3</a>', $ret);

  // matches an email@domain type address at the start of a line, or after a space.
  // Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
  if (variable_get("bbcode_encode_mailto_$format", 1)) {
    $ret = preg_replace_callback("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", '_uieforum_bbcode_encode_mailto', $ret);
  }
  else {
    $ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
  }

  // Remove our padding..
  $ret = substr($ret, 1);

  return $ret;
}

function _uieforum_bbcode_encode_mailto($matches) {
  if (isset($matches[3])) {
    $link = 'document.write(\'<a href="mailto:' . $matches[2].'@'.$matches[3] . '">' . $matches[2].'@'.$matches[3] . '</a>\');';
  }
  else {
    $link = 'document.write(\'<a href="mailto:' . $matches[1] . '" class="bb-email">' . (isset($matches[2]) ? $matches[2] : $matches[1]) . '</a>\');';
  }

  $js_encode = '';
  for ($x = 0; $x < strlen($link); $x++) {
    $js_encode .= '%' . bin2hex($link{$x});
  }

  $link = '<script type="text/javascript" language="javascript">eval(unescape(\''.$js_encode.'\'))</script>';
  if (isset($matches[3])) {
    $link = $matches[1] . $link;
  }

  return $link;
}

function _uieforum_bbcode_list_tag($type = NULL) {
  if (isset($type)) {
    return '<ol class="bb-list" style="list-style-type:' . $type . ';">\\1</ol>';
  }
  else {
    return '<ul class="bb-list">\\1</ul>';
  }
}

function _uieforum_bbcode_pre_tag($text) 
{
//	return '<div class="uie_pre"><pre>'.$text.'</pre></div>';
  if ((substr($text, 0, 2) == "\r\n") && (substr($text, strlen($text) - 2, 2) == "\r\n"))
    return '<div class="uie_pre"><pre>'.$text.'</pre></div>';
  else
    return '<code class="uie_pre">'.$text.'</code>';
}

function _uieforum_bbcode_php_tag($text)
{
	//# Undo the htmlspecialchars, we need the raw source.
	if (version_compare(PHP_VERSION, "5.1.0", ">="))
	{
		$text = htmlspecialchars_decode($text, ENT_QUOTES);
	}
	else 
	{
		$text = strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
	}
	
	//# add '<?php' prefix if we don't have one so the code is recognized as such
	if (!preg_match("#^\s*<\?#si", $text))
		$text = '<?php '.$text;
	
	if (version_compare(PHP_VERSION, "4.2.0", ">="))
	{
		$text = highlight_string($text, true);
	} 
	else
	{
		//# workaround PHP <4.2.0: Grab the output of hightlight_string
		ob_start();
		@highlight_string($text);
		$text = ob_get_contents();
		ob_end_clean();
	} 
	
	//# Strip linebreak of the first line
	$text = preg_replace('#<code>\s*<span style="color: \#000000">\s*#i', "<code>", $text);
	//# Strip linebreak of the last line
	$text = preg_replace("#</span>\s*</code>#", "</code>", $text);
	$text = preg_replace("#</span>(\r\n?|\n?)</code>#", "</span></code>", $text);

	return '<div class="uie_pre">'.$text.'</div>';
}
