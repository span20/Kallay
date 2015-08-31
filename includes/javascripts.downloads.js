var checkflag = "false";
function doNow()
{
  d=document;
  el=d.getElementsByTagName('INPUT');
  if (checkflag == "false") {
    for(i = 0; i < el.length; i++)
    {
      if (el[i].disabled == 0) el[i].checked = 1;
    }
    checkflag = "true";
  }
  else {
    for(i = 0; i < el.length; i++)
    {
      el[i].checked = 0;
    }
    checkflag = "false";
  }
}
