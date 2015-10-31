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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
$id = JRequest::getint( 'id' );
?>
<h1>EDIT</h1>

<form action="<?php echo JRoute::_('index.php?option=com_questions&amp;task=rankedit.save&amp;id='.$id);?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	
	
	<div class="formelm">
		<?php echo $this->form->getLabel("pointsreq");?>
        <?php echo $this->form->getInput("pointsreq");?>
	</div>
	<br />
	<div class="formelm">
		<?php echo $this->form->getLabel("rank");?>
		<?php echo $this->form->getInput("rank");?>
	</div>
	<br />

	<input type="hidden" name="task" value="rankedit.save" />
	<input type="hidden" name="return" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
	<div class="buttons">
		<button type="button" onclick="Joomla.submitbutton('rankedit.save')">
		<?php echo JText::_('JSAVE') ?>
		</button>
		<button type="button" onclick="Joomla.submitbutton('rankedit.cancel')">
		<?php echo JText::_('JCANCEL') ?>
		</button>
	</div>

</form>
