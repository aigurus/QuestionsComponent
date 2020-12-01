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
	Email: admin@extensiondeveloper.com
	support: support@extensiondeveloper.com
	Website: http://www.extensiondeveloper.com
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
//echo $profile->profile['address1'];
//var_dump($user[0]);

$doc = JFactory::getDocument();

$doc->addStyleSheet("components/com_questions/css/profiles.css");
$doc->addStyleSheet("components/com_questions/css/simple-profile.css");
$doc->addStyleSheet("components/com_questions/css/jquery-ui-1.10.0.custom.min.css");
require_once 'components/com_questions/media/style.php';

$document = JFactory::getDocument();
$document->addScript('http://code.jquery.com/jquery-1.9.1.js');
$document->addScript('http://code.jquery.com/ui/1.10.3/jquery-ui.js');

//$document->addStyleSheet('http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
$document = JFactory::getDocument();
	$document->addScriptDeclaration('
		 	$(function() {
				$("#simpaleTabs").tabs();
			});

');
//$doc->addScript("http://maps.google.com/maps/api/js?sensor=false");

?>
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
		<div id="simpaleTabs">	
                <ul>
                <li><a href="#profile-details" title="your profile/Stats">Profile</a></li>
                <?php /*<li><a href="#profilereputation" title="Reputation history">Favourite Users</a></li>*/?>
                <li><a href="#profileactivity" title="your recent activity">activity</a></li>
                <?php /*<li><a href="#profileresponses" title="your recent responses"> <span class="bounty-indicator-tab">2</span>responses</a></li>*/?>
                <li><a href="#profilefavourits" title="your favorites">Favorite QA</a></li>
                </ul>
       <div id="profile-details" style="min-height:350px;">     
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
							 echo AvatarHelper::getAvatar($user[0]->email,"logo",128,1,$user[0]->id); ?>
							</td>
						<?php }?>
                        </tr>
                        <?php
                        $show_points = $this->escape($params->get('show_points', 1));
						if($show_points == 1){
						?>
                        <tr>
                            <td class="summaryinfo">
				<span class="summarycount"> <?php 
			$rp=$this->getRP($user[0]->id);
			echo $rp['points']; ?></span>
                                <div style="margin-top:5px; font-weight:bold">Points</div>
                            </td>
                        </tr>
                        
                        <?php } ?>
                        
                        <?php
                        $show_profilehits = $this->escape($params->get('show_profilehits', 1));
						if($show_profilehits == 1){
						?>
                        <tr style="height:30px">
                            <td style="vertical-align:bottom" class="summaryinfo"><?php echo $this->GetProfileHits($user[0]->id);?> views</td>
                        </tr>
                        <?php } ?>
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
 echo JRoute::_('index.php?option=com_users&view=profile&layout=edit&itemid='.$reguser->id); ?><?php echo $redirectUrl;?>">EDIT PROFILE
 </a></div>
 				<?php }?>
                    
			        <h2 style="margin-top:20px">
					Registered User</h2>
                    <table class="user-details">
                        <tbody>
                        <?php
                        $show_username = $this->escape($params->get('show_username', 1));
						if($show_username == 1){
						?>
                        <tr>
                            <td style="width:120px">User Name</td>
                            <td style="width:230px"><b><?php echo $user[0]->username; ?></b></td>
                        </tr>
                        <?php } ?>
                        <?php
                        $show_registerdate = $this->escape($params->get('show_registerdate', 1));
						if($show_registerdate == 1){
						?>
                        <tr>
                            <td>member Since</td>
                            <td><span title="2011-02-24 07:15:56Z" class="cool"><?php echo $user[0]->registerDate; ?></span></td>
                        </tr>
                        <?php } ?>
                         <?php
                        $show_lastvisitdate = $this->escape($params->get('show_lastvisitdate', 1));
						if($show_lastvisitdate == 1){
						?>
                        <tr>
                            <td>Last Online</td>
                            <td><span class="supernova"><span class="relativetime" title="2011-06-09 06:31:13Z"><?php echo $user[0]->lastvisitDate; ?></span></span></td>
                        </tr>
                        <?php } ?>
                        <?php
                        $show_userwebsite = $this->escape($params->get('show_userwebsite', 1));
						if($show_userwebsite == 1){
						?>
                        <tr>
                            <td>website</td>
                            <td>
                                <div class="no-overflow"><?php 
								if(!empty($profile->profile['website'])){
								echo $profile->profile['website']; 
								}?>
                                </div>                                
                            </td>
                        </tr>
                         <?php } ?>
                         <?php
                        $show_useremail = $this->escape($params->get('show_useremail', 1));
						if($show_useremail == 1){
						?>   
                        <tr>
                            <td>email</td>
                            <td>  <?php echo $user[0]->email; ?>
                            </td>
                        </tr>
                         <?php } ?>
                         <?php
                        $show_userrealname = $this->escape($params->get('show_userrealname', 1));
						if($show_userrealname == 1){
						?>
                        <tr>
                            <td>Real name</td>
                            <td>
                               <?php echo ucfirst($user[0]->name); ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php
                        $show_usercountry = $this->escape($params->get('show_usercountry', 1));
						if($show_usercountry == 1){
						?>
                        <tr>
                            <td>Country</td>
                            <td class="label adr">
                                <?php 
								if(!empty($profile->profile['country'])){								
								echo $profile->profile['country']; 
								}
								?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php
                        $show_userdob = $this->escape($params->get('show_userdob', 1));
						if($show_userdob == 1){
						?>
                        <tr>
                            <td>Date of Birth</td>
                            <td>
                             <?php 
							 if(!empty($profile->profile['dob'])){	
							 echo $profile->profile['dob']; 
							 }
							 ?>   
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody></table>
	               </td>
		      </tr>
        </tbody></table>
		</div>
		   <div id="profileactivity" style="min-height:350px;">
		  		
				<h2><?php echo JText::_("COM_QUESTIONS_USER_PARTICIPATION_DETAILS"); ?></h2>
				<?php echo $this->useractivity($user[0]->id); ?>
				
		 </div>
         <div style="clear:both;"></div>
			<div id="profilefavourits" style="min-height:350px;">
			  
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
              <div style="clear:both;"></div>	
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
