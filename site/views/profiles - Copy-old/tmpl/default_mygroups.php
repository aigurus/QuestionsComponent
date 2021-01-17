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
  <div style="padding:10px;"> <strong>My Groups</strong> </div>
    <hr />
    <div style="width:95%;">
    <table class="csstable">
    <tr>
    <th><strong>Group Name:</strong></th>
    <th><strong>Group Admin</strong></th>
    <th><strong>Group members</strong></th>
    <!--<th><strong>Leave Group<br>Inc Fee</strong></th>-->
    </tr>
    <?php $mygroups = $this->mygroups; 
		  $model = $this->getModel('profiles');
		  $i=1;
	?>
    <?php 
	if(isset($mygroups) && count($mygroups)>0){
	foreach($mygroups as $mygroup){ 
	 $mygroupdetail  = $model->getMyGroupDetails($mygroup);
	 $mygroupdetails = $mygroupdetail[0];
	 if(!empty($mygroupdetails->group_name)){
	?>
    <tr>
    <td><?php echo  $mygroupdetails->group_name; ?></td>
    <td><?php echo  JFactory::getUser($mygroupdetails->userid)->name; ?></td>
    <td><?php echo  count(unserialize($mygroupdetails->requestsent)); ?></td>
    <!--<td><?php //echo  $mygroupdetails->group_name; ?></td>-->
    </tr>
    <?php 
	 }
	$i++;
	}} ?>
    </table>
    
    </div>
	<div style="clear:both"></div>
<style type="text/css">
table.csstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #999999;
	border-collapse: collapse;
}
table.csstable th {
	background:rgb(0, 136, 204);
	border-width: 1px;
	padding: 8px;
	color:#fff;
	border-style: solid;
	border-color: #999999;
}
table.csstable td {
	background:rgb(245, 245, 245);
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
}
</style>