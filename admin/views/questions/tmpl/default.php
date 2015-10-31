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
 
// load tooltip behavior
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

if(version_compare(JVERSION,'3.0.0','ge')) {
     JHtml::_('bootstrap.tooltip');
	 JHtml::_('dropdown.init');
	 JHtml::_('formbehavior.chosen', 'select');
};
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
?>

<form action="<?php echo JRoute::_('index.php?option=com_questions'); ?>" method="post" name="adminForm" id="adminForm">

<table class="adminform">
		<tr>
			<td width="100%">
				<?php
				echo JText::_( 'COM_QUESTIONS_SEARCH' );
				echo $this->lists['filter'];
				?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button class="btn" onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button class="btn" onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php //echo $this->lists['state'];	?>
			</td>
		</tr>
	</table>

    <table class="adminlist" cellspacing="1">
    
		<thead>
			<tr>
                
                <th><?php echo JHTML::_('grid.sort',"ID", 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
        		<th width="10"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>                     
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_TITLE", 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_PUBLISHED", 'a.published', $this->lists['order_Dir'], $this->lists['order'] )?></th>
        		<?php if ($this->viewAnswers):?>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_PARENT", 'a.parent', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		
        		<?php else: ?>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_ANSWER", 'a.chosen', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_TAGS", 'a.qtags', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_CATEGORY", 'a.catid', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_ANSWERS", 'answerscount', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<?php endif; ?>
        		
        		
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_VOTES", 'votes', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_SCORE", 'score', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_SUBMITTED", 'a.submitted', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_MODIFIED", 'a.modified', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_SUBMITTED_BY", 'a.userid_creator', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_MODIFIED_BY", 'a.userid_modifier', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<?php if (!$this->viewAnswers):?>
        		<th><?php echo JHTML::_('grid.sort',"COM_QUESTIONS_TBL_IMPRESSIONS", 'a.impressions', $this->lists['order_Dir'], $this->lists['order'])?></th>
        		<?php endif; ?>
        		<th>IP</th>
			</tr>
		</thead>
            
        <tfoot>
    	  	<tr>
        		<td colspan="17"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
           	
        <tbody>
        <?php $k = 0;
			for($i=0, $n=count( $this->rows ); $i < $n; $i++) {
				$item = $this->rows[$i]; ?>
	       	<?php //foreach($this->items as $i => $item): ?>
	        <tr class="row<?php echo $n % 2; ?>">
	                <td>
                    <?php echo $this->pageNav->getRowOffset( $item->id ); ?>
	                        <?php //echo $item->id; ?>
	                </td>
	                <td >
	                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                </td>
	                <td>
	                	<a href="<?php echo JRoute::_('index.php?option=com_questions&task=question.edit&id=' . $item->id . '&answers=' . JRequest::getInt("answers", 0)); ?>"><?php echo $item->title; ?></a>
	                </td>
	                <td class="center">
	                	<?php echo JHtml::_('jgrid.published', $item->published, $i , "questions."); ?>
	                </td>
	                <?php if ($this->viewAnswers):?>
	                <td>
	                	<?php 
	                		if ($item->parentData){
	                			echo "<a href='" . JRoute::_('index.php?option=com_questions&task=question.edit&id=' . $item->parentData->id) ."'>";
	                			echo $item->parentData->title;
	                			echo "</a>";
	                		}
	                		else {
	                			echo "N/A";
	                		}
	                	?>
	                </td>
	                <?php else: ?>
	                <td>
	                	<?php if ($item->question):?>
	                		<a href="<?php echo JRoute::_('index.php?option=com_questions&task=question.edit&question=0&parent=' . $item->id . '&catid=' . $item->catid ); ?>">Answer</a>
	                	<?php else: ?>
	                		N/A
	                	<?php endif; ?>
	                </td>
	                <td class="center">
	                	<?php 
	                	if ($item->qtags)
			      			echo $this->cleanString($item->qtags); ?>
	                </td>
	                <td class="center">
	                	<?php echo ($item->CategoryName); ?>
	                </td>
	                <td>
	                	<?php echo $item->answerscount; ?>
	                </td>
	                <?php endif; ?>
	                
	                
	                <td>
	                	<?php echo $item->votes; ?>
	                </td>
	                <td>
	                	<?php echo $item->score; ?>
	                </td>
	                <td class="center">
	                	<?php echo $item->submitted; ?>
	                </td>
	                <td class="center">
	                	<?php echo $item->modified; ?>
	                </td>
	                <td class="center">
	                	<?php echo JFactory::getUser($item->userid_creator)->name ? JFactory::getUser($item->userid_creator)->name : $item->name; ?>
	                </td>
	                <td class="center">
	                	<?php 
	                	if( $item->userid_modifier){
	                		echo JFactory::getUser($item->userid_modifier)->name;
	                	}
	                	?>
	                </td>
	                <?php if (!$this->viewAnswers):?>
	                <td class="center">
	                	<?php echo $item->impressions; ?>
	                </td>
	                <?php endif; ?>
	                <td class="center">
	                	<?php echo $item->ip; ?>
	                </td>
	        </tr>
			<?php //endforeach; ?>
        	<?php $k = 1 - $k;  } ?>
        </tbody>
        
	</table>
    
    <div>
		<input type="hidden" name="task" value="" />
        <input type="hidden" name="option" value="com_questions" />
        <input type="hidden" name="view" value="questions" />
        <input type="hidden" name="controller" value="questions" />
		<input type="hidden" name="answers" value="<?php echo JRequest::getInt('answers',0); ?>" />
		<input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>
