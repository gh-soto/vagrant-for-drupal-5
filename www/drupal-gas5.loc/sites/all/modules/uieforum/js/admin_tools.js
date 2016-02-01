
function forum_admin_edit(postToEdit, obj)
{
  selected = obj.options[obj.selectedIndex];
  var userSelected = obj.selectedIndex;
  obj.selectedIndex = 0;
  if(userSelected != 0)
  {
//    alert(selected.text + " - " + selected.value);
    location=selected.value;
  }
}

