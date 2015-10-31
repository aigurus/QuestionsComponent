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
$articleid = $params->get('helparticleid', 1);
$favlimit = $params->get('favlimit', 2);
$qtagsCount = $params->get('tagsCount', 5);
$profile_main = $params->get('profile_main', '550px');
$uid = JRequest::getInt( 'id' );
$user=array();
$user=$this->GetUserDetails($uid);
/*Extra user profile Edit*/
jimport('joomla.user.helper');
$prouser = JFactory::getUser();
$profile = JUserHelper::getProfile($prouser->id);
$this->profilehits($uid);
//echo $profile->profile['address1'];
//var_dump($user[0]);

$doc = JFactory::getDocument();
$doc->addScript("components/com_questions/media/js/ui/jquery-1.8.2.js");
$doc->addScript("components/com_questions/media/js/ui/jquery.ui.tabs.js");
$doc->addScript("components/com_questions/media/js/ui/jquery.ui.widget.js");
$doc->addScript("components/com_questions/media/js/ui/jquery-ui-1.9.1.custom.js");
$doc->addScript("http://maps.google.com/maps/api/js?sensor=false");
$doc->addScript($JQueryNoConflict);
?>
<div class="questionbox">
<div class="questions_filters">
<ul><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions"); ?>">Home</a></li><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=form&layout=edit"); ?>">Ask A Question</a></li>
	
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
        .summary-header h2 { font-size:200%; } 
        .summary-title { clear:both; margin:20px; width:95%; float:middle;}        
        .vcard { width:<?php echo $profile_main; ?>; margin-top:-10px; }
        .badge-col { width: 200px; }
        .user-stats-table .question-summary { width: <?php echo $profile_main; ?>; }
        #user-avatar { padding:20px 20px 8px 20px; }
		#mainbar-full { width: <?php echo $profile_main; ?>	}
    </style>
	<script type="text/javascript">
	var displayDuration = 5000;
	var loadFactor = 5;
	var selectCount = 0;
	$(document).ready(function() {
	$('#tabs1,#tabs2').tabs({
	fx: {
	opacity: "toggle",
	duration: "normal"
	},
	select: function() {
	var localCount = ++selectCount;
	setTimeout(function() {
	if (localCount == selectCount) {
	$('#tabs').tabs("rotate", displayDuration, false)
	}
	}, displayDuration * loadFactor)
	}
	}).tabs("rotate", displayDuration, false);
	});
	/**Ip for google maps**/
	$(document).ready(function(){
    try{
        IPMapper.initializeMap("map");
        IPMapper.addIPMarker("<?=$_SERVER['REMOTE_ADDR'];?>");
    } catch(e){
        //handle error
    }
	});
	</script>
