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
$user = JFactory::getUser();
JHtml::_('script', 'system/core.js', false, true);

JHtml::_('behavior.formvalidator');
?>
<form action="<?php echo JRoute::_('index.php?option=com_questions&view=edituser'); ?>"
    method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('description');  ?>
	
	<?php echo $this->form->renderField('email');  ?>
	
	<?php echo $this->form->renderField('url1');  ?>
	
	<?php echo $this->form->renderField('url2');  ?>
	
	<?php echo $this->form->renderField('url3');  ?>
	
	<?php echo $this->form->renderField('location');  ?>
	
	<?php echo $this->form->renderField('company');  ?>
	
	<?php echo $this->form->renderField('position');  ?>
	
	<?php echo $this->form->renderField('workno');  ?>
	
	<?php echo $this->form->renderField('mobno');  ?>
	
	<?php echo $this->form->renderField('workaddress');  ?>

	<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('edituser.save')">Submit</button>
	<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('edituser.cancel')">Cancel</button>
	<input type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" name="ip" />
	<input type="hidden" name="task" />
	<input type="hidden" name="userid" value="<?php 
                $jinput = JFactory::getApplication()->input;
				$uid = $jinput->getInt('id');
				echo $uid; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
	CopyrightHelper::copyright();
?>