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
$groupdetails = $this->groupdetails;

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
<div>
<h1>Twitter Profile Card</h1>
<p>You can use with Twitter API, bot or manual.</p>
<!-- code start -->
<div class="twPc-div">
    <a class="twPc-bg twPc-block"></a>

	<div>
		<div class="twPc-button">
            <!-- Twitter Button | you can get from: https://about.twitter.com/tr/resources/buttons#follow -->
            <a href="https://twitter.com/mertskaplan" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false" data-dnt="true">Follow @mertskaplan</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <!-- Twitter Button -->   
		</div>

		<a title="Mert S. Kaplan" href="https://twitter.com/mertskaplan" class="twPc-avatarLink">
			<img alt="Mert S. Kaplan" src="https://mertskaplan.com/wp-content/plugins/msk-twprofilecard/img/mertskaplan.jpg" class="twPc-avatarImg">
		</a>

		<div class="twPc-divUser">
			<div class="twPc-divName">
				<a href="https://twitter.com/mertskaplan">Mert S. Kaplan</a>
			</div>
			<span>
				<a href="https://twitter.com/mertskaplan">@<span>mertskaplan</span></a>
			</span>
		</div>

		<div class="twPc-divStats">
			<ul class="twPc-Arrange">
				<li class="twPc-ArrangeSizeFit">
					<a href="https://twitter.com/mertskaplan" title="9.840 Tweet">
						<span class="twPc-StatLabel twPc-block">Tweets</span>
						<span class="twPc-StatValue">9.840</span>
					</a>
				</li>
				<li class="twPc-ArrangeSizeFit">
					<a href="https://twitter.com/mertskaplan/following" title="885 Following">
						<span class="twPc-StatLabel twPc-block">Following</span>
						<span class="twPc-StatValue">885</span>
					</a>
				</li>
				<li class="twPc-ArrangeSizeFit">
					<a href="https://twitter.com/mertskaplan/followers" title="1.810 Followers">
						<span class="twPc-StatLabel twPc-block">Followers</span>
						<span class="twPc-StatValue">1.810</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- code end -->
</div>
</div>  

<?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
	$this->escape(CopyrightHelper::copyright());
?>

<div style="clear:both;"></div>
<style>
.twPc-div {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #e1e8ed;
    border-radius: 6px;
    height: 500px;
    max-width: auto; // orginal twitter width: 290px;
}
.twPc-bg {
    background-image: url("components/com_questions/css/images/bg-banner.jpg");
    background-position: 0 50%;
    background-size: 100% auto;
    border-bottom: 1px solid #e1e8ed;
    border-radius: 4px 4px 0 0;
    height: 200px;
    width: 100%;
}
.twPc-block {
    display: block !important;
}
.twPc-button {
    margin: -35px -10px 0;
    text-align: right;
    width: 100%;
}
.twPc-avatarLink {
    background-color: #fff;
    border-radius: 6px;
    display: inline-block !important;
    float: left;
    margin: -30px 5px 0 8px;
    max-width: 100%;
    padding: 1px;
    vertical-align: bottom;
}
.twPc-avatarImg {
    border: 2px solid #fff;
    border-radius: 7px;
    box-sizing: border-box;
    color: #fff;
    height: 72px;
    width: 72px;
}
.twPc-divUser {
    margin: 5px 0 0;
}
.twPc-divName {
    font-size: 18px;
    font-weight: 700;
    line-height: 21px;
}
.twPc-divName a {
    color: inherit !important;
}
.twPc-divStats {
    margin-left: 11px;
    padding: 10px 0;
}
.twPc-Arrange {
    box-sizing: border-box;
    display: table;
    margin: 0;
    min-width: 100%;
    padding: 0;
    table-layout: auto;
}
ul.twPc-Arrange {
    list-style: outside none none;
    margin: 0;
    padding: 0;
}
.twPc-ArrangeSizeFit {
    display: table-cell;
    padding: 0;
    vertical-align: top;
}
.twPc-ArrangeSizeFit a:hover {
    text-decoration: none;
}
.twPc-StatValue {
    display: block;
    font-size: 18px;
    font-weight: 500;
    transition: color 0.15s ease-in-out 0s;
}
.twPc-StatLabel {
    color: #8899a6;
    font-size: 10px;
    letter-spacing: 0.02em;
    overflow: hidden;
    text-transform: uppercase;
    transition: color 0.15s ease-in-out 0s;
}
</style>