</head>
<body class="user-page">

    <div class="container">
                    
    <div id="mainbar-full">
        <div class="subheader">
            <h1><?php 
			$arr = explode(' ',trim($user[0]->name));
			echo ucfirst($arr[0]); ?>
			</h1>
		<?php 
		$reguser = JFactory::getUser();
		if($reguser->id != $user[0]->id){
		$favarray = unserialize($this->getFavourite2($reguser->id));
		$favarray = array();
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
		<?php }} ?>
			
			</div>
			<div  id="tabs1">
           	<ul>
            <li><a href="#profile-details" title="your profile/Stats">Profile</a></li>
            <?php /*<li><a href="#profilereputation" title="Reputation history">Favourite Users</a></li>*/?>
            <li><a href="#profileactivity" title="your recent activity">activity</a></li>
            <?php /*<li><a href="#profileresponses" title="your recent responses"> <span class="bounty-indicator-tab">2</span>responses</a></li>*/?>
            <li><a href="#profilefavourits" title="your favorites">Favorite QA</a></li>
			</ul>
			
       <div id="profile-details" class="tabs-section">     
        <table class="vcard">
            <tbody><tr>
                <td style="vertical-align:top; width:170px">
                    <table>
                        <tbody><tr>
						<?php 
						$app = JFactory::getApplication();
						$parameters = $app->getParams();
						$appParams = json_decode(JFactory::getApplication()->getParams());
						if (isset($appParams->display_gravatars) && $appParams->display_gravatars!=0){
						?>
                            <td id="user-avatar">
							 <?php 
							 AvatarHelper::getAvatar($user[0]->email,"logo",128,1,$user[0]->id); ?>
							</td>
						<?php }?>
                        </tr>
                        <tr>
                            <td class="summaryinfo">
				<a title="view reputation privileges" href="/privileges"><span class="summarycount"><?php 
			$rp=$this->getRP($user[0]->id);
			echo $rp['points']; ?></span></a>
                                <div style="margin-top:5px; font-weight:bold">Points</div>
                            </td>
                        </tr>
                        <tr style="height:30px">
                            <td style="vertical-align:bottom" class="summaryinfo"><?php echo $this->GetProfileHits($user[0]->id);?> views</td>
                        </tr>
                    </tbody></table>
                </td>
                <td style="vertical-align: top; width:350px">
				<?php
				$redirectUrl = JRoute::_("index.php?option=com_questions&view=profile&userid=".$user[0]->id);
				$redirectUrl = urlencode(base64_encode($redirectUrl));  
				$redirectUrl = '&return='.$redirectUrl; 
				$reguser = JFactory::getUser(); 
				?>
				<?php if($reguser->id == $user[0]->id){?>
                    <div style="float: right; margin-top: 19px; margin-right: 4px">
 <a class="post-tag" href=<?php 
 echo JRoute::_('index.php?option=com_users&view=profile&layout=edit&itemid='.$reguser->id); ?><?php echo $redirectUrl;?>">EDIT PROFILE<?php }?></a>
                    </div>
			        <h2 style="margin-top:20px">
					Registered User</h2>
                    <table class="user-details">
                        <tbody><tr>
                            <td style="width:120px">User Name</td>
                            <td style="width:230px"><b><?php echo $user[0]->username; ?></b></td>
                        </tr>
                        <tr>
                            <td>member Since</td>
                            <td><span title="2011-02-24 07:15:56Z" class="cool"><?php echo $user[0]->registerDate; ?></span></td>
                        </tr>
                        <tr>
                            <td>Last Online</td>
                            <td><span class="supernova"><span class="relativetime" title="2011-06-09 06:31:13Z"><?php echo $user[0]->lastvisitDate; ?></span></span></td>
                        </tr>
                        <tr>
                            <td>Last visited</td>
                            <td>
                                <span style="cursor:pointer" class="no-overflow" id="days-visited">
                                    43 days, 3 consecutive
                                </span>
                             </td>
                        </tr>
                        <tr>
                            <td>website</td>
                            <td>
                                <div class="no-overflow"><?php echo $profile->profile['website']; ?></div>                                
                            </td>
                        </tr>
                        
                            
                        <tr>
                            <td>email</td>
                            <td>  <a href="mailto:".<?php echo $user->email; ?> class="user-email"><?php echo $user[0]->email; ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td>Real name</td>
                            <td>
                               <?php echo ucfirst($user[0]->name); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td class="label adr">
                                <?php echo $profile->profile['country']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Birth</td>
                            <td>
                             <?php echo $profile->profile['dob']; ?>   
                            </td>
                        </tr>
                    </tbody></table>
					</div>	 
					
                </td>
		      </tr>
        </tbody></table>
		</div>
		<?php /*
		<div id="profilereputation" class="tabs-section">
		<div style="clear:both;"></div>
				<script type="text/javascript">
					function initialize2()
					{
					var latlng = new google.maps.LatLng(9.931544168615512,76.27632894178791);
					var opt =
					{
					center:latlng,
					zoom:10,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					disableAutoPan:false,
					navigationControl:true,
					navigationControlOptions: {style:google.maps.NavigationControlStyle.SMALL },
					mapTypeControl:true,
					mapTypeControlOptions: {style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}
					};
					var map = new google.maps.Map(document.getElementById("map2"),opt);
					var marker= new google.maps.Marker({
					position: new google.maps.LatLng(9.931544168615512,76.27632894178791),
					title: "CodeGlobe",
					clickable: true,
					map: map
					});
					
					
					var infiwindow = new google.maps.InfoWindow(
					{
					content: " I am here! "
					
					});
					
					
					google.maps.event.addListener(marker,'mouseover',function(){
					infiwindow.open(map,marker);
					});
					google.maps.event.addListener(marker,'mouseout',function(){
					infiwindow.close(map,marker);
					});
					
					
					}
					function test(event)
					{
					
					alert( event.latLng.lat());
					alert(event.latLng.lng());
					
					}
			    initialize2();
				</script>
				<div id="map2" >
				</div>
		 </div>
		 */ ?>
		  <div id="profileactivity" class="tabs-section">
		  		<div style="clear:both;"></div>
				<h2><?php echo JText::_(COM_QUESTIONS_USER_PARTICIPATION_DETAILS); ?></h2>
				<?php echo $this->useractivity($user[0]->id); ?>
				<div style="clear:both;"></div>
		 </div>
		 <?php /*
		  <div id="profileresponses" class="tabs-section">
		  <div style="clear:both;"></div>
				    <div id="response">
					<h1>BEVERAGES</h1>
					<p>House Blend, $1.49</p>
					<p>Mocha Cafe Latte, $2.35</p>
					<p>Cappuccino, $1.89</p>
					<p>Chai Tea, $1.85</p>
					<h1>ELIXIRS</h1>
					<p>
					We proudly serve elixirs brewed by our friends
					at the Head First Lounge.
					</p>
					<p>Green Tea Cooler, $2.99</p>
					<p>Raspberry Ice Concentration, $2.99</p>
					<p>Blueberry Bliss Elixir, $2.99</p>
					<p>Cranberry Antioxidant Blast, $2.99</p>
					<p>Chai Chiller, $2.99</p>
					<p>Black Brain Brew, $2.99</p>
				</div>
		 */ ?>
			<div id="profilefavourits" class="tabs-section">
			  <div style="clear:both;"></div>
			  <h1> Favourite Answers </h1>
			  <?php $favourites = $this->getfavouritedata($user[0]->id,'ansfav') ; 
			  if(is_array($favourites)) {
			  foreach($favourites as $favourite){
			  //echo $favourite.'<br />';
			  if ($tmp1++ < $favlimit){
					$this->getfavtemplate((int)$favourite,(int)0);
			  }
			  }
			  }
			  ?>
			  <div style="clear:both;"></div>
			  <h1> Favourite Questions </h1>
			  <?php $favourites2 = $this->getfavouritedata($user[0]->id,'quesfav') ; 
			  if(is_array($favourites2)) {
			  foreach($favourites2 as $favourite2){
			  //echo $favourite.'<br />';
			  if ($tmp12++ < $favlimit){
					$this->getfavtemplate((int)$favourite2,(int)1);
			  }
			  }
			  }
			  ?>
			  <div style="clear:both;"></div>
			  <a href="<?php echo JRoute::_("index.php?option=com_questions&view=profiles&layout=detailfavs&id=".$user[0]->id); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/getdetails.png" ALT="Report It"></a>
			  <div style="clear:both;"></div>
			  </div>	
		</div>
		
        <div class="subheader">
        <div style="clear:both;"></div>
       <div style="display:block;colour=gray;">
	    <h1><?php echo JText::_(COM_QUESTIONS_USER_PARTICIPATION); ?></h1>
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

    <div class="user-stats-table">
        <table id="tags-title" class="summary-title">
            <tbody><tr>
                <td class="summary-header"><h2>User Tags</h2></td>
            </tr>
        </tbody></table>
    </div>
	<div>

<ul style="list-style: none;">
    <?php
	    //$obj = json_decode($rows);
		$rows= $this->getTags($user[0]->id);
		if ($rows):
			foreach ($rows as $row):
			  if ($row):
			  if(isset($row)){
			  foreach ($row as $indiv_tags):
			  $indiv_tags = json_decode($indiv_tags);
			  if(isset($indiv_tags)){
			  foreach ($indiv_tags as $indiv_tag):
			   if ($tmp++ < $qtagsCount){
			  //$indiv_tag = implode(",", $indiv);
			//$qtags = json_decode( $row->tags );
			?>
			<div class="Box_D">
				<li class="questions_category_li">
				<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $indiv_tag); ?>"><?php echo $indiv_tag; ?></a>
				</li>
			</div>
			<?php 
			}
			endforeach;
			}
			endforeach;
			}
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
                <td class="summary-header"><h2><?php echo JText::_(COM_QUESTIONS_RANK_ACHIEVED); ?></h2></td>
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
<p style="text-align:right;"><small>Proudly powered by </small><a target="_blank" href="http://phpseo.net/">PHP SEO</a></p>
