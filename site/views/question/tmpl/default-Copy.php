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
	Email: admin@extensiondeveloper.com
	support: support@extensiondeveloper.com
	Website: http://www.extensiondeveloper.com
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
//JHTML::_('behavior.tooltip');
jimport( 'joomla.application.component.view' );
//include helper functions
require_once ("administrator/components/com_questions/helpers/questions.php");
$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_questions/media/simple-question.css");
//require_once 'components/com_questions/helpers/cat.php';
require_once 'components/com_questions/media/style.php';

$app = JFactory::getApplication();
$parameters = $app->getParams();
$params = $app->getParams();

$articleid = $parameters->get('helparticleid', 1);
$appParams = json_decode(JFactory::getApplication()->getParams());
$chosenanswer=$this->getChosen($this->question->id);

$document =JFactory::getDocument();
$document->setTitle($this->question->title);
$document->setDescription(strip_tags($this->question->text));
if(is_array($this->question->qtags)){
$keytags = implode(",", $this->question->qtags);
}
//var_dump($this->question); exit;
$document->setMetaData('keywords', @$keytags);
?>

<script type="text/javascript">

jQuery(document).ready(function($){

$('.addfav').click(function()  {
	var qid = this.id;
	var idvalue = qid.split('-');
	var id = idvalue[idvalue.length - 1];
	var userid = <?php echo JFactory::getUser()->id; ?>; 
    req = $.ajax({
        type: "POST",
        url: "index.php?option=com_questions&task=question.addFavourite&vardata=quesfav&userid="+userid+"&addfav="+id,
		cache: false,
        success: function(){
			var oldSrc = 'components/com_questions/media/add.png';
			var newSrc = 'components/com_questions/media/rem.png';
			$('img[src="' + oldSrc + '"]').attr('src', newSrc);
			location.reload(); 
            //$('#addfav').html(data);
        }
    });
})
$('.delfav').click(function() {
	var qid = this.id;
	var idvalue = qid.split('-');
	var id = idvalue[idvalue.length - 1];
	var userid = <?php echo JFactory::getUser()->id; ?>; 
    req = $.ajax({
        type: "POST",
		cache: false,
        url: "index.php?option=com_questions&task=question.delFavourite&vardata=quesfav&userid="+userid+"&delfav="+id,
        success: function(){
            var oldSrc = 'components/com_questions/media/rem.png';
			var newSrc = 'components/com_questions/media/add.png';
			$('img[src="' + oldSrc + '"]').attr('src', newSrc);
			location.reload(); 
        }
    });
})
$('#positive').click(function() {
	var newurl = "index.php?option=com_questions&task=question.votepositive&id="+<?php echo $this->question->id ?>;
	console.log(newurl);
    req = $.ajax({
        type: "POST",
        url: newurl,
		cache: false,
        success: function(){
			location.reload(); 
            //$('#addfav').html(data);
        }
    });
})
$('#negative').click(function() {
	var newurl = "index.php?option=com_questions&task=question.votenegative&id="+<?php echo $this->question->id ?>;
	console.log(newurl);
    req = $.ajax({
        type: "POST",
		cache: false,
        url: newurl,
        success: function(){
			location.reload(); 
        }
    });
})

$('.anspositive').click(function() {
	var ansid = this.id;
	var idvalue = ansid.split('-');
	var id = idvalue[idvalue.length - 1];
	var newurl = "index.php?option=com_questions&task=question.votepositive&id="+id;
    req = $.ajax({
        type: "POST",
        url: newurl,
		cache: false,
        success: function(){
			location.reload(); 
            //$('#addfav').html(data);
        }
    });
})
$('.ansnegative').click(function() {
	var ansid = this.id;
	var idvalue = ansid.split('-');
	var id = idvalue[idvalue.length - 1];
	var newurl = "index.php?option=com_questions&task=question.votenegative&id="+id;
	console.log(newurl);
    req = $.ajax({
        type: "POST",
		cache: false,
        url: newurl,
        success: function(){
			location.reload(); 
        }
    });
})
})
</script>

<?php

