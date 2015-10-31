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

// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php global $_CONFIG ?>
<?php
$user = JFactory::getUser();
$name = $user->get('username');
$email = $user->get('email');
?>

<H2>Bug Reports</H2>
<div id="bug_report_form" style="margin:10px 5px;">
<?php if(!empty($note)): ?>
    <div style="background-color: #FFFFCC; border: 1px solid #FDE2AC; padding: 8px; color:#FF6633; margin: 5px; ">
        <?php echo $note;  ?>
    </div>
<?php endif; ?>

<div style=" background-color: #FFFFCC; border: 1px solid #FDE2AC; padding: 8px; color:#FF6633;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTINSTRUCTION'); ?></div>

<form action="<?php echo JRoute::_('index.php?option=com_questions&view=bugreports'); ?>" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform">
<div style="margin-top: 10px;">
	<div class="adminTable">
    	<div class="adminRow">
        	<div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTYOURNAME') ; ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<input type="text" name="username" id="username" style="width: 95%;" value="<?php echo $name ?>" />
            </div>
            <div class="clearFloat"></div>
            <div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTYOURCOMPANYNAME'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<input type="text" name="companyname" id="companyname" style="width: 95%;" value="" />
            </div>
            <div class="clearFloat"></div>
            <div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTCONTACTNUMBER'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<input type="text" name="phonenumber" id="phonenumber" style="width: 95%;" value="" />
            </div>
            <div class="clearFloat"></div>        
            <div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTEMAILADD') ; ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<input type="text" name="emailaddress" id="emailaddress" style="width: 95%;" value="<?php echo $email ;?>" />
            </div>
            <div class="clearFloat"></div>  
            <div class="adminRowColumn" style="width: 20%;"><b><?php echo JText::_('COM_QUESTIONS_BUGREPORTTITLE') ; ?></b><em>*</em></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<input type="text" name="subject" id="subject" style="width: 95%;" value="" />
            </div>
            <div class="clearFloat"></div>
            
        </div>
        <div class="adminRow">
        	<div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTSUMMARY'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<i><?php echo JText::_('COM_QUESTIONS_BUGREPORTSUMMARYTIP'); ?></i>
            </div>
            <div class="clearFloat"></div>
        
            <div class="adminRowColumn" style="width: 20%;"></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<textarea name="bug_summary" id="bug_summary" style="width: 95%" rows="5"></textarea>
            </div>
            <div class="clearFloat"></div>
        </div>
         <div class="adminRow">
        	<div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGREPORTREPRODUCE'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<i><?php echo JText::_('COM_QUESTIONS_BUGREPORTREPRODUCETIP'); ?></i>
            </div>
            <div class="clearFloat"></div>
        
            <div class="adminRowColumn" style="width: 20%;"></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<textarea name="bug_repoducibility" id="bug_reproducibility" style="width: 95%" rows="5"></textarea>
            </div>
            <div class="clearFloat"></div>
        </div>
        <div class="adminRow">
        	<div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGRESULTS'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<i><?php echo JText::_('COM_QUESTIONS_BUGRESULTSTIP'); ?></i>
            </div>
            <div class="clearFloat"></div>
        
            <div class="adminRowColumn" style="width: 20%;"></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<textarea name="bug_outcome" id="bug_outcome" style="width: 95%" rows="5"></textarea>
            </div>
            <div class="clearFloat"></div>
        </div>
        <div class="adminRow">
        	<div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGSUGGESTIONS'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<i><?php echo JText::_('COM_QUESTIONS_BUGSUGGESTIONSTIP'); ?></i>
            </div>
            <div class="clearFloat"></div>
        
            <div class="adminRowColumn" style="width: 20%;"></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<textarea name="bug_solution_suggest" id="bug_solution_suggest" style="width: 95%" rows="5"></textarea>
            </div>
            <div class="clearFloat"></div>
        </div>
         <div class="adminRow">
        	<div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGPRIORITY'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<select name="bug_priority">
                	<option value="Crucial"><?php echo JText::_('COM_QUESTIONS_BUGPRIORITYCRUCIAL'); ?></option>
                    <option value="Important"><?php echo JText::_('COM_QUESTIONS_BUGPRIORITYIMPORTANT'); ?></option>
                    <option value="Annoying"><?php echo JText::_('COM_QUESTIONS_BUGPRIORITYANNOYING'); ?></option>
                    <option value="Unimportant"><?php echo JText::_('COM_QUESTIONS_BUGPRIORITYUNIMPORTANT'); ?></option> 
              </select>
            </div>
            <div class="clearFloat"></div>
            <div class="adminRowColumn" style="width: 20%;"><?php echo JText::_('COM_QUESTIONS_BUGCLASS'); ?></div>
            <div class="adminRowColumn" style="width: 75%;">
            	<select name="bug_type">
                	<option selected="selected" value="(1) Security"><?php echo JText::_('COM_QUESTIONS_BUGCLASSSECURITY'); ?></option>
                    <option value="(2) Crash/Hang/Data Loss"><?php echo JText::_('COM_QUESTIONS_BUGCLASSCRASH'); ?></option>
                    <option value="(3) Performance"><?php echo JText::_('COM_QUESTIONS_BUGCLASSPERFORMANCE') ; ?></option>
                    <option value="(4) UI/Usability"><?php echo JText::_('COM_QUESTIONS_BUGCLASSUSABILITY'); ?></option>
                    <option value="(5) Serious Bug"><?php echo JText::_('COM_QUESTIONS_BUGCLASSSERIOUS');?></option>
                    <option value="(6) Other Bug/Has Workaround"><?php echo JText::_('COM_QUESTIONS_BUGCLASSOTHER'); ?></option>
                    <option value="(7) Feature (New)"><?php echo JText::_('COM_QUESTIONS_BUGCLASSFEATURE'); ?></option>
                    <option value="(8) Enhancement"><?php echo JText::_('COM_QUESTIONS_BUGCLASSENHANCEMENT'); ?></option>

              </select>
            </div>
            <div class="clearFloat"></div>
        </div>
         </fieldset>
     	<div>
                <input type="hidden" name="task" value="question.bugreport" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>

</div>
</div>