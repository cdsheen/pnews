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

function html_head($title, $redirect = null, $bodymod = '' ) {
	global $lang_coding, $curr_language, $CFG;
	$region = $curr_language;
	$coding = $lang_coding[$region];
	echo <<<EOH
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=$coding">
<META HTTP-EQUIV="Content-Language" CONTENT="$region">

EOH;

	if( $CFG['url_rewrite'] )
		echo '<base href="' . $CFG['url_base'] . '">';
	if( $redirect )
		echo "\n<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL=$redirect\">";

	echo <<<EOX
  <LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
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
	global $lang_define, $CFG, $pnews_version;
?>
  <p>
<hr><table width=100% border=0 cellpadding=0 cellspacing=0>
  <tr><td>
     <i>
       <font size=2>
<?
	if( $CFG['author_link'] == false )
		echo "<a href=\"http://sourceforge.net/projects/pnews/\" target=_blank>PHP News Reader</a> $pnews_version by Shen Cheng-Da";
	else
		echo '<a href="doc/index.php" target=_blank>PHP News Reader</a> ' . $pnews_version . ' by Shen Cheng-Da';
?>

       </font>
     </i>
     &nbsp;
</td>
<td align=right valign=center>
<?
	if( $CFG['show_sourceforge_logo'] ) {
		echo <<<EOL
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1" border="0" height=20 alt="SourceForge.net Logo">
</a>

EOL;
	}
?>
  </td><td align=right>
     <font size=2>
<?
	if( $CFG['language_switch'] && $langopt ) {
		echo "<i>Language:</i>";
		show_language_switch();
	}
?>
     </font>
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

function table_begin( $border = 1, $spacing = 0, $padding = 2 ) {
	echo "<table border=$border cellspacing=$spacing cellpadding=$padding>\n";
}

function table_head( $heads, $trclass, $tdclass, $height = 0 ) {
	if( $height == 0 )
		echo "<tr class=$trclass>\n";
	else
		echo "<tr class=$trclass height=$height>\n";
	foreach( $heads as $field ) {
		if( $field == '' )
			echo "  <td class=$tdclass>&nbsp;</td>\n";
		elseif( $field[0] == ':' && preg_match( '/^:([^:]+):(.*)$/', $field, $match ) ) {
			$mod_list = split( ',', $match[1] );
			$mod_text = '';
			if( $tdclass != '' );
				$class = "class=$tdclass";
			foreach( $mod_list as $mod ) {
				if( $mod[0] == 'r' )
					$mod_text .= ' rowspan=' . substr( $mod, 1 );
				elseif( $mod[0] == 'c' )
					$mod_text .= ' colspan=' . substr( $mod, 1 );
				elseif( $mod[0] == 'l' )
					$class = 'class=' . substr( $mod, 1 );
				elseif( $mod[0] == 't' )
					$mod_text = 'title="' . substr( $mod, 1 ) . '"';
				elseif( $mod == '<' )
					$mod_text .= ' align=left';
				elseif( $mod == '^' )
					$mod_text .= ' align=middle';
				elseif( $mod == '>' )
					$mod_text .= ' align=right';
			}
			if( $match[2] == '' )
				$match[2] = '&nbsp;';
			echo "  <td $class $mod_text>" . $match[2] . "</td>\n";
		}
		else
			echo "  <td class=$tdclass>$field</td>\n";
	}
	echo "</tr>\n";
}

function table_row( $n, $rows, $oddcolor, $evencolor, $selectcolor, $tdclass, $attrs = '' ) {

	if ( $n % 2 == 1 )
		echo "<tr $attrs bgcolor=$oddcolor onMouseover='this.bgColor=\"$selectcolor\";' onMouseout='this.bgColor=\"$oddcolor\";'>\n";
	else
		echo "<tr $attrs bgcolor=$evencolor onMouseover='this.bgColor=\"$selectcolor\";' onMouseout='this.bgColor=\"$evencolor\";'>\n";

	foreach( $rows as $field ) {
		if( $tdclass != '' );
			$class = "class=$tdclass";
		if( $field == '' )
			echo "  <td class=$tdclass>&nbsp;</td>\n";
		elseif( $field[0] == ':' && preg_match( '/^:([^:]+):(.*)$/', $field, $match ) ) {
			$mod_list = split( ',', $match[1] );
			$mod_text = '';
			foreach( $mod_list as $mod ) {
				if( $mod[0] == 'r' )
					$mod_text .= ' rowspan=' . substr( $mod, 1 );
				elseif( $mod[0] == 'c' )
					$mod_text .= ' colspan=' . substr( $mod, 1 );
				elseif( $mod[0] == 'l' )
					$class = 'class=' . substr( $mod, 1 );
				elseif( $mod[0] == 't' )
					$mod_text = 'title="' . substr( $mod, 1 ) . '"';
				elseif( $mod == '<' )
					$mod_text .= ' align=left';
				elseif( $mod == '^' )
					$mod_text .= ' align=middle';
				elseif( $mod == '>' )
					$mod_text .= ' align=right';
			}
			if( $match[2] == '' )
				$match[2] = '&nbsp;';
			echo "  <td $class $mod_text>" . $match[2] . "</td>\n";
		}
		else
			echo "  <td $class>$field</td>\n";
	}
	echo "</tr>\n";
}

function table_end() {
	echo "</table>\n";
}

?>
