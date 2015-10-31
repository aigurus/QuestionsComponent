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
<div id="feeds_page" style="margin:10px 5px;">
<?php
$insideitem = false;
$tag = "";
$title = "";
$description = "";
$link = "";
function startElement($parser, $name, $attrs) {
 global $insideitem, $tag, $title, $description, $link;
 if ($insideitem) {
  $tag = $name;
 } elseif ($name == "ITEM") {
  $insideitem = true;
 }
}
function endElement($parser, $name) {
 global $insideitem, $tag, $title, $description, $link;
 if ($name == "ITEM") {
  printf("<dt><b><a href='%s'>%s</a></b></dt>",
  trim($link),htmlspecialchars(trim($title)));
  printf("<dt>%s</dt><br><br><br />",strip_tags($description));
  $title = "";
  $description = "";
  $link = "";
  $insideitem = false;
 }
}
function characterData($parser, $data) {
 global $insideitem, $tag, $title, $description, $link;
 if ($insideitem) {
 switch ($tag) {
  case "TITLE":
  $title .= $data;
  break;
  case "DESCRIPTION":
  $description .= $data;
  break;
  case "LINK":
  $link .= $data;
  break;
 }
 }
}
$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
$fp = fopen("http://joomlaseo.org/index.php?option=com_ninjarsssyndicator&feed_id=1&format=raw","r")
 or die("Error reading RSS data.");
while ($data = fread($fp, 4096))
 xml_parse($xml_parser, $data, feof($fp))
  or die(sprintf("XML error: %s at line %d",
   xml_error_string(xml_get_error_code($xml_parser)),  
   xml_get_current_line_number($xml_parser)));
fclose($fp);
xml_parser_free($xml_parser);
?>
    <div style=" background-color: #FFFFCC; border: 1px solid #FDE2AC; padding: 8px; color:#FF6633; margin-bottom: 5px;">www.joomlaseo.org </div>
</div>
