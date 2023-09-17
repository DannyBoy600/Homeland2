var changed = false;

var isNav = (navigator.appName.indexOf("Netscape") > -1);
var isIE = (navigator.appName.indexOf("Microsoft") > -1);

/**********************************************************************
   loadFrame
   
   theframe = current frame
   src		= php file
   parname/parval = a number of parameter pairs 
***********************************************************************/
function loadFrame(theframe,src)
{
  var doc = theframe.document;
  var page = "<html><body><form action='" + src + "' method='post'>";

  var params = (arguments.length-2)/2;

  for (var i=0; i < params; i++)
  {
    page += "<input type=hidden name='" + arguments[i*2+2] + "'>";
  }
  page += "</form></body></html>";
  doc.open();
  doc.writeln(page);
  doc.close();
  
  // now assign values, safer this way
  for (var i=0; i < params; i++)
  {
    doc.forms[0].elements[i].value = arguments[i*2+3];
  } 

  doc.forms[0].submit();
  
  return "<html></html>";
}

function blank()
{
  return "<HTML></HTML>";
}

function urlencode(s)
{
  // Double the apostrophe, otherwise makes php insertion fail
  if (s.indexOf("'") > -1)
  {
    var s2 = s;
    s = "";
    var len = s2.length; 
    for (var i=0;i<len;i++)
      if (s2.charAt(i) == "'")
        s = s + "''";
      else 
        s = s + s2.charAt(i);
  }
  
  s = escape(s);
   
  // fix the plus, is otherwise changed to a space
  if (s.indexOf("+") > -1)
  {
    var s2 = s;
    s = "";
    var len = s2.length; 
    for (var i=0;i<len;i++)
      if (s2.charAt(i) == "+")
        s = s + "%2B";
      else 
        s = s + s2.charAt(i);
  }

  return(s);
}

// used when the input is a text string
function validateDigitString(str) 
{
  if (str.length == 0)
    return false;
  var valid = "0123456789"
  var temp;
  for (var i=0; i<str.length; i++) 
  {
    temp = "" + str.substring(i, i+1);
    if (valid.indexOf(temp) == "-1") 
      return false
  }
  return true;
}

/*
Purpose: return true if the date is valid, false otherwise
*/
function isValidDate(year,month,day)
{
  var dteDate;
  
  //set up a Date object based on the day, month and year arguments
  //javascript months start at 0 (0-11 instead of 1-12)
  month = parseInt(month,10) - 1;
  dteDate=new Date(year,month,day);
  
  /*
  Javascript Dates are a little too forgiving and will change the date to a reasonable guess if it's invalid. 
  We'll use this to our advantage by creating the date object and then comparing it to the details we put it. 
  If the Date object is different, then it must have been an invalid date to start with...
  */
  
  return ((parseInt(day,10)==dteDate.getDate()) && (parseInt(month,10)==dteDate.getMonth()) && (parseInt(year,10)==dteDate.getFullYear()));
}

function isValidDateStr(strDate)
{
	/* Valid date formats:
	   YYYYMMDD    8 
	   YYMMDD      6
	   YYYY-MM-DD 10
	   YY-MM-DD    8  */
	strDate = trim(strDate);
  if (strDate.indexOf("-") == -1)
  {
  	if (strDate.length == 8)
  	  return isValidDate(strDate.substr(0,4),strDate.substr(4,2),strDate.substr(6,2));
  	else if (strDate.length == 6)
  		return isValidDate("20" + strDate.substr(0,2),strDate.substr(2,2),strDate.substr(4,2));
  }
  else
  {
  	if (strDate.length == 10)
  	  return isValidDate(strDate.substr(0,4),strDate.substr(5,2),strDate.substr(8,2));
  	else if (strDate.length == 8)
  		return isValidDate("20" + strDate.substr(0,2),strDate.substr(3,2),strDate.substr(6,2));
  }
    	  
  return false;
}

function isValidInteger(str) 
{
  if (str.length == 0) return false;
  var valid = "0123456789"
  var temp;
  for (var i=0; i<str.length; i++) 
  {
    temp = "" + str.substring(i, i+1);
    if (valid.indexOf(temp) == "-1") return false
  }
  return true;
}

