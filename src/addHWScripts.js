function reqListener ()
{
	console.log(this.responseText);
}

function userOnblur(elem)
{
	var no = elem.name.charAt(4);
	var elemUser = document.getElementsByName("user[" + no + "]")[0];
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

 