/*!
 * jQuery UI Widget 1.9.1
 * http://jqueryui.com
 *
 * Copyright 2012 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/jQuery.widget/
 */
var $JQuery = jQuery.noConflict();
/*
$JQuery(document).ready(function(){
$JQuery('.tabs-section').hide();
});*/

$JQuery('#tabs a').bind('click', function(e){
$JQuery('#tabs a.current').removeClass('current');
$JQuery('.tabs-section:visible').hide();
$JQuery(this.hash).show();
$JQuery(this).addClass('current');
e.preventDefault;
});

$JQuery(document).ready(function(){
$JQuery('.tabs-section').hide();
$JQuery('#tabs a').bind('click', function(e){
e.preventDefault;
});
});

$JQuery('#tabs a').bind('click', function(e){
$JQuery(this.hash).show();
e.preventDefault;
});

$JQuery('#tabs a').bind('click', function(e){
$JQuery('.tabs-section:visible').hide();
$JQuery(this.hash).show();
e.preventDefault;
});