<?php
/*
    Copyright (C)  2012 Sweta ray.
    Permission is granted to copy, distribute and/or modify this document
    under the terms of the GNU Free Documentation License, Version 1.3
    or any later version published by the Free Software Foundation;
    with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
    A copy of the license is included in the section entitled 'GNU
    Free Documentation License'
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
$app = JFactory::getApplication();
$params = $app->getParams();
?>
<style>

div.Box_D {
border-radius: 5px; 
-moz-border-radius: 5px; 
-webkit-border-radius: 5px; 
border: 1px solid #800000;
width:<?php echo $params->get('box_width', '180px') ?>;
height: <?php echo $params->get('box_height', '50px') ?>;
float:left;
margin:5px;
}

div.Box_D_Tags {
border-radius: 5px; 
-moz-border-radius: 5px; 
-webkit-border-radius: 5px; 
border: 1px solid #800000;
width:<?php echo $params->get('box_width_tags', '140px') ?>;
height: <?php echo $params->get('box_height_tags', '30px') ?>;
float:left;
margin:5px;
}

div.questions_category {
list-style: none;
font-family: Georgia, "Times New Roman", Times, serif;
font-size: <?php echo $params->get('category_text_size_ul', '12px') ?>;
}
div.questions_category a {
background-repeat: no-repeat;
background-position: right;
padding-right: 5px;
padding-left: 5px;
display: inline;
line-height: 20px;
text-decoration: none;
color: <?php echo $params->get('category_color_ul', '#371C1C') ?>;
}
/*
div.questions_category {
list-style-type: none;
height: auto;
margin: auto;
}*/
.questions_category_li{
list-style: none; 
color:brown;
float:left;
width:100%;
text-align: center;
}
.questions_category_li a{
color:<?php echo $params->get('category_color_li', '#371C1C') ?>;
font-size: <?php echo $params->get('category_text_size_li', '12px') ?>;
float: left;
text-indent:<?php echo $params->get('sub_cat_text_indent', '10px') ?>;
}
div.questions_category a:hover {
color: <?php echo $params->get('category_color_onhover', '#FFF') ?>;
}

.summarycount {
    text-align: left;
    color: #808185;
    font-size: 150%;
    font-weight: bold;
}
.summaryinfo {
    color: #808185;
    text-align: center;
}
.badge,
.badge-tag {
    color: #fff !important;
    background-color: #8EB94B;
    border: 1px solid #8EB94B;
    margin: 0 3px 3px 0;
    padding: 0px 6px 0px 3px;
    display: inline-block;
    text-decoration: none;
    line-height: 1.9;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
}
.badge:hover,
.badge-tag:hover {
    border: 1px solid #555;
    background-color: #555;
    text-decoration: none;
}
.badge-tag:hover {
    color: #fff !important;
    border: 2px solid #555;
}
.badge-tag {
    color: #333 !important;
    border: 2px solid #333;
    background-color: #eee;
}
.badgecount {
    padding-left: 1px;
    color: #808185;
}
#map2{
width:300px;
height:300px;
float:left;
position:absolute;
left:50px;
top:50px;
}
</style>