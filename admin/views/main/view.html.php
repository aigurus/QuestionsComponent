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

jimport( 'joomla.application.component.view' );

/**
 * Configuration view for Jom Social
 */
class QuestionsViewMain extends QueView
{
	
	protected $totalUsers;
	protected $totalQuestions;
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/
	function display( $tpl = null )
	{
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');
		//jimport('joomla.html.pane');
		//$pane	=& JPaneLegacy::getInstance('sliders');
		jimport('joomla.html.html.sliders');
		//$model = $this->getModel();
		// Assign data to the view
		$totalUsers = $this->get('TotalUsers');
		$totalQuestions = $this->get('TotalQuestions');
		/*
		var_dump($totalUsers);
		var_dump($totalQuestions);
		exit;*/
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$this->totalUsers = $totalUsers;
		$this->totalQuestions = $totalQuestions;

		$this->assignRef( 'groups'		, $groups );
		$this->assignRef( 'configuration'	, $configuration );
		//$this->assignRef( 'pane'		, $pane );
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Private method to set the toolbar for this view
	 * 
	 * @access private
	 * 
	 * @return null
	 **/
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_QUESTIONS_Configuration'), 'user');
		 QuestionsHelper::canDo("core.admin") ? JToolBarHelper::preferences("com_questions") : NULL;
		 $helptool= JToolBar::getInstance( 'toolbar' );
         $helptool->appendButton( JText::_('COM_QUESTIONS_HELP'), 'help', 'JTOOLBAR_HELP', 'http://phpseo.net/blogs/144-questions-component-manual.html', 640, 480 );
		//JToolBarHelper::addNew("profiles.EditRank", JText::_('COM_QUESTIONS_EDITRANK'));	
		//JToolBarHelper::addNew("profiles.EditPoints", JText::_('COM_QUESTIONS_EDITPOINTS'));
	}
	
	public function addIcon( $image , $url , $text , $newWindow = false )
	{
		$lang		= JFactory::getLanguage();
		$newWindow	= ( $newWindow ) ? ' target="_blank"' : '';
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $url; ?>"<?php echo $newWindow; ?>>
					<?php echo JHTML::_('image', 'administrator/components/com_questions/assets/icons/' . $image , NULL, NULL ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}
	
}