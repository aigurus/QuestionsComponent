<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Configuration view for Jom Social
 */
class QuestionsViewBugreports extends QueView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		$form = $this->get("Form");
		$this->form = $form;
		$this->addToolBar();
		parent::display( $tpl );
	}

	/**
	 * Private method to set the toolbar for this view
	 * 
	 * @access private
	 * 
	 * @return null
	 **/	 	 
	protected function addToolBar() 
        {
        	$user= JFactory::getUser();
        	
            JToolBarHelper::title(JText::_('COM_QUESTIONS_REPORTS'));

			QuestionsHelper::canDo("core.admin") ? JToolBarHelper::save("question.bugreport", JText::_('COM_QUESTIONS_BUGSUBMIT')) : NULL;
			QuestionsHelper::canDo("core.admin") ? JToolBarHelper::cancel( "question.cancel" , "JTOOLBAR_CANCEL") : NULL;
        }
	function get_var($var)
    {
        if (isset($this->tpl_vars[$var])) {
            return $this->tpl_vars[$var];
        } else {
            return null;
        }
    }
	
}