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
	Email: admin@seohowto.net
	support: support@seohowto.net
	Website: http://www.seohowto.net
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view' );
//include helper functions
require_once ("administrator/components/com_questions/helpers/questions.php");

$app = JFactory::getApplication();
$parameters = $app->getParams();
JHTML::_('behavior.modal');
$articleid = $parameters->get('helparticleid', 1);
$appParams = json_decode(JFactory::getApplication()->getParams());
$chosenanswer=$this->getChosen($this->question->id);

$document =JFactory::getDocument();
$document->setTitle($this->question->title);
$keytags = implode(",", $this->question->qtags);
$document->setMetaData('keywords', $keytags);
//var_dump($keytags);
//$document->setKeyword($keytags);

if ($this->params->get('show_category_list', 1)) : ?>
<div class="questionbox">
<div style="width:250px;float:right;">
<form action="<?php echo JRoute::_('index.php');?>" method="post">
<?php
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
$mitemid = $set_Itemid > 0 ? $set_Itemid : JRequest::getInt('Itemid');
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
<?php if ($this->params->get('display_help', 0)) { ?>
<span style="float:right"><h2><a class="modal" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid.'&tmpl=component') ?>"rel="{handler: 'iframe', size: {x: 640, y: 480}}"><img src="components/com_questions/media/help.png" alt="Help"></a></h2></span>
<?php } ?>
<ul><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions"); ?>">Home</a></li><li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=form&layout=edit"); ?>"><?php echo JText::_("COM_QUESTIONS_ASK_A_QUESTION"); ?></a></li>
	</ul>
	</div>
<?php $catarray = $this->getCategories(); 
foreach($catarray as $category) {
if(isset($category->title) && $category->level==1){
    ?>
	<div class="Box_D">
	<ul class="questions_category"> 
	<h3>
	<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $category->id); ?>">
								<?php echo strtoupper($category->title)."(".$this->countCat($category->id).")"; ?>
	</a>
	</h3>
	<?php
	if(isset($category->lft) && isset($category->rgt)){
	$nestedcat = $this->nested($category->lft,$category->rgt,2);
	foreach($nestedcat as $nc){
	?>
	<li class="questions_category_li">
	<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $nc->id); ?>">
								<?php echo $nc->title."(".$this->countCat($nc->id).")"; ?>
	</a>
	</li>
	<?php
	}
	}
	?>
	</ul>
	</div>
	<?php
	}
} ?>
</div>
<?php endif; ?>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<div class="questionbox">
<div class="questions<?php echo $this->pageclass_sfx; ?>">
	<div class="votebox">
<?php if (!$this->isOwner ) :?>
		<a class="positive" href="<?php echo "index.php?option=com_questions&task=question.votepositive&id=" . $this->question->id ?>"><img src="components/com_questions/media/thumbs_up.png" /></a><br/>
<?php endif; ?>
		<span class="score"><?php echo $this->question->score2; ?></span><br />
<?php if (!$this->isOwner ) :?>
		<a class="negative" href="<?php echo "index.php?option=com_questions&task=question.votenegative&id=" . $this->question->id ?>"><img src="components/com_questions/media/thumbs_down.png" /></a>
