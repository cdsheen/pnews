<?

# PHP News Reader
# Copyright (C) 2001-2007 Shen Cheng-Da
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

require_once('utils.inc.php');

# ---------------------------------------------------------------------

html_head( $title );

if( $CFG['html_header'] ) {
	if( preg_match( '/\.php$/', $CFG['html_header'] ) )
		include( $CFG['html_header'] );
	else
		readfile( $CFG['html_header'] );
}
elseif( $CFG['banner'] )
	echo "<a href=index.php>" . $CFG['banner'] . "</a><br />\n";
else
	echo "<a href=index.php><span class=title>$title</span><br />";

$nnrp->open( $news_server[$curr_category], $news_nntps[$curr_category] );

echo "<p>";

echo "<table width=100%><tr><td valign=top width=120>\n";

$maxr = 100;

echo "<table class=shadow border=1 cellpadding=0 cellspacing=0>\n<tr><td>\n";
echo "<table border=0 cellpadding=2 cellspacing=1>\n";
for( $i = 0 ; $i < $maxr ; $i++ ) {
	if( $i >= $category_num )
		break;
	if( $news_hidden[$i] )
		continue;
	echo "<tr>\n";
	if( $CFG['url_rewrite'] )
		$link = $i+1;
	else
		$link = "$self?category=" . ($i+1);
	if( $i >= $category_num )
		echo "<td class=menu align=center>&nbsp;</td>";
	elseif( $i == $curr_category )
		echo " <td class=menu_select align=center>$news_category[$i]</td>\n";
	elseif( $news_authperm[$i] )
		echo " <td class=menu_auth align=center onMouseover='this.className=\"menu_hover\";' onMouseout='this.className=\"menu_auth\";'><a class=menu href=\"$link\">$news_category[$i]</a></td>\n";
	else
		echo " <td class=menu align=center onMouseover='this.className=\"menu_hover\";' onMouseout='this.className=\"menu\";'><a class=menu href=\"$link\">$news_category[$i]</a></td>\n";
	echo "</tr>\n";
}

if( is_array($CFG['links']) )
	foreach( $CFG['links'] as $text => $link ) {
		if( $config_convert['to'] ) {
			$text = $config_convert['to']($text);
			$link = $config_convert['to']($link);
		}
		echo "<tr><td class=menu_link width=100 align=center onMouseover='this.className=\"menu_hover\";' onMouseout='this.className=\"menu_link\";'><a href=\"" . $link . '">' . $text . '</a></td></tr>';
	}

if( $CFG['url_rewrite'] ) {
	if( $CFG['auth_type'] != 'open' && $auth_success )
		echo "<tr><td class=logout align=center onMouseover='this.className=\"logout_hover\";' onMouseout='this.className=\"logout\";'><a class=menu href=\"$urlbase/logout\" title=\"$pnews_msg[Logout]: $auth_user\">$pnews_msg[Logout]</a></td></tr>";
	if( $CFG['auth_type'] == 'optional' && !$auth_success )
		echo "<tr><td class=login align=center onMouseover='this.className=\"login_hover\";' onMouseout='this.className=\"login\";'><a class=menu href=\"$urlbase/login\">$pnews_msg[Login]</a></td></tr>";
}
else {
	if( $CFG['auth_type'] != 'open' && $auth_success )
		echo "<tr><td class=logout align=center onMouseover='this.className=\"logout_hover\";' onMouseout='this.className=\"logout\";'><a class=menu href=\"auth.php?logout=1\" title=\"$pnews_msg[Logout]: $auth_user\">$pnews_msg[Logout]</a></td></tr>";
	if( $CFG['auth_type'] == 'optional' && !$auth_success )
		echo "<tr><td class=login align=center onMouseover='this.className=\"login_hover\";' onMouseout='this.className=\"login\";'><a class=menu href=\"auth.php?login=1\">$pnews_msg[Login]</a></td></tr>";
}

echo "</table>\n";
echo "</td></tr></table>\n";

echo "</td><td valign=top align=left>";

