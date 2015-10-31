<?php
/*
    
    Copyright (C)  2012 Sweta ray.
    Permission is granted to copy, distribute and/or modify this document
    under the terms of the GNU Free Documentation License, Version 1.3
    or any later version published by the Free Software Foundation;
    with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
    A copy of the license is included in the section entitled "GNU
    Free Documentation License"
	@license GNU/GPL http://www.gnu.org/copyleft/gpl.html
    Questions for Joomla
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
   
	Questions for Joomla
	Version 0.0.1
	Created date: Sept 2012
	Creator: Sweta Ray
	Email: admin@phpseo.net
	support: support@phpseo.net
	Website: http://www.phpseo.net
*/

defined('_JEXEC') or die('Restricted access'); 
$app = JFactory::getApplication();
$params = $app->getParams();
JHTML::_('behavior.modal');
$articleid = $this->escape($params->get('helparticleid', 1));
$favlimit = $this->escape($params->get('favlimit', 2));
$qtagsCount = $this->escape($params->get('tagsCount', 5));
$profile_main = $this->escape($params->get('profile_main', '100%'));
$uid = JRequest::getInt( 'id' );
$user=array();
$user=$this->GetUserDetails($uid);
/*Extra user profile Edit*/
jimport('joomla.user.helper');
$prouser = JFactory::getUser();
$profile = JUserHelper::getProfile($prouser->id);
$this->escape($this->profilehits($uid));
$groupdetails = $this->groupdetails;

$doc = JFactory::getDocument();

$doc->addStyleSheet("components/com_questions/css/profiles.css");
$doc->addStyleSheet("components/com_questions/css/simple-profile.css");
$doc->addStyleSheet("components/com_questions/css/jquery-ui-1.10.0.custom.min.css");
require_once 'components/com_questions/media/style.php';
$document = JFactory::getDocument();
$document->addScript('components/com_questions/media/js/ui/jquery-1.11.0.js');
$document->addScript('components/com_questions/media/js/ui/jquery-ui-1.10.2.js');

$document->addScript('components/com_questions/media/js/profiles.js');

?>	  

<script type="text/javascript">

<?php $user3 = JFactory::getUser(); if($user[0]->id == $user3->id){ 


?>
$(document).ready(function() {
$('#load').hide();
});

$(function() {
$(".delete").click(function() {
$('#load').fadeIn();
var commentContainer = $(this).parent();
var id = $(this).attr("id");
var string = 'id='+ id ;
	
$.ajax({
   type: "POST",
   url: "?option=com_questions&tmpl=component&task=quazax.deletegroup&id="+id,
   data: string,
   cache: false,
   success: function(){
	commentContainer.slideUp('slow', function() {$(this).remove();});
	$('#load').fadeOut();
	location.reload(); 
  }
   
 });

return false;
	});
});
/*Group Delete Ends*/
var enter_group_name = "<?php echo JTEXT::_('COM_QUESTIONS_ENTER_NEW_GROUP_NAME'); ?>";
var group_name_exist = "<?php echo JTEXT::_('COM_QUESTIONS_ENTER_NEW_GROUP_NAME_EXIST'); ?>";
var group_name_created =  "<?php echo JTEXT::_('COM_QUESTIONS_ENTER_NEW_GROUP_NAME_CREATED'); ?>";
var users_notification_sent =  "<?php echo JTEXT::_('COM_QUESTIONS_USERS_NOTIFICATIONS_SENT'); ?>";
window.onload = function(){
        var user_bars = document.getElementById('userids').innerHTML;
};

function rtrim(str){
    return str.replace(/\s+$/, '');
}

$(document).on('click', ".removebtnuser", function() {
var liId = $(this).attr("uid");
$("span[id=user"+liId+"]").remove();
$("input[uid="+liId+"]").remove();
var foo = [];
document.getElementById("userids").innerHTML="";
$("#log").find("span").each(function(){ foo.push(this.id); });
document.getElementById("userids").innerHTML=foo;
console.log(foo);
alert("user"+liId);
});

$(document).on('click', ".removebtngrp", function() {
var liId = $(this).attr("gid");
$("span[id=group"+liId+"]").remove();
$("input[gid="+liId+"]").remove();
var foos = [];
document.getElementById("groupids").innerHTML="";
$("#logs").find("span").each(function(){ foos.push(this.id); });
console.log(foos);
document.getElementById("groupids").innerHTML=foos;
console.log(foos);
alert("group"+liId);
});

$(function() {
function loge( message,id ) {
	var foo = []; 
	$( "<span id=user"+id+">" ).text( message ).prependTo( "#log" );
	$('#log').prepend('<input type="button" value="X" class="removebtnuser" uid='+id+' />');
	$("#log").prepend("<br />");
	$("#log").scrollTop( 0 );
	$("#log").find("span").each(function(){ foo.push(this.id); });
	document.getElementById("userids").innerHTML=foo;
}
		
			
$( "#project" ).autocomplete({
minLength: 2,
source:"?option=com_questions&tmpl=component&task=quazax.getusers",
focus: function( event, ui ) {
$( "#project" ).val( ui.item.label );
return false;
},
select: function( event, ui ) {
				loge( ui.item ?
				 ui.item.name + " with username " + ui.item.username + "\n":
				"Nothing selected, input was " + this.value,ui.item.id + "\n");
$( "#project" ).val( ui.item.label );
$( "#project-id" ).val( ui.item.value );
$( "#project-description" ).html( ui.item.desc );
$( "#project-icon" ).attr( "src", "images/" + ui.item.icon );


return false;
}
})

.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
return $( "<li>" )
.append( "<a>" + item.name + "<br>" + item.username + "</a>")
.appendTo( ul );
};
});


