<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('forwards.ordering'));
$listDirn	= $this->escape($this->state->get('forwards.direction'));
$saveOrder	= $listOrder == 'a.ordering';
$stateOptions = ProfilesHelper::getStateOptions();
?>

<form action="<?php echo JRoute::_('index.php?option=com_questions&view=forwards'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-select fltrt">
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_QUESTIONS_Select state');?></option>
				<?php echo JHtml::_('select.options', $stateOptions, 'value', 'text', $this->state->get('filter.state'));?>
			</select>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('COM_QUESTIONS_JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JText::_('COM_QUESTIONS_EMAIL'); ?>
				</th>
				<th class="title">
					<?php echo JText::_('COM_QUESTIONS_JOOMLA_FORWARD'); ?>
				</th>
				<th class="title">
					<?php echo JText::_('COM_QUESTIONS_DIRECTADMIN_FORWARD'); ?>
				</th>
				<th class="title">
					<?php echo JText::_('COM_QUESTIONS_STATUS'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->email); ?>
				</td>
				<td>
					<?php echo $item->email; ?>
				</td>
				<td>
					<?php echo str_replace(',','<br/>', $item->joomlaforwardaddress); ?>
				</td>
				<td>
					<?php echo str_replace(',','<br/>', $item->directadminforwardaddress); ?>
				</td>
				<td>
					<?php echo str_replace(',','<br/>', $item->state); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>