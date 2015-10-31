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
// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
// Load the tooltip behavior.
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
$loggeduser = JFactory::getUser();
//$items = $this->getReports();
?>
<form action="<?php echo JRoute::_('index.php?option=com_questions&view=reports'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('COM_QUESTIONS_JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				
				
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Reference', 'a.qid', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'User ID', 'a.userid', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'Title', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'Report', 'a.qareport', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'IP', 'a.ip', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Email', 'a.email', $listDirn, $listOrder); ?>
				</th>
				
				<?php /*
                <th class="nowrap" width="10%">
					<?php echo JText::_("COM_QUESTIONS_QAID"); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JText::_("COM_QUESTIONS_USERID"); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JText::_("COM_QUESTIONS_TITLE"); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo  JText::_("COM_QUESTIONS_QAREPORT"); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JText::_("COM_QUESTIONS_USER_IP"); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JText::_("COM_QUESTIONS_USER_EMAIL"); ?>
				</th> */ ?>
			</tr>
		</thead>
		 <tfoot>
    	  	<tr>
        		<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach($this->items as $i => $item) :
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
                <td class="center">
                <a href="<?php echo JRoute::_('index.php?option=com_questions&view=question&layout=edit&id='.$item->qid); ?>"><?php echo $item->title; ?></a>
				</td>
				<td class="center">
					<?php echo $this->escape($item->userid); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->title); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->qareport); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->ip); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->email); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<div>
		<input type="hidden" name="task" value="" />
		<?php /*<input type="hidden" name="report" value="<?php echo  JFactory::getApplication()->input->get('cid',0); ?>" /> */ ?>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>


