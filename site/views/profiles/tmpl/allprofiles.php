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
$doc->addStyleSheet("components/com_questions/css/style.css");
$rows = $this->data;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
?>
<main id="tt-pageContent" class="tt-offset-small">
    <div class="qcontainer">
		  This is a community of creatives who come together to share ideas and help each other succeed.
				<h3 class="tt-title">
					Admins
				</h3>
				<div class="tt-list-avatar">
					<div class="row">
					<hr>  
					<?php foreach($rows as $row) { ?>
						<div class="col-6 col-md-4 col-lg-3">
							<a href="#" class="tt-avatar">
								<div class="tt-col-icon">
									<i class="tt-icon"><div class="circle c01"><?php 

									$user = Factory::getUser($row->userid);
									$name = substr($user->name,0,1);
									$userFirstname = strtoupper($name);
									echo $userFirstname; ?></div></i>
								</div>
								<div class="tt-col-description">
								<?php 
									$url = Route::_("index.php?option=com_questions&view=profiles&id=".$user->id); ?>
									<?php echo "<a href='".$url."' rel='nofollow'>$user->name</a>"; ?>
									
									<div class="tt-value"><?php echo $row->rank; ?></div>
								</div>
							</a>
						</div>		

				   <?php } ?>

				<div style="clear:both"></div>    
				<hr> 
			</div>
			<div class="table-responsive-sm tt-indent-top">
				<table class="table-01">
					<caption>Site Statistics</caption>
					<thead>
						<tr>
							<th>Topic</th>
							<th>All Time</th>
							<th>Last 7</th>
							<th>Last 30</th>
						</tr>
					</thead>
					<tbody class="table-zebra">
						<tr>
							<td>Topics</td>
							<td><?php echo $this->totaltopics; ?></td>
							<td><?php echo $this->last7topics; ?></td>
							<td><?php echo $this->last30topics; ?></td>
						</tr>
						<tr>
							<td>Posts</td>
							<td><?php echo $this->totalposts; ?></td>
							<td><?php echo $this->last7posts; ?></td>
							<td><?php echo $this->last30posts; ?></td>
						</tr>
						<tr>
							<td>Users</td>
							<td><?php echo $this->totalusers; ?></td>
							<td><?php echo $this->last7users; ?></td>
							<td><?php echo $this->last30users; ?></td>
						</tr>
						
					</tbody>
				</table>
			</div>
	   </div>
	</div>
</main>
    
</br>  
    
    
    
<?php
//echo $this->data;
$this->escape(CopyrightHelper::copyright());
?>