$(function() {
	
function logs( message,id ) {
	var foos = []; 
	$( "<span id=group"+id+">" ).text( message ).prependTo( "#logs" );
	$('#logs').prepend('<input type="button" value="X" class="removebtngrp" gid='+id+' />');
	$("#logs").prepend("<br />");
	$("#logs").scrollTop( 0 );
	$("#logs").find("span").each(function(){ foos.push(this.id); });

	console.log(foos);
	document.getElementById("groupids").innerHTML=foos;
}
	
$( "#groupid" ).autocomplete({
minLength: 2,
source:"?option=com_questions&tmpl=component&task=quazax.getgroups",
focus: function( event, ui ) {
$( "#groupid" ).val( ui.item.label );
return false;
},
select: function( event, ui ) {
logs( ui.item ?
				ui.item.group_name + "\n":
				"Nothing selected, input was " + this.value,ui.item.id + "\n");
			
$( "#groupid" ).val( ui.item.label );

return false;
}
})

.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
return $( "<li>" )
.append( "<a>" + item.group_name + "</a>")
.appendTo( ul );
};
});
//http://ajaxdump.com/?0DPIbJoN
//http://stackoverflow.com/questions/2203958/jquery-recursive-iteration-over-objects
//accordion style
$(document).ready(function($) {
    $('#accordion').find('.accordion-toggle').click(function(){

      //Expand or collapse this panel
      $(this).next().slideToggle('fast');

      //Hide the other panels
      $(".accordion-content").not($(this).next()).slideUp('fast');

    });
  });
<?php } ?>
$(document).ready(function() {
    //hiding tab content except first one
    $(".tabContent").not(":first").hide();
    // adding Active class to first selected tab and show
    $("ul.tabs li:first").addClass("active").show(); 
 
    // Click event on tab
    $("ul.tabs li").click(function() {
        // Removing class of Active tab
        $("ul.tabs li.active").removeClass("active");
        // Adding Active class to Clicked tab
        $(this).addClass("active");
        // hiding all the tab contents
        $(".tabContent").hide();       
        // showing the clicked tab's content using fading effect
        $($('a',this).attr("href")).fadeIn('slow');
 
        return false;
    });
 
});


</script>
<div class="questionbox">
<div class="questions_filters">
<ul><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions"); ?>">Home</a></li><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=form&layout=edit"); ?>"><?php echo JText::_("COM_QUESTIONS_ASK_A_QUESTION"); ?></a></li>
	
