var popupStack = new Array();
var previousPopup;
var clickActivated = 0;

$(document).ready(
  function(){
    $('div.popup-hover a.popup-title').mouseover(function(event){ mouse_hover(this);});
    $('div.popup-click a.popup-title').click(function(event){ mouse_click(this);});
  }
);



function show_popup(popup){

  var parent = $(popup);
  var body = parent.children('div.popup-positioner').children('div.popup-body');

  // Move previous popup
  if (previousPopup!=null){
    previousPopup.css({zIndex: 0});
  }

  // Move to top
  parent.css({
    zIndex: 1000 + popupStack.length + clickActivated
  });

  switch(true){
    case body.hasClass('popup-slide'): body.slideDown("medium"); return;
    case body.hasClass('popup-fade') : body.fadeIn("medium")   ; return;
    default: body.show();
  }
}

function hide_popup(popup){

  var parent = $(popup);
  var body = parent.children('div.popup-positioner').children('div.popup-body');

  // Move to back
  lastTimeout = setTimeout("$('#" + parent.attr('id') + "').css({zIndex: 0}); previousPopup = null;", 500);
  previousPopup = parent;

  switch(true){
    case body.hasClass('popup-slide'): body.slideUp("medium"); break;
    case body.hasClass('popup-fade'): body.fadeOut("medium"); break;
    default: body.hide(); 
  }
}



function mouse_click(title){

  var parent = $(title).parent().parent();
  var body = parent.children('div.popup-positioner').children('div.popup-body');

  var currentDisplay = body.css('display');

  if (currentDisplay == 'block'){
    clickActivated --;
    // Undo ie z-index hack after animation completes
    setTimeout("$('#"+parent.attr('id')+"').css('zIndex', 0)", 250);
    hide_popup(parent);
  }

  if (currentDisplay == 'none'){
    // Make sure ie does not display the other popup titles above the popup
    parent.css('zIndex', 1000 + clickActivated);
    clickActivated++;
    show_popup(parent);
  }
}

function mouse_hover(title){
  
  var parent = $(title).parent().parent();
  var id = parent.attr('id');
  var body = parent.children('div.popup-positioner').children('div.popup-body');
  
  // Ignore if already shown
  for(a=popupStack.length - 1; a >= 0; a--){
    if (popupStack[a] == id) return;
    // If not related, hide last
    if (parent.parents('#' + popupStack[a]).length == 0){
      var last = popupStack.pop();
      hide_popup($('#' + last));
    }
  }
  
  popupStack.push(id);
  // Show
  show_popup(parent);
  
  if (popupStack.length == 1) $(document).mousemove(function(event){mouse_move(event)});
}

function mouse_move(event){
  var source = $(event.target);
  var id = source.attr('id');

  // Ignore if already shown
  for(a=popupStack.length - 1; a >= 0; a--){
    if (popupStack[a] == id) return;
    // If not related, hide last
    if (source.parents('#' + popupStack[a]).length == 0){
      var last = popupStack.pop();
      hide_popup($('#' + last));
    }
  }
}


