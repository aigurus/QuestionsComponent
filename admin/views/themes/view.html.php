<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_questions
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.0 2013-06-02
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();


// Access check.
if (JFactory::getUser()->authorise('core.questions', 'com_questions')) {
	JToolBarHelper::preferences('com_questions');
}

/**
 * View class Admin - Theme Manager - iCagenda
 */
class QuestionsViewthemes extends QueView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			if(version_compare(JVERSION, '3.0', 'ge')) {
				$this->sidebar = JHtmlSidebar::render();
			}
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'questions.php';

		$state	= $this->get('State');

		// Set Title
		if(version_compare(JVERSION, '3.0', 'lt')) {
			JToolBarHelper::title('iCagenda - ' . JText::_('COM_ICAGENDA_THEME_MANAGER'), 'themes.png');
		} else {
			JToolBarHelper::title('&lt;div style="float:right"&gt; &lt;img src="../media/com_questions/images/iconquestions36.png" alt="logo" /&gt; &lt;/div&gt; iCagenda &lt;img src="../media/com_questions/images/themes-16.png" /&gt; ' . JText::_('COM_ICAGENDA_THEME_MANAGER'), 'themes-48.png');
		}

		$icTitle = JText::_('COM_ICAGENDA_THEME_MANAGER');

		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$sitename = $app->getCfg('sitename');
		$title = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;
		$document->setTitle($title);
	}
}