if ($this->escape($this->params->get('show_category_list', 1))) : ?>
<div class="questionbox">
<div style="width:250px;float:right;">
<form action="<?php echo JRoute::_('index.php');?>" method="post">
<?php
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
$mitemid = @$set_Itemid > 0 ? $set_Itemid : JRequest::getInt('Itemid');
$width			= 30;
$maxlength		= $upper_limit;
$text			= htmlspecialchars($parameters->get('text', JText::_('COM_QUESTIONS_SEARCH')));

			$output = '<input name="searchword" maxlength="'.$maxlength.'"  type="text" size="'.$width.'" value="'.$text.'"  onblur="if (this.value==\'\') this.value=\''.$text.'\';" onfocus="if (this.value==\''.$text.'\') this.value=\'\';" />';
			echo $output;
		?>
	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="areas" value="questions" />
	<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
</form>
</div>
<div class="questions_filters">
<?php if ($this->escape($this->params->get('display_help', 0))) { ?>
<span style="float:right"><a class="modal" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid.'&tmpl=component') ?>"rel="{handler: 'iframe', size: {x: 640, y: 480}}"><img src="components/com_questions/media/help.png" alt="Help"></a></span>
<?php } ?>
<ul><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions"); ?>">Home</a></li><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=form&layout=edit"); ?>"><?php echo JText::_("COM_QUESTIONS_ASK_A_QUESTION"); ?></a></li>
	</ul>
	</div>
   <?php endif; ?>
   
<?php if ($this->escape($this->params->get('show_category_list', 1))) :
//CatHelper::getcat();
endif; 
?>
</div>
<?php if ($this->escape($this->params->get('show_page_heading', 1))) : ?>

	<?php echo $this->escape($this->params->get('page_heading')); ?>

<?php endif; ?>
<div class="questionbox">
<div class="questions<?php echo $this->pageclass_sfx; ?>">
	<div class="votebox">
<?php if (!$this->isOwner ) :?>
		<a class="positive" id="positive" href="javascript:void(0)"><img src="components/com_questions/media/thumbs_up.png" /></a><br/>
<?php endif; ?>
		<span class="score"><?php echo $this->question->score2; ?></span><br />
<?php if (!$this->isOwner ) :?>
		<a class="negative" id="negative" href="javascript:void(0)" ><img src="components/com_questions/media/thumbs_down.png" /></a>
<?php endif; ?>
	</div>
    <div id="user_profile">
    <?php
    if (isset($appParams->display_gravatars) && $appParams->display_gravatars!=0):{
   	?>
	<div style="float:middle";>
	 <?php echo AvatarHelper::getAvatar($this->question->email,"questions_gravatar_big",64,0,$this->question->userid_creator); ?>
	</div>
    <?php } ?>
    <?php endif;?>
	<div style="clear:both"></div>
	<br />
	<div style="font-size:20px;	font-style:italic;font-family:Courier New,Courier, monospace;font:bold;text-align:center;color:blue;position: relative;text-transform:capitalize;">			
				<?php echo $this->getRank($this->question->userid_creator); ?>
                </div><br />
                <div style="float:left"; class="questions_star rank<?php echo $this->getId($this->question->userid_creator); ?>">    </div>
	</div>
	</div>
    <div>
        <div style="float:left;color:green; ">
    	<?php if ($chosenanswer==1){
		echo JText::_("COM_QUESTIONS_RESOLVED");
		}?>
        </div>
        <div style="float:left;min-width:300px;">
		<?php echo $this->question->title; ?>
        </div>
		<div style="float:right">
        <?php 
		SocialIcons::addsocial();
		?>
		</div>
		<?php if ($this->question->editable):?>
		<?php //if(JFactory::getConfig()->getValue('config.sef', false); ?>

		<a href="<?php echo JRoute::_("index.php?option=com_questions&task=question.edit&id=" . QuestionsHelper::getAlias($this->question->id)); ?>">
			<img src="media/system/images/edit.png" />
		</a>
		
		<?php endif; ?>
		
    </div>
	 <br>
	<?php echo JText::_("COM_QUESTIONS_SUBMITTED_BY"); ?> <?php /*echo ($this->question->userid_creator ? JFactory::getUser($this->question->userid_creator)->name : $this->question->name); */?>
	<strong>
	 <?php if($this->question->userid_creator>0){ ?>
    
	<a href= <?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$this->question->userid_creator . "%3A" . JFactory::getUser($this->question->userid_creator)->name) ?> ><?php echo ($this->question->userid_creator ? JFactory::getUser($this->question->userid_creator)->name : $this->question->name) ?></a>
    <?php } else { echo JText::_("COM_QUESTIONS_GUEST"); } ?> </strong>
	
	 <?php echo " On "?> <?php echo JHtml::date($this->question->submitted); ?>. 
     <br>
	 <?php echo JText::_("COM_QUESTIONS_CATEGORY"); ?>: <a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $this->getAlias($this->question->catid)); ?>"><?php echo $this->question->CategoryName; ?></a>
     <?php
		//Get group details
		if (!empty($this->question->groups)):
			echo JText::_("COM_QUESTIONS_QUESTION_GROUP").": ". $this->getGroupDetails($this->question->groups) ;
		endif;
	?>
     <div class="question_box">
        <div class="question_text">
            <?php echo $this->question->text; ?>
        </div>
		<div style="clear:both"></div>
        <span class="tags">
            <?php 
            if ($this->question->qtags):
                foreach ($this->question->qtags as $tag):
                    $tag = $this->cleanString($tag);
                    ?>
                    <span class="tagsitem">
                    <a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $tag); ?>"><?php echo $tag ?></a>
                    </span>
                <?php 
                endforeach;
            endif;
            ?>
        </span>
     </div>
	<!--<div style="float:left;">
	<?php echo JText::_("COM_QUESTIONS_QUESTION"); ?>
	</div>
	<br />-->
	
    <div style="clear:both"></div>
