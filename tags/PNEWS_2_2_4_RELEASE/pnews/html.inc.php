<?

# PHP News Reader
# Copyright (C) 2001-2003 Shen Chang-Da
# 
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

require_once('language.inc.php');

if( $_SERVER['HTTPS'] ) {
	$CFG['url_base'] = str_replace( 'http://', 'https://', $CFG['url_base'] );
	$sflogo = 'https://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';
}
else
	$sflogo = 'http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';

#if( $CFG['url_rewrite'] )

$urlbase  = preg_replace( '/[\\/]*$/', '', $CFG['url_base'] );

function html_head($title, $redirect = null, $bodymod = '' ) {
	global $lang_coding, $curr_language, $urlbase, $CFG;
	$region = $curr_language;
	$coding = $lang_coding[$region];
	echo <<<EOH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=$coding">
<META HTTP-EQUIV="Content-Language" CONTENT="$region">
<BASE HREF="$urlbase/">

EOH;


	if( $redirect )
		echo "\n<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL=$redirect\">\n";

	echo <<<EOX
  <LINK REL=STYLESHEET TYPE="text/css" HREF="css/{$CFG['style_sheet']}">
  <script language="javascript" src="utils.js"></script>
  <title>$title </title>
</head>
<body $bodymod>

EOX;

}

function show_language_switch() {
	global $CFG, $lang_option, $curr_language, $lang_coding;
	if( $CFG['language_switch'] ) {
		$uri = $_SERVER['REQUEST_URI'];
		$path = preg_replace( '/\/([^\/]+)$/', '/', $uri );
		if( $CFG['url_rewrite'] )
			echo "<select class=lang onChange='change_language_base( \"" . $CFG['url_base'] . "\", this.value, \"$path\", \"$uri\");'>\n";
		else
			echo "<select class=lang onChange='change_language(this.value, \"$path\", \"$uri\");'>\n";
		foreach( $lang_option as $region => $desc ) {
			$charset = $lang_coding[$region];
			if( $region == $curr_language )
				echo "<option value=\"$charset\" selected>$desc\n";
			else
				echo "<option value=\"$charset\">$desc\n";
		}
		echo "</select>\n";
	}
}

function html_foot($langopt = true) {
	global $lang_define, $CFG, $pnews_version, $sflogo;
?>
  <p>
<hr><table width=100% border=0 cellpadding=0 cellspacing=0>
  <tr class=footbar><td>
     <i>
<?
	if( $CFG['author_link'] == false )
		echo "<a href=\"http://sourceforge.net/projects/pnews/\" target=_blank>PHP News Reader</a> $pnews_version by Shen Cheng-Da";
	else
		echo '<a href="doc/index.php" target=_blank>PHP News Reader</a> ' . $pnews_version . ' by Shen Cheng-Da';
?>
     </i>
     &nbsp;
</td>
<td align=right valign=center>
<?
	if( $CFG['show_sourceforge_logo'] ) {
		echo <<<EOL
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" height=20 alt="SourceForge.net Logo">
</a>

EOL;
	}
?>
  </td><td align=right>
<?
	if( $CFG['language_switch'] && $langopt ) {
		echo "Language:";
		show_language_switch();
	}
?>
  </td></tr>
  </table>
<?
}

function html_tail() {

	echo <<<EOT

<!-- Copyright (C) 2001-2003 - All rights reserved -->
<!-- Shen Cheng-Da (cdsheen@users.sourceforge.net) -->
</body>
</html>

EOT;

}

function read_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	global $CFG, $group_default_server;
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$close_cmd = $close ? 'close_window();' : '';

	if( $server == $group_default_server )
		$reserver = '';
	else
		$reserver = $server;

	if( $CFG['show_article_popup'] )
		if( $CFG['url_rewrite'] )
			return "<a$class_text href=\"javascript:read_article( '" . $CFG['url_base'] . "', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
		else
			return "<a$class_text href=\"javascript:read_article( '', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
	else
		if( $CFG['url_rewrite'] )
			return "<a$class_text href=\"article/$reserver/$group/$artnum\">$link_text</a>";
		else
			return "<a$class_text href=\"read-art.php?server=$server&group=$group&artnum=$artnum\">$link_text</a>";
}

function post_article( $server, $group, $link_text, $close = false, $class = null ) {
	global $CFG;
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$close_cmd = ( $close ) ? 'close_window();' : '';
#	$close_cmd = ( $CFG['show_article_popup'] ) ? 'close_window();' : '';
	if( $CFG['url_rewrite'] )
		return "<a$class_text href=\"javascript:post_article( '" . $CFG['url_base'] . "', '$server', '$group' ); $close_cmd\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:post_article( '', '$server', '$group' ); $close_cmd\">$link_text</a>";
}

function delete_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	global $CFG;
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$close_cmd = ( $CFG['show_article_popup'] ) ? 'close_window();' : '';
	if( $CFG['url_rewrite'] )
		return "<a$class_text href=\"javascript:delete_article( '" . $CFG['url_base'] . "', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:delete_article( '', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
}

function reply_article( $server, $group, $artnum, $link_text, $quote = false, $close = false, $class = null ) {
	global $CFG;
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$close_cmd = ( $CFG['show_article_popup'] ) ? 'close_window();' : '';
	$quote = ( $quote ? 1 : 0 );
	if( $CFG['url_rewrite'] )
		return "<a$class_text href=\"javascript:reply_article( '" . $CFG['url_base'] . "', '$server', '$group', $artnum, $quote ); $close_cmd\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:reply_article( '', '$server', '$group', $artnum, $quote ); $close_cmd\">$link_text</a>";
}

function xpost_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	global $CFG;
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$close_cmd = ( $CFG['show_article_popup'] ) ? 'close_window();' : '';
	if( $CFG['url_rewrite'] )
		return "<a$class_text href=\"javascript:xpost_article( '" . $CFG['url_base'] . "', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:xpost_article( '', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
}

function forward_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	global $CFG;
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$close_cmd = ( $CFG['show_article_popup'] ) ? 'close_window();' : '';
	if( $CFG['url_rewrite'] )
		return "<a$class_text href=\"javascript:forward_article( '" . $CFG['url_base'] . "', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:forward_article( '', '$server', '$group', $artnum ); $close_cmd\">$link_text</a>";
}

function html_focus( $form, $field ) {
	echo "<script language=\"javascript\">\n";
	echo "	document.$form.$field.focus();\n";
	echo "</script>\n";
}

function html_delay_close( $mini_seconds ) {
	echo "<script language=\"javascript\">\n";
	echo "	setTimeout( \"close_window();\", $mini_seconds );\n";
	echo "</script>\n";
}

?>
