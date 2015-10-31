<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class QuestionsControllerProfiles extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	protected $item;
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('EditRank',	'EditRank');
		$this->registerTask('EditPoints',	'EditPoints');
		$this->registerTask('BlockUser',	'BlockUser');
		$this->registerTask('UnblockUser',	'UnblockUser');
	}
	/**
	 * Proxy for getModel.
	 *
	 * @since	1.6
	 */
	public function getModel($name = 'Profiles', $prefix = 'QuestionsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function EditRank()
	{
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
		$this->ChangeRank();
	}

	public function EditPoints()
	{
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
		$this->ChangePoints();
	}
	public function BlockUser()
	{
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
		$this->EditUser();
	}
	public function UnblockUser()
	{
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
		$this->UnEditUser();
	}
	private function ChangeRank()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
		$cids = implode(",",  JFactory::getApplication()->input->get("cid"));
		$db 			=& JFactory::getDBO();
		$acl			=& JFactory::getACL();
		$currentUser 	=& JFactory::getUser();
		$cid 	=  JFactory::getApplication()->input->get( 'cid', array(), '', 'array' );
		$number =0;
		JArrayHelper::toInteger( $cid );
		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::_( 'SELECTUSERS', true ) );
		}
		foreach ($cid as $id){
		$sql = "UPDATE #__questions_userprofile"
    	. "\n SET rank = 'Beginner'"
    	. "\n WHERE id =". $id
    	;
    	$db->setQuery( $sql );
    	if (!$db->query()) {	  
    		die("SQL error" . $db->stderr(true));
    	}
		$number++;
		}
    	//return true;
		$this->setRedirect( 'index.php?option=com_questions&view=profiles', JText::sprintf(JText::_('COM_QUESTIONS_RANKCHANGED'), $number));

	}

	private function ChangePoints( ) 
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
	    jimport('joomla.user.helper');
	    jimport('joomla.database.table');
		$points=0;
		
		$db 			=& JFactory::getDBO();
		$acl			=& JFactory::getACL();
		$currentUser 	=& JFactory::getUser();

		$cid 	=  JFactory::getApplication()->input->get( 'cid', array(), '', 'array' );
		$number=0;
		JArrayHelper::toInteger( $cid );
		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::_( 'SELECTUSERS', true ) );
		}
		foreach ($cid as $id){
		$sql = "UPDATE #__questions_userprofile"
    	. "\n SET points = $points"
    	. "\n WHERE id =". $id
    	;
    	$db->setQuery( $sql );
    	if (!$db->query()) {	  
    		die("SQL error" . $db->stderr(true));
    	}
		$number++;
		}
    	//return true;
		$this->setRedirect( 'index.php?option=com_questions&view=profiles', JText::sprintf(JText::_('COM_QUESTIONS_POINTSCHANGED'), $number));
    }
	private function EditUser( ) 
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
	    jimport('joomla.user.helper');
	    jimport('joomla.database.table');
				
		$db 			=& JFactory::getDBO();
		$acl			=& JFactory::getACL();
		$currentUser 	=& JFactory::getUser();

		$cid 	=  JFactory::getApplication()->input->get( 'cid', array(), '', 'array' );
		$number=0;
		JArrayHelper::toInteger( $cid );
		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::_( 'SELECTUSERS', true ) );
		}
		foreach ($cid as $id){
		$sql = "UPDATE #__questions_userprofile"
    	. "\n SET blocked = 1"
    	. "\n WHERE id =". $id
    	;
    	$db->setQuery( $sql );
    	if (!$db->query()) {	  
    		die("SQL error" . $db->stderr(true));
    	}
		$number++;
		}
    	//return true;
		$this->setRedirect( 'index.php?option=com_questions&view=profiles', JText::sprintf(JText::_('COM_QUESTIONS_USERBLOCKED'), $number));
    }
		private function UnEditUser( ) 
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
	    jimport('joomla.user.helper');
	    jimport('joomla.database.table');
				
		$db 			=& JFactory::getDBO();
		$acl			=& JFactory::getACL();
		$currentUser 	=& JFactory::getUser();

		$cid 	=  JFactory::getApplication()->input->get( 'cid', array(), '', 'array' );
		$number=0;
		JArrayHelper::toInteger( $cid );
		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::_( 'SELECTUSERS', true ) );
		}
		foreach ($cid as $id){
		$sql = "UPDATE #__questions_userprofile"
    	. "\n SET blocked = 0"
    	. "\n WHERE id =". $id
    	;
    	$db->setQuery( $sql );
    	if (!$db->query()) {	  
    		die("SQL error" . $db->stderr(true));
    	}
		$number++;
		}
    	//return true;
		$this->setRedirect( 'index.php?option=com_questions&view=profiles', JText::sprintf(JText::_('COM_QUESTIONS_UNUSERBLOCKED'), $number));
    }
}
