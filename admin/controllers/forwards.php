<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controlleradmin' );

class QuestionsControllerForwards extends QueController
{
	protected $option = 'com_questions';
	protected $view = 'forwards';
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// Define standard task mappings.
		$this->registerTask('delete', 'deleteForwardFromDirectAdmin');
		$this->registerTask('update', 'addOrUpdateDirectAdmin');
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Forwards', $prefix = 'QuestionsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function deleteForwardFromDirectAdmin()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid	=  JFactory::getApplication()->input->get('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_('COM_QUESTIONS_NO_ITEM_SELECTED'));
		} else {
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if ($model->delete($cid)) {
				$this->setMessage(JText::plural('N_ITEMS_DELETED', count($cid)));
			} else {
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view, false));
	}
	
	public function addOrUpdateDirectAdmin()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid	=  JFactory::getApplication()->input->get('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_('COM_QUESTIONS_NO_ITEM_SELECTED'));
		} else {
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if ($model->addOrUpdate($cid)) {
				$this->setMessage(JText::plural('N_ITEMS_ADDED_OR_UPDATED', count($cid)));
			} else {
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view, false));
	}
}
?>