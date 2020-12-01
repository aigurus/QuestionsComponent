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
	Theme used from https://bootsnipp.com/snippets/359lB
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


jimport('joomla.user.helper');

if(!isset($uid)){
	$prouser = JFactory::getUser();
} else {
    $prouser = JFactory::getUser($uid);
}
//var_dump($this->userquestions); exit;
//$profile = JUserHelper::getProfile($prouser->id);

$userdet = $this->useractivity($prouser->id);

//var_dump($userdet); exit;

if(isset($userdet) && !empty($userdet)){
	$userdetails = $userdet[0];
}

/*Extra user profile Edit*/

//$this->escape($this->profilehits($uid));
//$groupdetails = $this->groupdetails;

$doc = JFactory::getDocument();

$doc->addStyleSheet("components/com_questions/css/profiles.css");
$doc->addStyleSheet("components/com_questions/css/simple-profile.css");
$doc->addStyleSheet("components/com_questions/css/jquery-ui-1.10.0.custom.min.css");
require_once 'components/com_questions/media/style.php';
$document = JFactory::getDocument();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
//$document->addScript('components/com_questions/media/js/ui/jquery-1.11.0.js');
$document->addScript('components/com_questions/media/js/ui/jquery-ui-1.10.2.js');

$document->addScript('components/com_questions/media/js/profiles.js');
$document->addStyleSheet("components/com_questions/css/style.css");
?>	
<div class="qcontainer">
<div class="row">
      <div class="col-xs-12 col-sm-9">
        
        <!-- User profile -->
        <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">User profile</h4>
          <?php 
			  $jinput = JFactory::getApplication()->input;
			  $uid = $jinput->getInt('id');
			  $user = JFactory::getUser();
		   ?>
          <?php if ( ($this->ownedit && ($user->id == $uid)) || $this->alledit) { ?>
          <button style="float:right" type="button" class="btn btn-primary" onclick="location.href='<?php echo JRoute::_('index.php?option=com_questions&view=edituser&id='.$prouser->id);?>'">Edit</button>
          <?php } ?>
          </div>
          <div class="panel-body">
            <div class="profile__avatar">
              <?php echo AvatarHelper::getAvatar($prouser->email,"questions_gravatar_big",64,0,$prouser->id); ?>
            </div>
            <?php if(isset($userdetails)) { ?>
            <div class="profile__header">
              <h4><?php echo $userdetails->username; ?> <small>(<?php echo $userdetails->rank; ?>)</small></h4>
              <p class="text-muted">
                <?php echo $userdetails->description; ?>
              </p>
              <?php if(isset($userdetails->url1)); { ?>
              <p>
                <a href="<?php echo $userdetails->url1; ?>" rel="nofollow"><?php echo $userdetails->url1; ?></a>
              </p>
              <?php } ?>
              <?php if(isset($userdetails->url2)); { ?>
               <p>
                <a href="<?php echo $userdetails->url2; ?>" rel="nofollow"><?php echo $userdetails->url2; ?></a>
              </p>
               <?php } ?>
               <?php if(isset($userdetails->url3)); { ?>
               <p>
                <a href="<?php echo $userdetails->url3; ?>" rel="nofollow"><?php echo $userdetails->url3; ?></a>
              </p>
               <?php } ?>
            </div>
            <?php } ?>
          </div>
        </div>

        <!-- User info -->
        <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">User info</h4>
          </div>
          <?php if(isset($userdetails)) { ?>
          <div class="panel-body">
            <table class="table profile__table">
              <tbody>
                <tr>
                  <th><strong>Location</strong></th>
                  <td><?php echo $userdetails->location; ?></td>
                </tr>
                <tr>
                  <th><strong>Company name</strong></th>
                  <td><?php echo $userdetails->company; ?></td>
                </tr>
                <tr>
                  <th><strong>Position</strong></th>
                  <td><?php echo $userdetails->position; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <?php } ?>
        </div>

        <!-- Community -->
        <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">Activities</h4>
          </div>
          <div class="panel-body">
            <table class="table profile__table">
              <tbody>
              	<?php if(isset($userdetails)) { ?>
                    <tr>
                      <th><strong>Questions</strong></th>
                      <td><?php echo $userdetails->asked; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                  <th><strong>Member since</strong></th>
                  <td><?php echo $prouser->registerDate; ?></td>
                </tr>
                <?php if(isset($userdetails)) { ?>
                <tr>
                  <th><strong>Last login</strong></th>
                  <td>
                  <?php

						$now = time(); // or your date as well
						$your_date = strtotime($userdetails->logdate);
						$datediff = $now - $your_date;
						$days = (int)$datediff / (60 * 60 * 24);
						if($days <= 1){
							echo round($days)." day Before";
						} else {
							echo round($days)." days Before";
						}
					?>

                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Latest posts -->
        
        <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">Latest Questions</h4>
          </div>
          <?php if(isset($userdetails)) { ?>

          <div class="panel-body">
            <div class="profile__comments">
              <?php if(isset($this->userquestions)) {?>
              <?php foreach($this->userquestions as $question){ 
					if($question->parent == 0){
						$id = $question->id;
					} else {
						$id = $question->parent;
					}
				
				?>
              <div class="profile-comments__item">
                <div class="profile-comments__avatar">
                  <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="...">
                </div>
                <div class="profile-comments__body">
                  <h5 class="profile-comments__sender">
                    <?php echo $userdetails->username; ?> <small>1 day ago</small>
                  </h5>
                  <div class="profile-comments__content">
					  <a href="<?php echo JRoute::_('index.php?option=com_questions&view=question&id='.$id);?>"><?php echo $question->title; ?></a>
                  </div>
                </div>
              </div>
              <?php }} ?>
            </div>
          </div>
          <?php } else { ?>
        	Sorry No Posts.
          <?php } ?>
        </div>
        
          <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">Latest Answers</h4>
          </div>
          <?php if(isset($userdetails)) { ?>

          <div class="panel-body">
            <div class="profile__comments">
             <?php if(isset($this->useranswers)) {?>
              <?php foreach($this->useranswers as $answer){ 
					if($answer->parent == 0){
						$id = $answer->id;
					} else {
						$id = $answer->parent;
					}
				
				?>
              <div class="profile-comments__item">
                <div class="profile-comments__avatar">
                  <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="...">
                </div>
                <div class="profile-comments__body">
                  <h5 class="profile-comments__sender">
                    <?php echo $userdetails->username; ?> <small>1 day ago</small>
                  </h5>
                  <div class="profile-comments__content">
					  <a href="<?php echo JRoute::_('index.php?option=com_questions&view=question&id='.$id);?>"><?php echo $answer->title; ?></a>
                  </div>
                </div>
              </div>
              <?php }} ?>
            </div>
          </div>
          <?php } else { ?>
        	Sorry No Posts.
          <?php } ?>
        </div>
		
      </div>

      <div class="col-xs-12 col-sm-3">
        
        <!-- Contact user -->
        <p>
          <a href="#" class="profile__contact-btn btn btn-lg btn-block btn-info" data-toggle="modal" data-target="#profile__contact-form">
            Contact user
          </a>
        </p>

        <hr class="profile__contact-hr">
        
        <!-- Contact info -->
        <?php if(isset($userdetails)) { ?>
        <div class="profile__contact-info">
          <div class="profile__contact-info-item">
            <div class="profile__contact-info-icon">
              <i class="fa fa-phone"></i>
            </div>
            <div class="profile__contact-info-body">
              <h5 class="profile__contact-info-heading">Work number</h5>
              <a href="tel:<?php echo $userdetails->workno; ?>"><?php echo $userdetails->workno; ?></a>
            </div>
          </div>
          <div class="profile__contact-info-item">
            <div class="profile__contact-info-icon">
              <i class="fa fa-phone"></i>
            </div>
            <div class="profile__contact-info-body">
              <h5 class="profile__contact-info-heading">Mobile number</h5>
              <a href="tel:<?php echo $userdetails->workno; ?>"><?php echo $userdetails->mobno; ?></a>
            </div>
          </div>
          <div class="profile__contact-info-item">
            <div class="profile__contact-info-icon">
              <i class="fa fa-envelope-square"></i>
            </div>
            <div class="profile__contact-info-body">
              <h5 class="profile__contact-info-heading">E-mail address</h5>
              <a href="mailto:admin@domain.com"><?php echo $userdetails->email; ?></a>
            </div>
          </div>
          <div class="profile__contact-info-item">
            <div class="profile__contact-info-icon">
              <i class="fa fa-map-marker"></i>
            </div>
            <div class="profile__contact-info-body">
              <h5 class="profile__contact-info-heading">Work address</h5>
              <?php echo $userdetails->workaddress; ?>
            </div>
          </div>
        </div>
        <?php } ?>

      </div>
    </div>
</div>

<style>
body{
    margin-top:20px;
    background:#f5f5f5;
}
/**
 * Panels
 */
/*** General styles ***/
.panel {
  box-shadow: none;
}
.panel-heading {
  border-bottom: 0;
}
.panel-title {
  font-size: 17px;
}
.panel-title > small {
  font-size: .75em;
  color: #999999;
}
.panel-body *:first-child {
  margin-top: 0;
}
.panel-footer {
  border-top: 0;
}

.panel-default > .panel-heading {
    color: #333333;
    background-color: transparent;
    border-color: rgba(0, 0, 0, 0.07);
}

/**
 * Profile
 */
/*** Profile: Header  ***/
.profile__avatar {
  float: left;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  margin-right: 20px;
  overflow: hidden;
}
@media (min-width: 768px) {
  .profile__avatar {
    width: 100px;
    height: 100px;
  }
}
.profile__avatar > img {
  width: 100%;
  height: auto;
}
.profile__header {
  overflow: hidden;
}
.profile__header p {
  margin: 20px 0;
}
/*** Profile: Table ***/
@media (min-width: 992px) {
  .profile__table tbody th {
    width: 200px;
  }
}
/*** Profile: Recent activity ***/
.profile-comments__item {
  position: relative;
  padding: 15px 16px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}
.profile-comments__item:last-child {
  border-bottom: 0;
}
.profile-comments__item:hover,
.profile-comments__item:focus {
  background-color: #f5f5f5;
}
.profile-comments__item:hover .profile-comments__controls,
.profile-comments__item:focus .profile-comments__controls {
  visibility: visible;
}
.profile-comments__controls {
  position: absolute;
  top: 0;
  right: 0;
  padding: 5px;
  visibility: hidden;
}
.profile-comments__controls > a {
  display: inline-block;
  padding: 2px;
  color: #999999;
}
.profile-comments__controls > a:hover,
.profile-comments__controls > a:focus {
  color: #333333;
}
.profile-comments__avatar {
  display: block;
  float: left;
  margin-right: 20px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
}
.profile-comments__avatar > img {
  width: 100%;
  height: auto;
}
.profile-comments__body {
  overflow: hidden;
}
.profile-comments__sender {
  color: #333333;
  font-weight: 500;
  margin: 5px 0;
}
.profile-comments__sender > small {
  margin-left: 5px;
  font-size: 12px;
  font-weight: 400;
  color: #999999;
}
@media (max-width: 767px) {
  .profile-comments__sender > small {
    display: block;
    margin: 5px 0 10px;
  }
}
.profile-comments__content {
  color: #999999;
}
/*** Profile: Contact ***/
.profile__contact-btn {
  padding: 12px 20px;
  margin-bottom: 20px;
}
.profile__contact-hr {
  position: relative;
  border-color: rgba(0, 0, 0, 0.1);
  margin: 40px 0;
}
.profile__contact-hr:before {
  content: "OR";
  display: block;
  padding: 10px;
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  background-color: #f5f5f5;
  color: #c6c6cc;
}
.profile__contact-info-item {
  margin-bottom: 30px;
}
.profile__contact-info-item:before,
.profile__contact-info-item:after {
  content: " ";
  display: table;
}
.profile__contact-info-item:after {
  clear: both;
}
.profile__contact-info-item:before,
.profile__contact-info-item:after {
  content: " ";
  display: table;
}
.profile__contact-info-item:after {
  clear: both;
}
.profile__contact-info-icon {
  float: left;
  font-size: 18px;
  color: #999999;
}
.profile__contact-info-body {
  overflow: hidden;
  padding-left: 20px;
  color: #999999;
}
.profile__contact-info-body a {
  color: #999999;
}
.profile__contact-info-body a:hover,
.profile__contact-info-body a:focus {
  color: #999999;
  text-decoration: none;
}
.profile__contact-info-heading {
  margin-top: 2px;
  margin-bottom: 5px;
  font-weight: 500;
  color: #999999;
}
</style>