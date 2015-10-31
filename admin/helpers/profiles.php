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
defined('_JEXEC') or die;

class ProfilesHelper
{
	public static $extension = 'com_questions';
	
	static function getStateOptions()
	{
		$options = array();
		$options[]	= JHtml::_('select.option', '', 'All');
		$options[]	= JHtml::_('select.option', 'OK', 'Ok');
		$options[]	= JHtml::_('select.option', 'Mismatch', 'Mismatch');
		$options[]	= JHtml::_('select.option', 'NotListedInDirectAdmin', 'Not listed in DirectAdmin');
		$options[]	= JHtml::_('select.option', 'NotListedInJoomla', 'Not listed in Joomla');
		return $options;
	}
	
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_QUESTIONS_Profiles'),
			'index.php?option=com_questions&view=profiles',
			$vName == 'profiles'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_QUESTIONS_Email Forwards'),
			'index.php?option=com_questions&view=forwards',
			$vName == 'forwards'
		);
	}
}