<?php if ($params->get('display_help', 0)) { ?>
<span style="float:right"><h2><a class="modal" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid) ?>"rel="{handler: 'iframe', size: {x: 640, y: 480}}"><img src="components/com_questions/media/help.png" alt="Help"></a></h2></span>
<?php } ?>
  </ul>  </div></div>
   <style type="text/css">
        h1 { font-size: 200%; }
        .user-stats-table { margin-left:0px; margin-bottom:0px; }
        #user-about-me pre, blockquote { width: 95%; }
        .no-overflow { width: 230px; color: #999; white-space: nowrap; overflow: hidden; }
        .summary-header { vertical-align:middle; padding-left:10px; }
		.user-email {}
        .summary-header h2 { font-size:200%; } 
        .summary-title { clear:both; margin:20px; width:95%; float:middle;}        
        .vcard { width:<?php echo $profile_main; ?>; margin-top:-10px; }
        .badge-col { width: 200px; }
        .user-stats-table .question-summary { width: <?php echo $profile_main; ?>; }
        #user-avatar { padding:20px 20px 8px 20px; }
		#mainbar-full { width: <?php echo $profile_main; ?>	}
		#login_error_msg{text-align:center;margin: 30px auto 0;line-height: 20px;text-shadow: 0 1px rgba(255, 255, 255, 1); font-size:16px; color:#ff0000;font-weight: bold;}
		#login_error_msg .close{display: none;}
		#uploadgrouptxt {position: relative; z-index: 3; display: block; width: 30%; background: transparent; font-size: 14px; border: 1px solid #a4a2a2; border-radius: 6px; -moz-border-radius: 6px; -webkit-border-radius: 6px; box-shadow: inset 0 1px rgba(34,25,25,0.15), 0 1px rgba(255,255,255,0.8); -moz-box-shadow: inset 0 1px rgba(34,25,25,0.15), 0 1px rgba(255,255,255,0.8); -webkit-box-shadow: inset 0 1px rgba(34,25,25,0.15), 0 1px rgba(255,255,255,0.8); -webkit-transition: all 0.08s ease-in-out; -moz-transition: all 0.08s ease-in-out;}
		.creat_bttn{ display:inline-block; zoom:1; text-align:left;z-index: 1;padding: 4px 6px 4px 0px;border: 1px solid rgba(140, 126, 126, 0.5);border-radius: 6px;color: #524D4D;font-weight: bold;font-family: "helvetica neue",arial,sans-serif;-moz-border-radius: 6px;-webkit-border-radius: 6px;box-shadow: inset 0 1px rgba(255,255,255,0.35);-moz-box-shadow: inset 0 1px rgba(255,255,255,0.35);-webkit-box-shadow: inset 0 1px rgba(255,255,255,0.35);border-color: #C3C3C3; background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FDFCFC), to(#F2F0F0), color-stop(.5,#FBF9F9),color-stop(.5,#F7F5F6)); background: -moz-linear-gradient(center top, #FDFCFC, #FBF9F9 50%, #F7F5F6 50%, #F2F0F0);background: -o-linear-gradient(top left, #FDFCFC, #FBF9F9 50%, #F7F5F6 50%, #F2F0F0);background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FDFCFC), to(#F2F0F0), color-stop(.5,#FBF9F9),color-stop(.5,#F7F5F6));filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdfcfc', endColorstr='#f2f0f0');width: 100px;text-align:center;}
		  	#project-label {
			display: block;
			font-weight: bold;
			margin-bottom: 1em;
			}
			#project-icon {
			float: left;
			height: 32px;
			width: 32px;
			}
			#project-description {
			margin: 0;
			padding: 0;
			}
  			.accordion-content {display: none;}
	  		.accordion-content.default {display: block;}
			.accordion-toggle {display: block;padding: 8px 15px;cursor: pointer;background-color:rgb(170, 170, 170);-moz-border-radius: 15px; border-radius: 5px;}
			/*Table CSS*/
			.groupstable { 
			 color: #333;
			 font-family: Helvetica, Arial, sans-serif;
			 width: 640px; 
			 border-collapse: 
			 collapse; border-spacing: 0; 
			}
			 
			.groupstable td, th { 
			 border: 1px solid transparent; /* No more visible border */
			 height: 30px; 
			 transition: all 0.3s;  /* Simple transition for hover effect */
			}
			 
			.groupstable th {
			 background: #DFDFDF;  /* Darken header a bit */
			 font-weight: bold;
			}
			 
			.groupstable td {
			 background: #FAFAFA;
			 text-align: center;
			}
			
			/* Cells in even rows (2,4,6...) are one color */ 
			.groupstable tr:nth-child(even) td { background: #F1F1F1; }   
			
			/* Cells in odd rows (1,3,5...) are another (excludes header cells)  */ 
			.groupstable tr:nth-child(odd) td { background: #FEFEFE; }  
			 
			.groupstable tr td:hover { background: #666; color: #FFF; } /* Hover cell effect! */
			<?php $user3 = JFactory::getUser(); if($user[0]->id == $user3->id){ ?>
			#load {
				position:absolute;
				left:225px;
				background-image:url(<?php echo $this->baseurl ?>/components/com_questions/media/images/loading-bg.png);
				background-position:center;
				background-repeat:no-repeat;
				width:159px;
				color:#999;
				font-size:18px;
				font-family:Arial, Helvetica, sans-serif;
				height:40px;
				font-weight:300;
				padding-top:14px;
				top: 23px;
			}
			<?php } ?>
    </style>
	
   <div class="container_profile">
                    
    <div id="mainbar-full">
        <div class="subheader">
            <h1><?php 
			/*$arr = explode(' ',trim($user[0]->name));
			echo ucfirst($arr[0]); */
			echo $this->escape($user[0]->name);
			?>
			</h1>
		<?php 
		$reguser = JFactory::getUser();
		if($reguser->id != $user[0]->id){
		$favarray = array();
		$favarray = unserialize($this->getFavourite2($reguser->id));
		if(is_array($favarray)){
		if(!in_array($user[0]->id,$favarray))
		{
		?>
		<?php /*
		<a href="<?php 
		echo "index.php?option=com_questions&task=profiles.addFavourite&addfav=".$user[0]->id."&userid=".$reguser->id; ?>">
			<img src="components/com_questions/media/add.png" />
		</a>
		*/ ?>
		<?php } else { ?>
		<a href="<?php 
		echo "index.php?option=com_questions&task=profiles.delFavourite&delfav=".$user[0]->id."&userid=".$reguser->id; ?>">
			<img src="components/com_questions/media/rem.png" />
		</a>
		<?php }}} ?>
		
              <ul class="tabs">
                <li><a href="#profile-details" title="your profile/Stats"><?php echo JText::_("COM_QUESTIONS_USER_PROFILE"); ?></a></li>
                <li><a href="#profileactivity" title="your recent activity"><?php echo JText::_("COM_QUESTIONS_USER_ACTIVITY"); ?></a></li>
                <li><a href="#profilefavourits" title="your favorites"><?php echo JText::_("COM_QUESTIONS_USER_QA_FAVORITES"); ?></a></li>
                <li><a href="#profilegroups" title="your group"> <span class="bounty-indicator-tab"><?php echo count($groupdetails);?></span><?php echo JText::_("COM_QUESTIONS_MY_GROUPS"); ?></a></li>
                <?php $roleuser = JFactory::getUser(); if($roleuser->id == $user[0]->id){?>
                <li><a href="#mygroups" title="My Groups"><?php echo JText::_("COM_QUESTIONS_USER_JOINED_GROUPS"); ?></a></li>
               <!-- <li><a href="#notifications" title="your notifications"> <span class="bounty-indicator-tab"><?php echo count($groupdetails);?></span>Notifications</a></li> -->
                <?php }?>
                </ul>
       <div class="tabContainer">	
       <div id="profile-details" style="min-height:350px;" class="tabContent">     
        	 <?php
					echo $this->loadTemplate('profiledetails');
			 ?>
		</div>
		   <div id="profileactivity" style="min-height:350px;" class="tabContent">
		  		
				<h2><?php echo JText::_("COM_QUESTIONS_USER_PARTICIPATION_DETAILS"); ?></h2>
				<?php echo $this->useractivity($user[0]->id); ?>
				
		 </div>
         <div style="clear:both;"></div>
			<div id="profilefavourits" style="min-height:350px;" class="tabContent">
			  
                  <h1> Favourite Answers </h1>
                  <?php $favourites = $this->getfavouritedata($user[0]->id,'ansfav') ; 
                  if(is_array(@$favourite)){
                  foreach($favourites as $favourite){
                  //echo $favourite.'<br />';
                  if ($tmp1++ < $favlimit){
                        $this->getfavtemplate((int)$favourite,(int)0);
                  }
                  }
                  }
                  ?>
                  
                  <h1> Favourite Questions </h1>
                  <?php $favourites2 = $this->getfavouritedata($user[0]->id,'quesfav') ; 
                  if(is_array($favourites2)){
                  foreach($favourites2 as $favourite2){
                  //echo $favourite.'<br />';
                  if (@$tmp12++ < $favlimit){
                        $this->getfavtemplate((int)$favourite2,(int)1);
                  }
                  }
                  }
                  ?>
                  
                  <a href="<?php echo JRoute::_("index.php?option=com_questions&view=profiles&layout=detailfavs&id=".$user[0]->id); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/getdetails.png" ALT="Report It"></a>
			  
			  </div>
   <div id="profilegroups" style="min-height:350px;" class="tabContent">
   <?php $user3 = JFactory::getUser(); if($user[0]->id == $user3->id){ ?>
   	<div id="load" align="center"><img src="<?php echo $this->baseurl ?>/components/com_questions/media/images/loading.gif" width="28" height="28" align="absmiddle"/> Loading...</div>
    <?php } ?>
       <?php echo JTEXT::_('COM_QUESTIONS_GROUP_DETAILS'); ?>
       <hr />
       <?php if(count($groupdetails)>0){
		    echo '<table class="groupstable">';
		    echo '<tr>';
            echo '<th>' . JTEXT::_('COM_QUESTIONS_GROUP_NAME'). '</th>';
            echo '<th>' . JTEXT::_('COM_QUESTIONS_GROUP_MEMBERS') . '</th>';
            echo '<th>' . JTEXT::_('COM_QUESTIONS_CREATED_ON') . '</th>'; 
			$user3 = JFactory::getUser(); if($user[0]->id == $user3->id){
			echo '<th>' . JTEXT::_('COM_QUESTIONS_DELETE_GROUP') . '</th>'; 
			}
            echo '</tr>'; 
			foreach($groupdetails as $groups){
			if(!empty($groups->friendsid))
	   		{
				 $friends = unserialize($groups->friendsid);
				 $friendscount = count($friends);
	   		}else{
				 $friendscount = 0;
			}
	  		echo '<tr>';
            echo '<td>' . $groups->group_name . '</td>';
            echo '<td>' . $friendscount . '</td>';
            echo '<td>' . $groups->created . '</td>'; ?>
            <?php $user3 = JFactory::getUser(); if($user[0]->id == $user3->id){ ?>
			<td><div class="delete" id="<?php echo $groups->id; ?>"><a href="javascript:void(0);" class="creat_bttn" style="display:block">

<?php echo JText::_('COM_QUESTIONS_DELETE_GROUP'); ?>

                                        </a></div></td>
            <?php 
			}
            echo '</tr>';
			}
			echo '</table>';
		   
		   } ?>
       <br />
       <?php $user3 = JFactory::getUser(); if($user[0]->id == $user3->id){?>
             					
                                <div id="accordion">
									<h4 class="accordion-toggle">Create New Group</h4>
									<div class="accordion-content default">
                                    <div id="upload_group">

                                        <input style="color:#C9C8C8" type="text" name="uploadgrouptxt" id="uploadgrouptxt" onFocus="onFocusMenu(this,'<?php echo JTEXT::_('COM_QUESTIONS_ENTER_NEW_GROUP_NAME'); ?>');" onBlur="onBlurEvent(this,'<?php echo JTEXT::_('COM_QUESTIONS_ENTER_NEW_GROUP_NAME'); ?>');" value="<?php echo JTEXT::_('COM_QUESTIONS_ENTER_NEW_GROUP_NAME'); ?>"/>

                                    </div>
									
                                    <div class="special" style=""><a href="javascript:void(0);" class="creat_bttn" onclick="addnewgroup(<?php echo $user3->id; ?>)" style="display:block">

<?php echo JText::_('COM_QUESTIONS_CREATE_GROUP'); ?>

                                        </a>
                                        </div>
                                        
											<div id="groupError" style="clear: both; margin: 0 0 0 23px; "></div> 
                                     		</div>
                                            <h4 class="accordion-toggle">Select which Group to add members</h4>
											<div class="accordion-content">
                                                <div class="ui-widget">
                                                    <label for="groupid">Select Group(Enter Atleast 2 characters to start with): </label>
                                                    <input id="groupid" />
                                                </div>  
                                                
                                                <div class="ui-widget" style="margin-top: 2em; font-family: Arial;">
                                                    Groups:
                                                    <div id="logs" style="height: 200px; width: 350px; overflow-y:scroll;" class="ui-widget-content"></div>
                                                    <div id="groupids" style="display:none;"></div>
                                                </div>
                                            </div>
                                            
                                            <h4 class="accordion-toggle">Select Members for that Group</h4>
											<div class="accordion-content">
                                                                            
                                            <div id="project-label">Select a member (type some characters to locate user):</div>
                                            <img id="project-icon" src="images/transparent_1x1.png" class="ui-state-default" alt="" />
                                            <input id="project" />
                                            <input type="hidden" id="project-id" />
                                            <p id="project-description"></p>
               
                                        <div class="ui-widget" style="margin-top: 2em; font-family: Arial;">
                                        Result:
                                        <div id="log" style="height: 200px; width: 350px; overflow-y:scroll;" class="ui-widget-content"></div>
                                        <div id="userids" style="display:none;"></div>
                                    </div>
                                 
                                        </div>
                                        <div class="special" style=""><a href="javascript:void(0);" class="creat_bttn" onclick="sendnotification(<?php echo $user3->id; ?>)" style="display:block">
                                 

<?php echo JText::_('COM_QUESTIONS_SEND_JOINING_NOTIFICATION'); ?>

                                        </a>
                                        </div>
                                        <div id="profilepopup" style="clear: both; margin: 0 0 0 23px; "></div>
                                        </div>
                                     <?php } ?>
								
								
        </div>
        
            <div style="clear:both;"></div>	
            <?php $user3 = JFactory::getUser(); if($user[0]->id == $user3->id){ ?>
            <div id="mygroups" style="min-height:350px;" class="tabContent">
                            <?php
								echo $this->loadTemplate('mygroups');
							?>
            </div>
            <?php } ?>
            <div style="clear:both;"></div>	
            <!--
            <div id="notifications" style="min-height:350px;">
                            <?php
								//echo  $this->loadTemplate('notifications');
							?>
            <div style="clear:both;"></div>	
            </div>	
            -->
       

		</div>
        
		
        <div class="subheader">
        
       <div style="display:block;colour=gray;">
	    <h1><?php echo JText::_('COM_QUESTIONS_USER_PARTICIPATION'); ?></h1>
       </div>
        
<div id="questions-table" class="user-stats-table">
<a name="questions"></a>
<table class="summary-title">
    <tbody><tr>
        <td class="summary-header">
            <span class="summarycount ar">Latest Questions By <?php echo strtok($user[0]->name," "); ?></span>
        </td>
    </tr>
</tbody></table>
<?php
$this->gettemplate($user[0]->id,1);
?>
</div>
</div>
<div id="answers-table" class="user-stats-table">
<a name="answers"></a>
<table class="summary-title">
    <tbody><tr>
        <td class="summary-header">
            <div class="summarycount ar">Latest Answers By <?php echo strtok($user[0]->name," "); ?></div>
        </td>
    </tr>
</tbody></table>
<?php
$this->gettemplate($user[0]->id,0);
?>
</div>

    <div class="user-stats-table" style="clear:both;">
        <table id="tags-title" class="summary-title">
            <tbody>
            <tr>
                <td class="summary-header"><h2>User Tags</h2></td>
            </tr>
        </tbody>
        </table>
    </div>
    
<div style="position: relative;">

<ul style="float:left;list-style-type: none;">
    <?php
	    //$obj = json_decode($rows);
		$rows= $this->getTags($user[0]->id);
		if ($rows):
			foreach ($rows as $row):
			  if (@$tmp++ < $qtagsCount):
			?>
			<div class="Box_D_Tags">
				<li class="questions_category_li">
				<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $row); ?>"><?php echo $row; ?></a>
				</li>
			</div>
			<?php 
			endif;
			endforeach;
		endif;
	?>
</ul>
	
</div>	
</div>
</div>
    <div class="user-stats-table">
        <table class="summary-title">
            <tbody><tr>
                <td class="summary-header"><h2><?php echo JText::_('COM_QUESTIONS_RANK_ACHIEVED'); ?></h2></td>
            </tr>
        </tbody></table>
    </div>
    <div class="user-stats-table">
        <table>
<tbody><tr>
<td class="badge-col">
<a class="badge"><span class="badge3"></span>&nbsp;<?php 
$rp=$this->getRP($user[0]->id);
echo $rp['rank']; ?></a>
<br>
</td></tr>
</tbody></table>
</div>
</div>
<?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
	$this->escape(CopyrightHelper::copyright());
?>

<div style="clear:both;"></div>