<?php endif; ?>
	</div>
    <div id="user_profile">
    <?php
    if (isset($appParams->display_gravatars) && $appParams->display_gravatars!=0):{
   	?>
	<div style="float:left";>
	 <?php AvatarHelper::getAvatar($this->question->email,"questions_gravatar_big",64,0,$this->question->userid_creator); ?>
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
	<h2>
		<?php echo $this->question->title; ?>
		<div style="color:green; float:left;">
		<?php if ($chosenanswer==1){
		echo "[RESOLVED]";
		}?>
		</div>
		<?php if ($this->question->editable):?>
		<?php //if(JFactory::getConfig()->getValue('config.sef', false); ?>

		<a href="<?php echo "index.php?option=com_questions&task=question.edit&id=" . $this->question->id; ?>">
			<img src="media/system/images/edit.png" />
		</a>
		
		<?php endif; ?>
		<?php 
		$reguser = JFactory::getUser();
		$favarray = array();
		$favarray = unserialize($this->getFavourite2('quesfav',$reguser->id));
		if(!in_array($this->question->id,$favarray))
		{
		?>
		<a href="<?php 
		echo "index.php?option=com_questions&task=question.addFavourite&addfav=".$this->question->id."&userid=".$reguser->id."&vardata=quesfav"."&id=".$this->question->id; ?>">
			<img src="components/com_questions/media/add.png" />
		</a>
		<?php } else { ?>
		<a href="<?php 
		echo "index.php?option=com_questions&task=question.delFavourite&delfav=".$this->question->id."&userid=".$reguser->id."&vardata=quesfav"."&id=".$this->question->id; ?>">
			<img src="components/com_questions/media/rem.png" />
		</a>
		<?php } ?>
	</h2>
	
	<h4><?php echo JText::_("COM_QUESTIONS_SUBMITTED_BY"); ?> <?php /*echo ($this->question->userid_creator ? JFactory::getUser($this->question->userid_creator)->name : $this->question->name); */?>
	<a href= <?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$this->question->userid_creator . "%3A" . JFactory::getUser($this->question->userid_creator)->name) ?> ><?php echo ($this->question->userid_creator ? JFactory::getUser($this->question->userid_creator)->name : $this->question->name) ?></a>
	
	 <?php echo " On "?> <?php echo JHtml::date($this->question->submitted); ?>. 	<?php echo JText::_("COM_QUESTIONS_CATEGORY"); ?>: <a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $this->question->catid); ?>"><?php echo $this->question->CategoryName; ?></a></h4>

	<span class="tags">
	Tags:
		<?php 
		if ($this->question->qtags):
			foreach ($this->question->qtags as $tag):
				?>
				<span class="tagsitem">
				<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $tag); ?>"><?php echo $tag ?></a>
				</span>
			<?php 
			endforeach;
		endif;
		?>
	</span><br /><br /><br />
	<div style="float:left;">
	<?php echo JText::_("COM_QUESTIONS_QUESTION")." :-"; ?>
	</div>
	<br />
	<div class="question_text">
	<?php echo $this->strip_word_html($this->question->text,'<b><i><sup><sub><em><strong><u><br><br />'); ?>
	</div>
	<div class="fb_buttons"> 
		<?php
				$app = JFactory::getApplication();
				$params = $app->getParams();
				$twitter_login = $params->get('twitter_login', '');
	$twitter_widget = $params->get('twitter_widget', '');

	$small_twitter = $params->get('small_twitter', '');
	$small_facebook = $params->get('small_facebook', '');
	
	$big_twitter = $params->get('big_twitter', '');
	$big_facebook = $params->get('big_facebook', '');
	$big_share_facebook = $params->get('big_share_facebook', '');

	$facebook = $params->get('facebook', '');
	$twitter = $params->get('twitter', '');
	$myspace = $params->get('myspace', '');
	$stumbleupon = $params->get('stumbleupon', '');
	$reddit = $params->get('reddit', '');
	$delicious = $params->get('delicious', '');
	$google = $params->get('google', '');
	$mail = $params->get('mail', '');
	$print = $params->get('print', '');

	$number_of_tweets = $params->get('number_of_tweets', '');
	$show_avatars = $params->get('show_avatars', '');
	$show_timestamps = $params->get('show_timestamps', '');
	$show_hashtags = $params->get('show_hashtags', '');
	
	$shell_background = $params->get('shell_background', '');
	$shell_color = $params->get('shell_color', '');
	$tweets_background = $params->get('tweets_background', '');
	$tweets_color = $params->get('tweets_color', '');
	$tweets_links = $params->get('tweets_links', '');
	
	$icons_size = $params->get('icons_size', '');

	$social_url = JURI::current();

echo '<div class="social_module">';