function trim(inputString) {
   // Removes leading and trailing spaces from the passed string. Also removes
   // consecutive spaces and replaces it with one space. If something besides
   // a string is passed in (null, custom object, etc.) then return the input.
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " ") { // Check for spaces at the beginning of the string
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " ") { // Check for spaces at the end of the string
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) { // Note that there are two spaces in the string - look for multiple spaces within the string
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); // Again, there are two spaces in each of the strings
   }
   return retValue; // Return the trimmed string back to the user
} 


function emptyString(txt)
{
  if (txt == null) return true;
  if (trim(txt) == "") return true;
  return false;  
}

function validName(s)
{
  if (s == null) return false;
  if (trim(s) == "") return false;
  if (s.indexOf(',') > -1) return false;
  if (s.indexOf(';') > -1) return false;
  return true;  
}

function validUserNamePassword(s)
{
  if (s == null)
    return false;
  s = trim(s);
  if (s == "")
    return false;
  s = s.toUpperCase();
  var valid = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
  var tmp;
  for (var i=0; i<s.length; i++) 
  {
    tmp = "" + s.charAt(i);
    if (valid.indexOf(tmp) == -1) 
      return false;
  }
  return true;
}

// translates 'numeric entities' &#034 and &#039 to double and single quotes
function removeNumericQuotes(s)
{
  var s2 = s.replace(/&#034/g,"\"");
  return s2.replace(/&#039/g,"'");
}

function returnWasHit(ev)
{
  if (isIE)
    butCode = ev.keyCode;
  else
    butCode = ev.which;
  if (butCode == 13)
    return true;
  return false;
}

// general highlight routines for buttons
function onMouseOverBut(butNo)
{
  var td = document.getElementById("td_b" + butNo);
  if (td.className.indexOf("Dis") > -1 || td.className.indexOf("Sel") > -1 || td.className.indexOf("Over") > -1)
    return;
  td.className += "Over";
}

function onMouseOutBut(butNo)
{
  var td = document.getElementById("td_b" + butNo);
  if (td.className.indexOf("Over") > -1)
    td.className = td.className.substr(0,td.className.length-4);
}

// general highlight routines for id buttons
function onMouseOverIdBut(butId)
{
  var td = document.getElementById(butId);
  if (td.className.indexOf("Dis") > -1 || td.className.indexOf("Sel") > -1 || td.className.indexOf("Over") > -1) return;
  td.className += "Over";
}

function onMouseOutIdBut(butId)
{
  var td = document.getElementById(butId);
  if (td.className.indexOf("Over") > -1)
    td.className = td.className.substr(0,td.className.length-4);
}

function highlightBut(butNo)
{
  for (var i=1;i<10;i++)
  {
    var td = document.getElementById("td_b" + i);
    if (td != null)
    {
      if (td.className.indexOf("Sel") > -1)
        td.className = td.className.substr(0,td.className.length-3);
    }
  }
  var td = document.getElementById("td_b" + butNo);
  // remove suffix "Over"
  if (td.className.indexOf("Over") > -1)
    td.className = td.className.substr(0,td.className.length-4);
  // add suffix "Sel"
  td.className += "Sel";
}

function setButtonText(doc,butNo,txt)
{
  var btn = doc.getElementById("td_b" + butNo);
  btn.childNodes[0].nodeValue = txt;
}

function getButtonText(doc,butNo)
{
  var btn = doc.getElementById("td_b" + butNo);
  return btn.childNodes[0].nodeValue;
}

function setElementText(doc,el,txt)
{
  var new_txt = doc.createTextNode(txt);
  if (el.hasChildNodes())  
    el.replaceChild(new_txt, el.childNodes[0]);
  else 
    el.appendChild(new_txt);  
}

function getElementText(el)
{
  if (el.hasChildNodes())
    return el.childNodes[0].nodeValue;

  return "";
}

function toInt ( x )
{
	return ( x > 0 ? Math.floor( x ) : Math.ceil ( x ) )
}

// Returns an integer random number, falling in the specified (inclusive) range
function intRnd ( low, high )
{
	return toInt ( Math.random() * (high - low + 1) ) + low;
}
