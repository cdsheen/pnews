<?

require_once('language.inc.php');

function html_head($title, $redirect = null, $bodymod = '' ) {
	global $lang_coding, $curr_language;
	$region = $curr_language;
	$coding = $lang_coding[$region];
	echo "<html>
<head>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$coding\">
<META HTTP-EQUIV=\"Content-Language\" CONTENT=\"$region\">
<LINK REL=STYLESHEET TYPE=\"text/css\" HREF=\"style.css\">
<script language=\"javascript\" src=\"utils.js\">
</script>
";
	if( $redirect )
		echo "\n<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL=$redirect\">";

	echo "<title>$title</title>\n</head>\n";
	echo "<body $bodymod>\n";
}

function show_language_switch() {
	global $CFG, $lang_option, $curr_language;
	if( $CFG['language_switch'] ) {
		$uri = $_SERVER['REQUEST_URI'];
		$path = preg_replace( '/\/([^\/]+)$/', '/', $uri );
		echo "<select class=lang onChange='change_language(this.value, \"$path\", \"$uri\");'>\n";
		foreach( $lang_option as $region => $desc ) {
			if( $region == $curr_language )
				echo "<option value=\"$region\" selected>$desc\n";
			else
				echo "<option value=\"$region\">$desc\n";
		}
		echo "</select>\n";
	}
}

function html_foot() {
	global $lang_define, $CFG, $pnews_version;
?>
  <p>
<hr><table width=100% border=0 cellpadding=0 cellspacing=0>
  <tr><td>
     <i>
       <font size=2>
<?
	if( $CFG['author_link'] == false )
		echo "PHP News Reader $pnews_version by Shen Cheng-Da";
	else
		echo '<a href="release.php" target=_blank>PHP News Reader</a> ' . $pnews_version . ' by <a href="http://www.csie.nctu.edu.tw/~cdsheen/" target=_blank>Shen Cheng-Da</a>';
?>

       </font>
     </i>
  </td><td align=right>
     <font size=2>
<?
	if( $CFG['language_switch'] ) {
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
	echo "\n<!-- Copyright (C) 2001-2003 - All rights reserved -->\n";
	echo "<!-- Shen Cheng-Da (cdsheen@csie.nctu.edu.tw) -->\n";
	echo "\n</body>\n";
	echo "</html>\n";
}

function read_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	$class_text = ( $class == null ) ? '' : " class=$class" ;
//	return "<a$class_text href=null onClick=\"close_window(); read_article( '$server', '$group', $artnum )\">$link_text</a>";
	if( $close )
		return "<a$class_text href=\"javascript:read_article( '$server', '$group', $artnum ); close_window();\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:read_article( '$server', '$group', $artnum )\">$link_text</a>";
}

function post_article( $server, $group, $link_text, $close = false, $class = null ) {
	$class_text = ( $class == null ) ? '' : " class=$class" ;
//	return "<a$class_text href=null onClick=\"close_window(); post_article( '$server', '$group' )\">$link_text</a>";
	if( $close )
		return "<a$class_text href=\"javascript:post_article( '$server', '$group' ); close_window();\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:post_article( '$server', '$group' )\">$link_text</a>";
}

function delete_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	$class_text = ( $class == null ) ? '' : " class=$class" ;
//	return "<a$class_text href=null onClick=\"close_window(); delete_article( '$server', '$group', $artnum )\">$link_text</a>";
	if( $close )
		return "<a$class_text href=\"javascript:delete_article( '$server', '$group', $artnum ); close_window();\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:delete_article( '$server', '$group', $artnum )\">$link_text</a>";
}

function reply_article( $server, $group, $artnum, $link_text, $quote = false, $close = false, $class = null ) {
	$class_text = ( $class == null ) ? '' : " class=$class" ;
	$quote = ( $quote ? 1 : 0 );
//	return "<a$class_text href=null onClick=\"close_window(); reply_article( '$server', '$group', $artnum, $quote )\">$link_text</a>";
	if( $close )
		return "<a$class_text href=\"javascript:reply_article( '$server', '$group', $artnum, $quote ); close_window();\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:reply_article( '$server', '$group', $artnum, $quote )\">$link_text</a>";
}

function xpost_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	$class_text = ( $class == null ) ? '' : " class=$class" ;
//	return "<a$class_text href=null onClick=\"close_window(); xpost_article( '$server', '$group', $artnum )\">$link_text</a>";
	if( $close )
		return "<a$class_text href=\"javascript:xpost_article( '$server', '$group', $artnum ); close_window();\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:xpost_article( '$server', '$group', $artnum )\">$link_text</a>";
}

function forward_article( $server, $group, $artnum, $link_text, $close = false, $class = null ) {
	$class_text = ( $class == null ) ? '' : " class=$class" ;
//	return "<a$class_text href=null onClick=\"close_window(); forward_article( '$server', '$group', $artnum )\">$link_text</a>";
	if( $close )
		return "<a$class_text href=\"javascript:forward_article( '$server', '$group', $artnum ); close_window();\">$link_text</a>";
	else
		return "<a$class_text href=\"javascript:forward_article( '$server', '$group', $artnum )\">$link_text</a>";
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
