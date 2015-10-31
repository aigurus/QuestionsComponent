<?php defined('_JEXEC') or die; 
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


?>
<form action="<?php echo JRoute::_('index.php?option=com_questions&task=import_questions'); ?>" method="post"
	enctype="multipart/form-data">
	<div class="width-50 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_QUESTIONS_IMPORT_DATA'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label for="data_file"><?php echo JText::_('COM_QUESTIONS_IMPORT_DATA_FILE_LABEL'); ?></label>
				<input type="file" name="data_file" id="data_file" />
			</li>
			<li style="clear: both;">
				<?php echo JText::_('COM_QUESTIONS_or'); ?>
			</li>
			<li>
				<label for="data_path"><?php echo JText::_('COM_QUESTIONS_IMPORT_DATA_PATH_LABEL'); ?></label>
				<input type="text" name="data_path" id="data_path" size="70" />
			</li>
			<li style="clear: both;">
				<input class="button" type="submit" value="<?php echo JText::_('COM_QUESTIONS_IMPORT_BUTTON_IMPORT'); ?>" />
			</li>
		</ul>
	</fieldset>
	</div>
	<?php echo JHtml::_('form.token'); ?>
</form>

<form action="<?php echo JRoute::_('index.php?option=com_questions&task=import_config'); ?>" method="post"
	enctype="multipart/form-data">
	<div class="width-50 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_QUESTIONS_IMPORT_CONFIG'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label for="config_file"><?php echo JText::_('COM_QUESTIONS_IMPORT_CONFIG_FILE_LABEL'); ?></label>
				<input type="file" name="config_file" id="config_file" />
			</li>
			<li>
				<label><?php echo JText::_('COM_QUESTIONS_IMPORT_OPTION_MODULES'); ?></label>
				<input type="checkbox" name="options[modules]" value="1" checked /> 
			</li>
			<li>
				<label><?php echo JText::_('COM_QUESTIONS_IMPORT_OPTION_MENUS'); ?></label>
				<input type="checkbox" name="options[menus]" value="1" checked /> 
			</li>
			<li>
				<label><?php echo JText::_('COM_QUESTIONS_IMPORT_OPTION_CONFIG'); ?></label>
				<input type="checkbox" name="options[config]" value="1" checked /> 
			</li>			
			<li style="clear: both;">
				<input class="button" type="submit" value="<?php echo JText::_('COM_QUESTIONS_IMPORT_BUTTON_IMPORT'); ?>" />
			</li>
		</ul>
	</fieldset>
	</div>
	<?php echo JHtml::_('form.token'); ?>
</form>



