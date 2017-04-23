//search function source: http://stackoverflow.com/questions/1181575/determine-whether-an-array-contains-a-value
var contains = function(needle)
{
    // Per spec, the way to identify NaN is that it is not equal to itself
    var findNaN = needle !== needle;
    var indexOf;

    if(!findNaN && typeof Array.prototype.indexOf === 'function') {
        indexOf = Array.prototype.indexOf;
    } else {
        indexOf = function(needle) {
            var i = -1, index = -1;

            for(i = 0; i < this.length; i++) {
                var item = this[i];

                if((findNaN && item !== item) || item === needle) {
                    index = i;
                    break;
                }
            }

            return index;
        };
    }

    return indexOf.call(this, needle) > -1;
}

function scrapAdd(no)
{	
	var inv = document.getElementById("invTd[" + no + "]").innerHTML;
	
	if(!contains.call(del, inv))
	{
		if(!contains.call(edit, inv))	//if not already marked to editing
		{
			del.push(inv);
			document.getElementById("invTr[" + no + "]").style.backgroundColor='#FF0000';
		}
		else
		{
			alert("This item is already marked for editing!\nCannot perform two tasks at once");
		}
	}
	else
	{
		var ind = del.indexOf(inv);
				
		if (ind > -1)	//double check, not really necessary
		{
			del.splice(ind, 1);
			document.getElementById("invTr[" + no + "]").style.backgroundColor=null;
		}
	}
}


function editAdd(no)
{
	//get inventory number
	
	var inv = document.getElementById("invTd[" + no + "]").innerHTML;
	
	if(!contains.call(edit, inv))	//if not already marked to editing
	{
		if(!contains.call(del, inv))	//if not already marked to deleting
		{
			edit.push(inv);
			document.getElementById("invTr[" + no + "]").style.backgroundColor='#0066FF';
		}
		else
		{
			alert("This item is already marked for deleting!\nCannot perform two tasks at once");
		}
	}
	else
	{
		var ind = edit.indexOf(inv);
				
		if (ind > -1)	//double check, not really necessary
		{
			edit.splice(ind, 1);
			document.getElementById("invTr[" + no + "]").style.backgroundColor=null;
		}
	}	
}

function scrapAll()
{
	var delJson = JSON.stringify(del);
	
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "scrapHW.php");
	form.setAttribute("target", "_blank");

	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "message");
	hiddenField.setAttribute("value", delJson);
	form.appendChild(hiddenField);
	document.body.appendChild(form);

	form.submit();

}

function editAll()
{
	var editJson = JSON.stringify(edit);
	
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "editHW.php");
	form.setAttribute("target", "_blank");

	var hiddenField = document.createElement("input"); 
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "message");
	hiddenField.setAttribute("value", editJson);
	form.appendChild(hiddenField);
	document.body.appendChild(form);

	form.submit();
	
}

var edit = [];
var del = [];
 