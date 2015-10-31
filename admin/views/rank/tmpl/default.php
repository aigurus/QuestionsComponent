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
JHtml::_('behavior.tooltip');
JTable::addIncludePath(JPATH_COMPONENT.'/'.'tables');
?>
<form action="<?php echo JRoute::_('index.php?option=com_questions&view=rank'); ?>" method="post" name="adminForm">
    <table class="adminlist">
    
		<thead>
			<tr>
            	<th width="10">&nbsp;</th>
		        <th width="10"><?php echo JText::_("COM_QUESTIONS_TBL_ID")?></th>
         		<th width="10"><?php echo JText::_("COM_QUESTIONS_TBL_POINTSREQ")?></th>
        		<th width="10"><?php echo JText::_("COM_QUESTIONS_TBL_RANK")?></th>
			</tr>
		</thead>
            
        <tfoot>
    	  	<tr>
        		<td colspan="17"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
           	
        <tbody>
	       	<?php foreach($this->items as $i => $item): ?>
	        <tr class="row<?php echo $i % 2; ?>">
            		<td>
	                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                </td>
	                <td>
	                        <?php	echo $item->id; ?>
	                </td>
	                <td>
	                	<a href="<?php echo JRoute::_('index.php?option=com_questions&task=rankedit.edit&id=' . $item->id); ?>"><?php echo $item->pointsreq; ?></a>
	                </td>
                    <td>
	                	<a href="<?php echo JRoute::_('index.php?option=com_questions&task=rankedit.edit&id=' . $item->id); ?>"><?php echo $item->rank; ?></a>
	                </td>
              </tr>
			<?php endforeach; ?>
        
        </tbody>
        
	</table>
    
    <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo JRequest::getInt('id',0); ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>
