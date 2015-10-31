// JavaScript Document
function addnewgroup(a){
    var c=document.getElementById("uploadgrouptxt").value,b=document.getElementById("upload_group");
    if(""==c||"Enter New Group Name"==c||"Please Enter Group Name"==c||enter_group_name==c)return document.getElementById("groupError").innerHTML='<label id="login_error_msg" >'+enter_group_name+'</label>',!1;
    var d=getBrowser();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected=
                "selected";
                document.getElementById("uploadgrouptxt").value="";
                document.getElementById("upload_group").value=f.value;
                $(".customStyleSelectBoxInner").text(f.text)
                return document.getElementById("groupError").innerHTML='',!1;
                }else return document.getElementById("groupError").innerHTML='<label id="login_error_msg" style="color:red;">'+group_name_exist+'</label>',!1
                }catch(e){}
        };

var e=1<c.indexOf("&")?c.replace("&","_"):c,a="?option=com_questions&tmpl=component&task=quazax.addnewgroup&userid="+a+"&group_name="+e;
d.open("GET",a,!0);
document.getElementById("groupError").innerHTML='<label id="login_error_msg" style="color:green !important;">'+group_name_created+'</label>';
d.send(null)
}
function getBrowser(){
    try{
        req=new XMLHttpRequest
        }catch(a){
        try{
            req=new ActiveXObject("Msxml2.XMLHTTP")
            }catch(c){
            try{
                req=new ActiveXObject("Microsoft.XMLHTTP")
                }catch(b){
                req=!1
                }
            }
    }
return req
}
function onBlurEvent(a,b)
{""==a.value&&(a.value=b,a.style.color="#C9C8C8",a.style.fontWeight="normal")
}

function onFocusMenu(a,b)
{a.value==b&&(a.value="",a.style.color="#000000",a.style.fontWeight="normal")
}

function sendnotification(a){
    if("0"==a)return window.open(c+"p?option=com_users&task=login","_self"),!1;
	var e = document.getElementById("groupids").innerHTML;
	var c = document.getElementById("userids").innerHTML;
	if(""==e)alert("Select a group to continue.");
	if(""==c)alert("You must select some Users to continue.");
    document.getElementById("log").style.zIndex=9999;
    var d=getBrowser();
	document.getElementById("profilepopup").innerHTML='',!1;
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            }catch(n){
            alert(n.message)
            }
    };
	

d.open("GET","?option=com_questions&tmpl=component&task=quazax.sendnotification&userid="+a+"&users="+c+"&groups="+e,!0);
if("" !=e && "" !=c) {
document.getElementById("profilepopup").innerHTML='<label id="login_error_msg" style="color:green !important;">'+users_notification_sent+'</label>';
}
d.send(null);
return!0
}