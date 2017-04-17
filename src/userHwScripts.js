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

function updateUser()
{
	var user=userField.value;
	findUser(user);
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

var userField = document.getElementById("usr");
var userText = document.getElementById("txt");
var usersJson;
var oReq = new XMLHttpRequest(); //New request object

oReq.onload = function()
{
		//This is where you handle what to do with the response.
		//The actual data is found on this.responseText
	usersJson = JSON.parse(this.responseText);
};


oReq.open("get", "src/getUsersActive.php", true);
oReq.send();

 