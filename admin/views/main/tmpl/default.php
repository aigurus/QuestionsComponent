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

// No direct access
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addScript(JUri::BASE().'components/com_questions/assets/js/jquery.js');
$document->addScript(JUri::BASE().'components/com_questions/assets/js/jqueryui.js');
$document->addStyleSheet(JUri::BASE().'components/com_questions/assets/css/jqueryui.css');
//echo JPATH_COMPONENT;
$document = JFactory::getDocument();
	$document->addScriptDeclaration('
		 	$(function() {
				$( "#accordion" ).accordion();
			});
');
?>
<table width="100%" border="0">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
				<?php echo $this->addIcon('profiles.gif','index.php?option=com_questions&view=profiles', JText::_('COM_QUESTIONS_PROFILES'));?>
				<?php echo $this->addIcon('rank.gif','index.php?option=com_questions&view=rank', JText::_('COM_QUESTIONS_RANK'));?>
				<?php echo $this->addIcon('categories.gif','index.php?option=com_categories&view=categories&extension=com_questions', JText::_('COM_QUESTIONS_CATEGORIES'));?>
				<?php echo $this->addIcon('questions.gif','index.php?option=com_questions&view=questions', JText::_('COM_QUESTIONS_QUESTIONS'));?>
				<?php echo $this->addIcon('answers.png','index.php?option=com_questions&view=questions&answers=1', JText::_('COM_QUESTIONS_ANSWERS'));?>
				<?php //echo $this->addIcon('settings.gif','index.php?option=com_questions&view=import', JText::_('COM_QUESTIONS_IMPORT'));?>
				<?php //echo $this->addIcon('settings.gif','index.php?option=com_questions&view=export', JText::_('COM_QUESTIONS_EXPORT'));?>
                <?php echo $this->addIcon('reports.gif','index.php?option=com_questions&view=reports', JText::_('COM_QUESTIONS_USERREPORTS'));?>
                <?php echo $this->addIcon('help.gif','index.php?option=com_questions&view=help', JText::_('COM_QUESTIONS_HELP'));?>
                <?php echo $this->addIcon('about.gif','index.php?option=com_questions&view=about', JText::_('COM_QUESTIONS_ABOUT'));?>
                <?php echo $this->addIcon('bugreports.png','index.php?option=com_questions&view=bugreports', JText::_('COM_QUESTIONS_BUGREPORTS'));?>
				<?php //echo $this->addIcon('bugreports.png','index.php?option=com_questions&task=access.default_access', JText::_('COM_QUESTIONS_DEFAULT_ACCESS'));?>
			</div>
		</td>
		<td width="45%" valign="top">
			<?php
				echo JText::_('COM_QUESTIONS_WELCOME_TO_QUESTIONS');
			?>
			<div id="accordion">
						<h3>
							<?php echo JText::_('COM_QUESTIONS_COMPONENT_SUPPORT');?>
						</h3>
                        <div>
                            <p>
                                If you require professional support just head on to the forums at 
                                <a href="https://extensiondeveloper.com/index.php?option=com_questions&view=questions&catid=0&filter=0&Itemid=153" target="_blank">
                                	Extensiondeveloper Forum
                                </a>
                                For developers, you can browse through the documentations at 
                                <a href="https://extensiondeveloper.com" target="_blank">https://extensiondeveloper.com</a>
                            </p>
                            <p>
                                If you found any bugs, give us a report in Bug report section
                            </p>
                        </div>
                        <h3>
						<?php
                            echo JText::_('COM_QUESTIONS_STATISTICS');
                        ?>
                        </h3>
						<div>
                        <p style="float:left";>
							<?php echo JText::_('COM_QUESTIONS_TOTAL_USERS' ).': '; ?>
						</p>
						<p style="float:right";>
							<strong><?php echo $this->totalUsers; ?></strong>
						</p>
                        <br /><br />
						<p style="float:left";>
						<?php echo JText::_('COM_QUESTIONS_TOTAL_ASKED_QUESTIONS' ).': '; ?>
                        </p>
						<p style="float:right";>
							<strong><?php echo $this->totalQuestions; ?></strong>
                        </p>
                        </div>
                        <h3>
			
			<?php
				echo JText::_('COM_QUESTIONS_LATEST_UPDATES');
			?>
            			</h3>
         </td>
	</tr>
</table>
<style>
div#cpanel{margin-top: 10px};
div#cpanel div div.icon a{width: 107px;}
</style>