<?php 
		if ($this->addfavorites):
		$reguser = JFactory::getUser();
		$favarray2 = unserialize($this->getFavourite2('quesfav',$reguser->id));
		if(is_array($favarray2)){
		if(!in_array($this->question->id,$favarray2))
		{
		?>
        <a href="javascript:void(0)"  class="addfav" id="addfav-<?php echo $this->question->id; ?>">
			<img src="components/com_questions/media/add.png" />
		</a>
		<?php } else { ?>
		<a href="javascript:void(0)"  class="delfav" id="delfav-<?php echo $this->question->id; ?>">
			<img src="components/com_questions/media/rem.png" />
		</a>
		<?php }}else{ ?>
			<a href="javascript:void(0)"  class="addfav" id="addfav-<?php echo $this->question->id; ?>">
			<img src="components/com_questions/media/add.png" />
		</a>
		<?php } ?>
        <?php endif;?>
<div STYLE="border: none; float:right;">

<?php
$reguser = JFactory::getUser();   
if( !$reguser->authorise("question.answer" , "com_questions")){
?>
<?php if ($params->get('show_report', 1) && $reguser->authorise ( "report.edit" , "com_questions" )) { ?>
<a href="<?php echo JRoute::_("index.php?option=com_questions&view=reports&layout=edit&qid=".$this->question->id); ?>">
<div class="imagecss" style="float:left;">
  <img alt="<?php echo JText::_("COM_QUESTIONS_REPORTIT")?>" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" />
</div>
</a>
<?php } ?>

<?php if ($params->get('display_help', 0)) { ?>
<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid); ?>">
<div class="imagecss" style="float:left;">
  <img alt="<?php echo JText::_("COM_QUESTIONS_ANSWERTHIS")?>" src="<?php echo $this->baseurl ?>/components/com_questions/media/help.png" />
</div>
</a>
<?php } ?>
<?php } 
else { ?>
<?php $user = JFactory::getUser(); if ($params->get('show_report', 1) && $user->authorise ( "report.edit" , "com_questions" )) { ?>
<a href="<?php echo JRoute::_("index.php?option=com_questions&view=reports&layout=edit&qid=".$this->question->id); ?>">
<div class="imagecss" style="float:left;">
  <img alt="<?php echo JText::_("COM_QUESTIONS_REPORTIT")?>" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" />
</div>
</a>
<?php } 
}
?>