if ($facebook != "hide" or $twitter != "hide" or $myspace != "hide" or $stumbleupon != "hide" or $reddit != "hide" or $delicious != "hide" or $google != "hide" or $mail != "hide" or $print != "hide") {
	echo <<<EOT

<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<div class="addthis_toolbox">
   <div class="custom_images">
EOT;
	if ($facebook == "show") { 
    ?>
		<a class="addthis_button_facebook"><div class="icon<?php echo $icons_size?> facebook<?php echo $icons_size ?>"></div></a>&nbsp; <?php 
	}
	if ($twitter == "show") {
		?>
		<a class="addthis_button_twitter"><div class="icon<?php echo $icons_size?> twitter<?php echo $icons_size ?>"></div></a>&nbsp; <?php 
		}
	if ($myspace == "show") {
		?>
		<a class="addthis_button_myspace"><div class="icon<?php echo $icons_size?> myspace<?php echo $icons_size ?>"></div></a>&nbsp; <?php 
		}
	if ($stumbleupon == "show") {
		?>
		<a class="addthis_button_stumbleupon"><div class="icon<?php echo $icons_size?> stumbleupon<?php echo $icons_size ?>"></div></a>&nbsp; <?php 
		}
	if ($reddit == "show") {
		?>
		<a class="addthis_button_reddit"><div class="icon<?php echo $icons_size?> reddit<?php echo $icons_size ?>"></div></a>&nbsp; <?php
	}
	if ($delicious == "show") {
		?>
		<a class="addthis_button_delicious"><div class="icon<?php echo $icons_size?> delicious<?php echo $icons_size ?>"></div></a>&nbsp; <?php
	}
	if ($google == "show") {
		?>
		<a class="addthis_button_google"><div class="icon<?php echo $icons_size?> google<?php echo $icons_size ?>"></div></a>&nbsp; <?php
		}
	if ($mail == "show") {
		?>
		<a class="addthis_button_email"><div class="icon<?php echo $icons_size?> email<?php echo $icons_size ?>"></div></a>&nbsp; <?php
	}
	if ($print == "show") {
		?>
		<a class="addthis_button_print"><div class="icon<?php echo $icons_size?> print<?php echo $icons_size ?>"></div></a>&nbsp; <?php
	}
	echo <<<EOT
   </div>
</div>
EOT;
}

if ($small_twitter != "hide" or $small_facebook != "hide") {
	echo <<<EOT
<br/>
<div class="addthis_toolbox">
EOT;
	if ($small_facebook == "show"){
		echo '<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>';
	}
	if ($small_twitter == "show"){
		echo '<a class="addthis_button_tweet"></a>';
	}
	echo <<<EOT
</div>

<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<br/>     
EOT;
}

if ($twitter_widget == "show") {

	if (empty($show_avatars)) {
		$show_avatars="false";
	}
	if (empty($show_timestamps)) {
		$show_timestamps="false";
	}
	if (empty($show_hashtags)) {
		$show_hashtags="false";
	}

	echo <<<EOT
<script src="http://widgets.twimg.com/j/2/widget.js" type="text/javascript"></script>
<script type="text/javascript">
new TWTR.Widget({
	version: 2,
	type: 'profile',
	rpp: $number_of_tweets,
	interval: 6000,
	width: 'auto',
	height: 250,
	theme: {
		shell: {
			background: '$shell_background',
			color: '$shell_color'
		},
		tweets: {
			background: '$tweets_background',
			color: '$tweets_color',
			links: '$tweets_links'
		}
	},
	features: {
		scrollbar: false,
		loop: false,
		live: false,
		hashtags: $show_hashtags,
		timestamp: $show_timestamps,
		avatars: $show_avatars,
		behavior: 'all'
	}
}).render().setUser('$twitter_login').start();
</script>
EOT;
}

if ($big_twitter == "show") {
	echo <<<EOT
<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<br/><br/>
EOT;
}

if ($big_facebook == "show") {
	echo <<<EOT
<iframe src="http://www.facebook.com/plugins/like.php?href=$social_url&amp;layout=standard&amp;show_faces=true&amp;width=150&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:80px;" allowTransparency="true"></iframe>
EOT;
}

if ($big_share_facebook == "show") {
	echo <<<EOT
<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<br/><br/>
EOT;
}

