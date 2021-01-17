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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<table class="vcard">
            <tbody><tr>
                <td style="vertical-align:top; width:170px">
                    <table>
                        <tbody><tr>
						<?php 
						$uid = JRequest::getInt( 'id' );
						$user=array();
						$user=$this->GetUserDetails($uid);
						$app = JFactory::getApplication();
						$params = $app->getParams();
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
				$reguser =  JFactory::getUser(); 
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