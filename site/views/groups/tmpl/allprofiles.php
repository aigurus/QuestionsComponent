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
$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_questions/css/allprofiles.css");
?>
<style>
div.questionbox{
	background: none repeat scroll 0 0 #ccc;
    border-bottom: 1px solid #AAAAAA;
    border-right: 1px solid #AFAFAF;
    border: 1px solid #CCCCCC;
    float: left;
    margin: ;
    padding: 1% 2%;
    position: relative;
    width: 95%;
}
div.questions_filters {
	float: left;
	clear: both;
	width: 95%;
	border: 1px solid #ccc;
	margin: ;
	background: #eeeeee;
	padding:1% 2%;
}

div.questions_filters ul
{
list-style: none;
padding: 0;
margin: 0;
}

div.questions_filters ul li{
	list-style: none;	
	display: inline-block;
}

div.questions_filters ul li a {
	background: url(background.gif) #fff bottom left repeat-x;
	height: 2em;
	line-height: 2em;
	float: left;
	width: 9em;
	display: block;
	border: 0.1em solid #dcdce9;
	color: #0d2474;
	text-decoration: none;
	text-align: center;
}

div.questions_filters ul li a.active {
	font-weight: bold;
}
img{
border-radius: 20px 20px 20px 20px;
-moz-border-radius: 20px 20px 20px 20px;
-webkit-border-radius: 20px 20px 20px 20px;
border: 5px inset #00000C;
}
</style>
<div class="questionbox">
<div class="questions_filters">
<ul><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions"); ?>">Home</a></li><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=form&layout=edit"); ?>"><?php echo JText::_("COM_QUESTIONS_ASK_A_QUESTION"); ?></a></li>
	</ul></div></div>
    <div style="clear:both"></div>
<?php
echo $this->data;
$this->escape(CopyrightHelper::copyright());
?>