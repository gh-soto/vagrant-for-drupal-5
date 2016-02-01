var detect = navigator.userAgent.toLowerCase();
var is_ie = false;

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}
if(checkIt('msie'))
  is_ie = true;





/**
  AddText physically adds text to a textarea, selects that text, and sets the focus of the page to that object

  Param: obj - The Textarea (or object) to add the text to
  Param: text - The text to add
  Param: selStart - The selection start index
  Param: selEnd - The selection end index
**/
function AddText(obj, text, selStart, selEnd)
{
  obj.value  = text;
//  obj.selectionStart = selStart;
  obj.selectionStart = selEnd;
  obj.selectionEnd = selEnd;

  setfocus(obj);
}

/**
  Process interprets the command and generates the bbcode to insert. This will eventually be database driven.
**/
function Process(str)
{
  obj = document.getElementById( 'edit-PostText' );

  originaltxt = obj.value;

  selStart = obj.selectionStart;
  selEnd = obj.selectionEnd;

  if(is_ie)
  {
    if( document.selection )
    {
      txtSelected = document.selection.createRange();

      var stored_range = txtSelected.duplicate();
      stored_range.moveToElementText( obj );
      stored_range.setEndPoint( 'EndToEnd', txtSelected );

      selStart = stored_range.text.length - txtSelected.text.length;
      obj.selectionStart = selStart;
      selEnd = obj.selectionStart + txtSelected.text.length;
      obj.selectionEnd = selEnd;
    }
  }

  txtStart = originaltxt.substring(0, selStart);
  txtEnd = originaltxt.substring(selEnd);
  txtSelected = originaltxt.substring(selStart, selEnd);

  txt = null;
  finaltxt = null;

  if(str == 'bold')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text that should be bolded.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "[b]" + txt + "[/b]";
  }
  else if(str == 'italic')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text that should be italicized.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "[i]" + txt + "[/i]";
  }
  else if(str == 'underline')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text that should be underlined.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "[u]" + txt + "[/u]";
  }
  else if(str == 'image')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the URL to the image you wish to insert.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "\r[img]"+txt+"[/img]\r";
  }
  else if(str == 'quote')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text you want quoted.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "\r[quote]\r" + txt + "\r[/quote]";
  }
  else if(str == 'rant')
  {
    if(txtSelected.length == 0)
      txt = prompt("ENTER YOUR DAMN RANT NOW!", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "\r[rant]\r" + txt + "\r[/rant]";
  }
  else if(str == 'code')
  {
    if(txtSelected.length == 0)
      txt = ' ';
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "\r[code]\r" + txt + "\r[/code]";
  }
  else if(str == 'url')
  {
    txt2 = prompt("What name should be shown?\nIf this Field is blank, the URL will be visible",txtSelected);
    if(txt2 != null)
    {
      if(txtSelected.length == 0)
        txt = prompt("Please enter the URL for the hyperlink.","http://");
      else
        txt = txtSelected;

      if (txt != null && txt.length > 0)
      {
        if(txt.indexOf("http://") != 0)
          txt = "http://" + txt;
        if (txt2=="")
          txt = "[url=" + txt + "]" + txt + "[/url]";
        else
          txt = "[url=" + txt + "]" + txt2 + "[/url]";
      }
    }
  }
  else if(str == 'left')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text that will be left aligned.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "[left]" + txt + "[/left]";
  }
  else if(str == 'center')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text that will be center aligned.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "[center]" + txt + "[/center]";
  }
  else if(str == 'right')
  {
    if(txtSelected.length == 0)
      txt = prompt("Please enter the text that will be right aligned.", txtSelected);
    else
      txt = txtSelected;

    if (txt != null && txt.length > 0)
      txt = "[right]" + txt + "[/right]";
  }
  else if(txt == null || txt.length == 0)
    txt = str;

  if(txt != null && txt.length > 0)
  {
    selEnd = selStart + txt.length;
    finaltxt = txtStart + txt + txtEnd;
    AddText(obj, finaltxt, selStart, selEnd);
  }

}

/** Not used at the moment **/
function storeCaret(textEl)
{ if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate(); }


/** Sets focux **/
function setfocus(obj)
{ obj.focus(); }