if( ! $nnrp->connected() ) {
	echo "<br /><br /><font size=3>$pnews_msg[ConnectServerError] (" . $news_server[$curr_category] . ")</font></td></tr></table>\n";
	html_foot();
	html_tail();
	exit;
}

nnrp_authenticate();

$active = $nnrp->list_group( $news_groups[$curr_category], $article_convert['to'] );

if( $active == null ) {
	echo "<br /><br /><font size=3>$pnews_msg[ConnectServerError] &lt;" . $news_server[$curr_category] . "&gt;</font></td></tr></table>\n";
	html_foot();
	html_tail();
	exit;
}

/*
if( $global_readonly ) {
	echo "<font color=red>* $pnews_msg[ReadonlyNotify]</font>\n";
	echo '<p>';
}
*/


echo "<table class=shadow border=1 cellpadding=0 cellspacing=0>\n<tr><td>\n";

// $row = array( $pnews_msg['Number'], $pnews_msg['PostNumber'], $pnews_msg['Group'], $pnews_msg['GroupDescription'] );

if( $CFG['show_group_description'] )
	echo <<<EOH
<table border=0 cellspacing=2 cellpadding=2>
<tr class=header height=25>
  <td align=right>$pnews_msg[Number]</td>
  <td align=right>$pnews_msg[PostNumber]</td>
  <td>$pnews_msg[Group]</td>
  <td>$pnews_msg[GroupDescription]</td>
</tr>

EOH;
else
	echo <<<EOH
<table border=0 cellspacing=2 cellpadding=2>
<tr class=header height=25>
  <td>$pnews_msg[Number]</td>
  <td align=right>$pnews_msg[PostNumber]</td>
  <td>$pnews_msg[Group]</td>
</tr>

EOH;

if( $CFG['group_sorting'] )
	ksort( $active );

reset( $active );

$i = 0;

$server = $news_server[$curr_category];

while ( list ($group, $value) = each ($active) ) {

	$i++;

	if( $CFG['magic_tag'] ) {
		$magic = $value[0];
		if( $CFG['url_rewrite'] ) {
			if( $server == $group_default_server )
				$glink = "<a href=\"group//$group?$magic\">$group</a>";
			else
				$glink = "<a href=\"group/$server/$group?$magic\">$group</a>";
		}
		else
			$glink = "<a href=\"indexing.php?server=$server&group=$group&magic=$magic\">$group</a>";
	}
	else {
		if( $CFG['url_rewrite'] ) {
			if( $server == $group_default_server )
				$glink = "<a href=\"group//$group\">$group</a>";
			else
				$glink = "<a href=\"group/$server/$group\">$group</a>";
		}
		else
			$glink = "<a href=\"indexing.php?server=$server&group=$group\">$group</a>";
	}

	if( !isset($value[2]) || $value[2] == '' )
		$value[2] = '&nbsp;';
	elseif( strlen( $value[2] ) > 50 )
		$value[2] = htmlspecialchars(substr( $value[2], 0, 50 )) . ' ..';

	$num = $value[0] - $value[1] + 1;
	if( $num < 0 ) $num = 0;

if( $CFG['show_group_description'] )
	echo <<<EOR
<tr class=list onMouseover='this.className="list_hover";' onMouseout='this.className="list";'>
  <td align=right><i>$i</i></td>
  <td align=right>$num</td>
  <td>$glink</td>
  <td>$value[2]</td>
</tr>

EOR;
else
	echo <<<EOR
<tr class=list onMouseover='this.className="list_hover";' onMouseout='this.className="list";'>
  <td align=right><i>$i</i></td>
  <td align=right>$num</td>
  <td>$glink</td>
</tr>

EOR;

}

echo "</table>\n";
echo "</td></tr></table>\n";

if( $CFG['advertise_group_list'] )
	echo '</td><td valign=top align=right>'.$CFG['advertise_group_list'];

echo "</td></tr></table>\n";

html_foot();

$nnrp->close();

if( $CFG['html_footer'] ) {
	if( preg_match( '/\.php$/', $CFG['html_footer'] ) )
		include( $CFG['html_footer'] );
	else
		readfile( $CFG['html_footer'] );
}
html_tail();

?>