?>
</div>
<div STYLE="border: none; float:right;width:100%;">
<?php
$reguser = JFactory::getUser();   
if( !$reguser->authorise("question.answer" , "com_questions")){
?>
<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" ALT="Report It"></a>
<?php if ($params->get('display_help', 0)) { ?>
<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/answerthis.png" ALT="Answer This"></a>
<?php } ?>
<?php } 
else { ?>
	<a href="<?php echo JRoute::_("index.php?option=com_questions&view=reports&layout=edit&qid=".$this->question->id); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" ALT="Report It"></a>
    <?php if ($params->get('display_help', 0)) { ?>
	<a href="<?php echo $this->question->link; ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/answerthis.png" ALT="Answer This"></a>
    <?php } ?>
<?php	
}
?>
</div>
	</div>
	</div>
	</div>
    <div>
    <div>
	<div class="question_options">	

		<a href="<?php echo $this->question->link; ?>#answers"><?php echo count($this->question->answers);?></a>  <?php echo JText::_("COM_QUESTIONS_ANSWERS")?>. 
	
		<?php if ($this->submitanswers && !$this->isOwner):?>
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
				<a class="positive" href="<?php echo JRoute::_("index.php?option=com_questions&task=question.votepositive&id=" . $answer->id)?>"><img src="components/com_questions/media/thumbs_up.png" /></a><br />
				<?php endif;?>
				<span class="score"><?php echo $answer->score2; ?></span><br />
			<?php	if($user->id!= $answer->userid_creator) :?>
				<a class="negative" href="<?php echo JRoute::_("index.php?option=com_questions&task=question.votenegative&id=" . $answer->id)?>"><img src="components/com_questions/media/thumbs_down.png" /></a>
				<?php endif;?>
			</div>
			
			
		<?php 
		$reguser = JFactory::getUser();	
		$favarray = array();	
		$favarray = unserialize($this->getFavourite2('ansfav',$reguser->id));
		if(!in_array($answer->id,$favarray))
		{
		?>
		<a href="<?php 
		echo "index.php?option=com_questions&task=question.addFavourite&addfav=".$answer->id."&userid=".$reguser->id."&vardata=ansfav"."&id=".$this->question->id; ?>">
			<img src="components/com_questions/media/add.png" />
		</a>
		<?php } else { ?>
		<a href="<?php 
		echo "index.php?option=com_questions&task=question.delFavourite&delfav=".$answer->id."&userid=".$reguser->id."&vardata=ansfav"."&id=".$this->question->id; ?>">
			<img src="components/com_questions/media/rem.png" />
		</a>
		<?php } ?>
		
		
	<div id="user_profile">
    <?php
    if (isset($appParams->display_gravatars) && $appParams->display_gravatars!=0) :{
   	?>
	<div style="float:left";>
	<?php AvatarHelper::getAvatar($answer->email,"questions_gravatar_small",34,0,$answer->userid_creator); ?>
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
	
			
			<h3><?php echo $answer->title; ?></h3>
			<h5><?php echo JText::_("COM_QUESTIONS_SUBMITTED_BY"); ?> <?php /*echo $answer->name; ?> <?php echo JText::_("COM_QUESTIONS_AT"); ?>  <?php echo JHtml::date($answer->submitted); */?>
		
			<a href= <?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$answer->userid_creator. "%3A" . $answer->name) ?> ><?php echo $answer->name; ?></a>
			<?php echo " On "; ?>  <?php echo JHtml::date($answer->submitted); ?></h5>
			
			
			
			<?php echo JText::_("COM_QUESTIONS_ANSWER")."  :-"; ?> <br />
			<div class="question_text">
			<?php echo $this->strip_word_html($answer->text,'<b><i><sup><sub><em><strong><u><br><br />'); ?>
			</div>
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
			<div style="clear:both"></div>
			<?php
			$reguser2 = JFactory::getUser();   
			if( !$reguser2->authorise("question.answer" , "com_questions")){
			?>
			<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" ALT="Report It"></a>
             <?php if ($params->get('display_help', 0)) { ?>
			<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$articleid); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/answerthis.png" ALT="Answer This"></a>
            <?php } ?>
			<?php } 
			else { ?>
				<a href="<?php echo JRoute::_("index.php?option=com_questions&view=reports&layout=edit&qid=".$this->question->id); ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/reportit.png" ALT="Report It"></a>
                 <?php if ($params->get('display_help', 0)) { ?>
				<a href="<?php echo $this->question->link; ?>"><IMG STYLE="border: none; float:right;" src="<?php echo $this->baseurl ?>/components/com_questions/media/answerthis.png" ALT="Answer This"></a>
                <?php } ?>
			<?php	
			}
			?>
		</div>
		<?php endforeach;?>
	<?php endif;?>
	<?php if ($chosenanswer==1): ?>
	<?php echo '<H2><font color="red">Question is marked as resolved. No further Answering Allowed</font></H2>'; ?>
	<?php endif;?>
	<?php if ($this->submitanswers && !$this->isOwner && $chosenanswer!=1) :?>
		<!-- ANSWER FORM -->
		<a name="newanswer">&nbsp;</a>
		<?php echo $this->loadTemplate('form'); 
		
		?>
	<?php endif;?>
	<?php //echo $chosenanswer;?>	
	
</div>
<p style="text-align:right;"><small>Proudly Powered by </small><a target="_blank" href="http://seohowto.net/">SEO How To</a></p>