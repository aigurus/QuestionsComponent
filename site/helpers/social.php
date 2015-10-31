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

abstract class SocialIcons{

	public static function addsocial(){

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
	
?>
<?php
echo '<div class="social_module">';

if ($facebook != "hide" or $twitter != "hide" or $myspace != "hide" or $stumbleupon != "hide" or $reddit != "hide" or $delicious != "hide" or $google != "hide" or $mail != "hide" or $print != "hide") {

?>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>

<div class="addthis_toolbox">
   <div class="custom_images">
   <?php

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

	?>
   </div>
<?php

}

if ($small_twitter != "hide" or $small_facebook != "hide") {
?>
<?php
	if ($small_facebook == "show"){
		echo '<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>';
	}
	if ($small_twitter == "show"){
		echo '<a class="addthis_button_tweet"></a>';
	}
?>

<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<?php
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

?>
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
<?php
}

if ($big_twitter == "show") {
?>
<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<?php
}

if ($big_facebook == "show") {
?>
<iframe src="http://www.facebook.com/plugins/like.php?href=$social_url&amp;layout=standard&amp;show_faces=true&amp;width=150&amp;action=like&amp;colorscheme=light&amp;height=28px" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:28px;" allowTransparency="true"></iframe>
<?php
}

if ($big_share_facebook == "show") {
?>
<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<?php
}

?>
</div>
</div>
<?php } } ?>