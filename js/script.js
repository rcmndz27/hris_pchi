/* Script to disable right-click */
var message=" This function is not allowed here.";
function clickIE4(){
      if (event.button==2)
      {
            swal(message);
            return false;
      }
}

function clickNS4(e){
      if (document.layers||document.getElementById&&!document.all)
      {
            if (e.which==2||e.which==3)
            {
                  
                  return false;
            }
      }
}

if (document.layers)
{
      document.captureEvents(Event.MOUSEDOWN);
      document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById)
{
      document.onmousedown=clickIE4;
}


document.oncontextmenu = new Function('swal({text:"This function is not allowed here!",icon:"warning"});return false;')