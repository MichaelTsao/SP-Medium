function VN_cColor(elm,n)
{
	switch(n){
		case 1:
			elm.style.backgroundColor = '#E9E9D1';
			elm.style.cursor = 'hand';
			break;
		case 2:
			elm.style.backgroundColor = '#FFDFBB';
			elm.style.cursor = 'hand';
			break;
		case 3:
			elm.style.backgroundColor = '#FFDFDF';
			elm.style.cursor = 'hand';
			break;
		case 4:
			elm.style.backgroundColor = '#AADDFF';
			break;
	}
}

function VN_rColor(elm)
{
	elm.style.backgroundColor = '';
}

function jumpMenu(targ,selObj)
{ 
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
}

function enableit(isphone)
{
	switch(isphone)
	{
	case 1:
		//qt.phone.disabled = false;
		qt.usertype.disabled = true;
		qt.r3.disabled = true;
		if(qt.r3.checked == true)
			qt.r1.checked = true;
		break;
	case 0:
		//qt.phone.disabled = true;
		qt.usertype.disabled = false;
		qt.r3.disabled = false;
		break;
	case 2:
		qt.pak.disabled = false;
		//if(qt.rgateway.checked == true)
			qt.usertype.disabled = false;
		break;
	case 3:
		qt.pak.disabled = true;
		//if(qt.rgateway.checked == true)
			qt.usertype.disabled = false;
		break;
	case 4:
		qt.pak.disabled = false;
		qt.usertype.disabled = true;
		break;
	}
}

function changeDay(daytype, doit)
{
	if(doit == 1)
	{
		if(daytype == 0)
		{
			day = eval(document.qt.sday);
			month = eval(document.qt.smonth);
			year = eval(document.qt.syear);
		}
		else
		{
			day = eval(document.qt.seday);
			month = eval(document.qt.semonth);
			year = eval(document.qt.seyear);
		}
		
		for(a = 0; a < 28; a++)
		{
			day.options[a].text = a + 1;
			day.options[a].value = a + 1;
		}
		switch(month.options[month.selectedIndex].value)
		{
			case "2":
				if(year.options[year.selectedIndex].value % 4 == 0)
				{
					day.length=29;
					day.options[28].text = "29";
					day.options[28].value = "29";
					if(daytype == 1)
						day.options[28].selected = true;
				}
				else
				{
					day.length=28;
					if(daytype == 1)
						day.options[27].selected = true;
				}
				break;
			case "1":
			case "3":
			case "5":
			case "7":
			case "8":
			case "10":
			case "12":
				day.length=31;
				for(a = 28; a < 31; a++)
				{
					day.options[a].text = a + 1;
					day.options[a].value = a + 1;
				}
				if(daytype == 1)
					day.options[30].selected = true;
				break;
			case "4":
			case "6":
			case "9":
			case "11":
				day.length=30;
				for(a = 28; a < 30; a++)
				{
					day.options[a].text = a + 1;
					day.options[a].value = a + 1;
				}
				if(daytype == 1)
					day.options[29].selected = true;
				break;
		}
	}
}

function CheckAll(chked)
{
	obj_data = document.getElementsByName("check_data[]");

	for(i=0; i<obj_data.length; i++)
	{
		obj_data[i].checked = chked;
	}
}

var min, sec;
function runTime(t)
{
	if(min > 0 || sec > 0)
	{
		if(sec == 0)
		{
			sec = 59;
			min--;
		}
		else
			sec--;
	}
	else
	{
		if(t == 1)
		{
			document.all("gobtn").disabled = false;
		}
	}
		
	document.all("min").innerHTML = min;
	document.all("sec").innerHTML = sec;

	setTimeout("runTime(" + t + ")", 1000);
}

function init(m,s,b)
{
	min = m;
	sec = s;
	runTime(b);
}



function Redirect(x)
{
	var groups = document.getElementById("big_area").options.length;
	var group = new Array(groups);
	for (i = 0; i < groups; i ++)
	group[i] = new Array();
	
	group[0][0] = new Option("","0");
	
	group[1][0] = new Option("总部","1");
	
	group[2][0] = new Option("北京/山西/内蒙","2");
	group[2][1] = new Option("山东/河南/天津","3");
	group[2][2] = new Option("东北","4");
	group[2][3] = new Option("西北","5");
	
	group[3][0] = new Option("上海","6");
	group[3][1] = new Option("南京","7");
	group[3][2] = new Option("杭州","8");
	group[3][3] = new Option("华中","9");
	
	group[4][0] = new Option("广州","10");
	group[4][1] = new Option("深圳","11");
	group[4][2] = new Option("西南","12");
	
	group[5][0] = new Option("ACQ","13");
	group[5][1] = new Option("集采","14");
	group[5][2] = new Option("WEB","15");

	var temp = document.getElementById("small_area");
	
	for (m = temp.options.length-1; m > 0; m --)
		temp.options[m] = null;
	for (i = 0; i < group[x].length; i ++)
	{
		temp.options[i] = new Option(group[x][i].text,group[x][i].value);
	}
	temp.options[0].selected = true
}

function Show_Menu(num)
{
	for(var i=1;i<4;i++){document.getElementById("tab"+i).style.display="none";}
	for(var i=1;i<4;i++){document.getElementById("menu"+i).className="menuhide";}
	document.getElementById("menu"+num).className="menushow";
	document.getElementById("tab"+num).style.display="block";
}