<div style="clear:both"></div>
	</div>
	</div>
	
    <div>
    <div>
	<div class="question_options">	

		<a href="<?php echo $this->question->link; ?>#answers"><?php echo count($this->question->answers);?></a>  <?php echo JText::_("COM_QUESTIONS_ANSWERS")?>. 
	
		<?php //if ($this->submitanswers && !$this->isOwner):?>
        <?php if ($this->submitanswers):?>
		<a href="<?php echo $this->question->link; ?>#newanswer"><?php echo JText::_("COM_QUESTIONS_ANSWER")?></a>  <?php echo JText::_("COM_QUESTIONS_THIS_QUESTION")?>! 
		<?php endif;?>
	
	</div>
	
	<?php if ($this->viewanswers):?>
		<!-- ANSWERS -->
		<a name="answers">&nbsp;</a>
		<?php foreach ($this->question->answers as $answer):?>
		<div class="answer_<?php if ($answer->chosen==1){ echo "chosen"; }?>_system_<?php echo ($answer->published ? 'published' : 'unpublished');?>">
			<div class="votebox">
			<?php $user =JFactory::getUser(); 
			      if($user->id!= $answer->userid_creator) :?>
				<a class="anspositive" id="anspositive-<?php echo $answer->id; ?>" href="javascript:void(0)" ><img src="components/com_questions/media/thumbs_up.png" /></a><br />
				<?php endif;?>
				<span class="score"><?php echo $answer->score2; ?></span><br />
			<?php	if($user->id!= $answer->userid_creator) :?>
				<a class="ansnegative" id="ansnegative-<?php echo $answer->id; ?>" href="javascript:void(0)"><img src="components/com_questions/media/thumbs_down.png" /></a>
				<?php endif;?>
			</div>
			
			
			
	<div id="user_profile">
    <?php
    if (isset($appParams->display_gravatars) && $appParams->display_gravatars!=0) :{
   	?>
	<div style="float:middle";>
	<?php echo AvatarHelper::getAvatar($answer->email,"questions_gravatar_small",34,0,$answer->userid_creator); ?>
	</div>
    <?php } ?>
    <?php endif;?>
	<div style="clear:both"></div>
	<br />
	<div style="font-size:20px;	font-style:italic;font-family:Courier New,Courier, monospace;font:bold;text-align:center;color:blue;position: relative;text-transform:capitalize;">			
				<?php echo $this->getRank($answer->userid_creator); ?>
                </div><br />
                <div style="float:left"; class="questions_star rank<?php echo $this->getId($answer->userid_creator); ?>">    </div>
	</div>
	
			
			<?php echo $answer->title; ?> <br />
			<?php echo JText::_("COM_QUESTIONS_SUBMITTED_BY"); ?> <?php /*echo $answer->name; ?> <?php echo JText::_("COM_QUESTIONS_AT"); ?>  <?php echo JHtml::date($answer->submitted); */?>
			<strong>
			<?php if($answer->userid_creator>0){ ?>
			<a href= <?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$answer->userid_creator. "%3A" . $answer->name) ?> ><?php echo $answer->name; ?></a>
            
            <?php } else { echo JText::_("COM_QUESTIONS_GUEST"); } ?></strong>
            
			<?php echo " On "; ?>  <?php echo JHtml::date($answer->submitted); ?>
					
			<?php echo JText::_("COM_QUESTIONS_ANSWER")."  :-"; ?> <br />
			<div class="question_text">
          	<?php echo $answer->text; ?>
            </div>
            <br />
            <div>
            <?php if(!empty($answer->refurl1))
				{
				echo "Reference: ".'<a href ="'.$this->addhttp($answer->refurl1).'" target="_blank">'.$this->smarttrim($answer->refurl1).'</a>'."<br />";
				}
			?>
             <?php if(!empty($answer->refurl2))
				{
				echo "Reference: ".'<a href ="'.$this->addhttp($answer->refurl2).'" target="_blank">'.$this->smarttrim($answer->refurl2).'</a>'."<br />";
				}
			?>
             <?php if(!empty($answer->refurl3))
				{
				echo "Reference: ".'<a href ="'.$this->addhttp($answer->refurl3).'" target="_blank">'.$this->smarttrim($answer->refurl3).'</a>'."<br />";
				}
			?>
            </div>
            <div style="clear:both"></div>
            
            <?php 
			if ($this->addfavorites):
		$reguser = JFactory::getUser();	
		$favarray2 = unserialize($this->getFavourite2('ansfav',$reguser->id));
		if(is_array($favarray2)){
		if(!in_array((int)$answer->id,$favarray2))
		{
		?>
		<a href="javascript:void(0)"  class="addfav" onclick="addfav('<?php 
		echo $answer->id."&userid=".$reguser->id."&vardata=ansfav"."&id=".$this->question->id; ?>',<?php echo $this->question->id; ?>)">
			<img src="components/com_questions/media/add.png" />
		</a>
		<?php } else { ?>
		<a href="javascript:void(0)" class="delfav" onclick="delfav('<?php 
		echo $answer->id."&userid=".$reguser->id."&vardata=ansfav"."&id=".$this->question->id; ?>',<?php echo $this->question->id; ?>)">
			<img src="components/com_questions/media/rem.png" />
		</a>
		<?php } 
		}
		endif;?>
            
            
			<?php 
			$check = $this->getBestAnswerid($this->question->id);
			if (!isset($check)):?>
			<?php if ($this->isOwner && $answer->chosen != 1): //Display "Choose" link ?>
			<span class="choose_answer"><a href="<?php echo JRoute::_("index.php?option=com_questions&task=answer.choose&questionid=" . $this->question->id . "&answerid=" . $answer->id)?>"><?php echo JText::_("COM_QUESTIONS_CHOOSE")?></a></span>
			<?php endif;?>
			<?php endif;?>
					
			<?php if ($this->isOwner && $answer->chosen == 1 && $this->getBestAnswerid($this->question->id)): //Display "Unchoose" link ?>
			<span class="choose_answer"><a href="<?php echo JRoute::_("index.php?option=com_questions&task=answer.chooseReset&questionid=" . $this->question->id . "&answerid=" . $answer->id)?>"><?php echo JText::_("COM_QUESTIONS_UNCHOOSE")?></a></span>
			<?php endif;?>
			<?php if ($answer->chosen == 1): //Display "Unchoose" link ?>
			<span class="choose_answer"><?php echo "This Answer has been Chosen as Best Answer"; ?></span>
			<?php endif;?>

			<div STYLE="border: none; float:right;">
            <?php
				$reguser2 = JFactory::getUser();   
				if( !$reguser2->authorise("question.answer" , "com_questions")){
				?>
				<?php if ($params->get('show_report', 1) && $reguser2->authorise ( "report.edit" , "com_questions" )) { ?>
				<a href="<?php echo JRoute::_("index.php?option=com_questions&view=reports&layout=edit&qid=".$answer->id); ?>">
				<div class="imagecss" style="float:left;">
				  <img alt="<?php echo JText::_("COM_QUESTIONS_REPORTIT")?>" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" />
				</div>
				</a>
				<?php } ?>
				<?php if ($params->get('display_help', 0)) { ?>
				<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid); ?>">
				<div class="imagecss" style="float:left;">
				  <img alt="<?php echo JText::_("COM_QUESTIONS_ANSWERTHIS")?>" src="<?php echo $this->baseurl ?>/components/com_questions/media/help.png" />
				</div>
				</a>
				<?php } ?>
				<?php } 
				else { ?>
				<?php if ($params->get('show_report', 1) && $reguser2->authorise ( "report.edit" , "com_questions" ) ) { ?>
				<a href="<?php echo JRoute::_("index.php?option=com_questions&view=reports&layout=edit&qid=".$answer->id); ?>">
				<div class="imagecss" style="float:left;">
				  <img alt="<?php echo JText::_("COM_QUESTIONS_REPORTIT")?>" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" />
				</div>
				</a>
				<?php }
				}
				?>
               </div>
		</div>
		<?php endforeach;?>
	<?php endif;?>
	
    <?php //if ($this->submitanswers && !$this->isOwner && $chosenanswer!=1) :?>
	<?php if ($this->submitanswers && $chosenanswer!=1) :?>
		<!-- ANSWER FORM -->
		<a name="newanswer">&nbsp;</a>
		<?php echo $this->loadTemplate('form'); 
		
		?>
	<?php endif;?>
    </div>
    <div style="text-align:right;">
    <?php if ($chosenanswer==1): ?>
	<?php echo '<font color="green">'. JText::_('COM_QUESTIONS_IS_MARKED_AS_RESOLVED').'</font>'; ?>
	<?php endif;?>
	<?php //echo $chosenanswer;?>	
	</div>
    </div>

<?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
	CopyrightHelper::copyright();
?>