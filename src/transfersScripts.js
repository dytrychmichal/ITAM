function isEmptyObject(obj) {
  for (var key in obj) {
    if (Object.prototype.hasOwnProperty.call(obj, key)) {
      return false;
    }
  }
  return true;
}

function reqListener ()
{
	console.log(this.responseText);
}

function userOnblur(elem)
{
	var no = elem.name.charAt(7);
	var elemUser = document.getElementsByName("userNew[" + no + "]")[0];
	findUser(elem.value, elemUser, elem);
	
	//elem.onblur = updateUser;
}
		
function findUser(sso, elemUser, elem)
{	
	for(var i=0; i<usersJson.length; i++)
	{
		if(usersJson[i].sso==sso)
		{	
			elemUser.value = usersJson[i].surname + " " + usersJson[i].name;
			return;
		}
	}
	elemUser.value = null;
	elem.value=null;
	
}

function invOnblur(elem)
{
	//find the HW with inv
	var no = elem.name.charAt(4);
	var elemHw = document.getElementsByName("hwName[" + no + "]")[0];
	var elemSerial = document.getElementsByName("serial[" + no + "]")[0];
	var elemUser = document.getElementsByName("userOld[" + no + "]")[0];
	var elemSso = document.getElementsByName("ssoOld[" + no + "]")[0];
	var elemUserNew = document.getElementsByName("userNew[" + no + "]")[0];
	var elemSsoNew = document.getElementsByName("ssoNew[" + no + "]")[0];
	var elemNote = document.getElementsByName("note[" + no + "]")[0];
	var hwJson;
	var inv = elem.value.toUpperCase();	
	//proper POST format
	if(inv != null && inv != '') 				//only if inv is not empty
	{
		inv = 'inv='+inv;
		var reqInv = new XMLHttpRequest();		//get the last ownership of the user
		
		reqInv.open("post", "src/getOwnershipInv.php", true);
		reqInv.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		
		reqInv.onload = function() {			//once we get the data - doMagic()
			hwJson = JSON.parse(this.responseText);
			if(!isEmptyObject(hwJson))
			{	
				elem.value = elem.value.toUpperCase();
				elemSerial.setAttribute("disabled", "disabled");
				elemUser.setAttribute("disabled", "disabled");
				elemSso.setAttribute("disabled", "disabled");
				elemHw.value = hwJson[0].manufacturer_name + " " + hwJson[0].model;
				elemSerial.value = hwJson[0].serial;
				if(hwJson[0].sso != null)
				{
					elemUser.value = hwJson[0].user_surname + " " + hwJson[0].user_name;
					elemSso.value = hwJson[0].sso;
				}
				elemSsoNew.removeAttribute("disabled");
			}
			else	//set line to default
			{
				elem.value = null;
				elemHw.value = null;
				elemSerial.value = null;
				elemSerial.removeAttribute("disabled");
				elemUser.value = null;
				elemSso.value = null;
				elemUserNew.value = null;
				elemSsoNew.value = null;		
				elemSsoNew.setAttribute("disabled", "disabled");
				elemNote.value = null;
			}
			
		}
		
		reqInv.send(inv);
		
		
	//update all fields
	}
	
	else //empty value - set to default
	{
		elem.value = null;
		elemHw.value = null;
		elemSerial.value = null;
		elemSerial.removeAttribute("disabled");
		elemUser.value = null;
		elemSso.value = null;
		elemUserNew.value = null;
		elemSsoNew.value = null;		
		elemSsoNew.setAttribute("disabled", "disabled");
		elemNote.value = null;
		
	}
	
}

function serialOnblur(elem)
{
	//find the HW with serial
	var no = elem.name.charAt(7);
	var elemHw = document.getElementsByName("hwName[" + no + "]")[0];
	var elemInv = document.getElementsByName("inv[" + no + "]")[0];
	var elemUser = document.getElementsByName("userOld[" + no + "]")[0];
	var elemSso = document.getElementsByName("ssoOld[" + no + "]")[0];
	var elemUserNew = document.getElementsByName("userNew[" + no + "]")[0];
	var elemSsoNew = document.getElementsByName("ssoNew[" + no + "]")[0];
	var elemNote = document.getElementsByName("note[" + no + "]")[0];
	var hwJson;
	var ser = elem.value.toUpperCase();	
	
	//proper POST format
	if(ser != null && ser != '') 				//only if inv is not empty
	{
		ser = 'ser='+ser;
		var reqSer = new XMLHttpRequest();		//get the last ownership of the user
		
		reqSer.open("post", "src/getOwnershipSerial.php", true);
		reqSer.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		
		reqSer.onload = function() {			//once we get the data - doMagic()
			hwJson = JSON.parse(this.responseText);
			if(!isEmptyObject(hwJson))
			{	
				elem.value = elem.value.toUpperCase();
				elemInv.setAttribute("disabled", "disabled");
				elemUser.setAttribute("disabled", "disabled");
				elemSso.setAttribute("disabled", "disabled");
				elemHw.value = hwJson[0].manufacturer_name + " " + hwJson[0].model;
				elemInv.value = hwJson[0].inv;
				if(hwJson[0].sso != null)
				{
					elemUser.value = hwJson[0].user_surname + " " + hwJson[0].user_name;
					elemSso.value = hwJson[0].sso;	
				}
				elemSsoNew.removeAttribute("disabled");
			}
			else	//set line to default
			{
				elemHw.value = null;
				elemInv.value = null;
				elemInv.removeAttribute("disabled");
				elemUser.value = null;
				elemSso.value = null;	
				elemSsoNew.value=null;	
				elemSsoNew.setAttribute("disabled", "disabled");
				elemUserNew.value=null;
				elem.value = null;
				elemNote.value=null;
			}
			
		}
		
		reqSer.send(ser);
		
		
	//update all fields
	}
	
	else //empty value - set to default
	{
		elemHw.value = null;
		elemInv.value = null;
		elemInv.removeAttribute("disabled");
		elemUser.value = null;
		elemSso.value = null;
		elemSsoNew.value=null;
		elemSsoNew.setAttribute("disabled", "disabled");
		elemUserNew.value=null;
		elem.value = null;
		elemNote.value=null;
		
	}
	
}

function unAble()		//enable all input fields
{
	var table = document.getElementById('transfersTable');
	var elem = document.forms["hwTableForm"].getElementsByTagName("input");
	var elemNr = elem.length;
	
	for(var i = 0; i<elemNr; i++)
	{
		elem[i].removeAttribute("disabled");
	}	
	
}
		
var usersJson;
var oReq = new XMLHttpRequest(); //New request object

oReq.open("post", "src/getUsersActive.php", true);
oReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
oReq.onload = function()
{
		//This is where you handle what to do with the response.
		//The actual data is found on this.responseText
	usersJson = JSON.parse(this.responseText);
};

oReq.send();