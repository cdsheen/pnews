<?

# PHP News Reader
# Copyright (C) 2001-2004 Shen Cheng-Da
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

#echo "<center>\n";

if( $CFG['html_header'] )
	readfile( $CFG['html_header'] );
elseif( $CFG['banner'] )
	echo "<a href=index.php>" . $CFG['banner'] . "</a><br />\n";
else
	echo "<font color=black size=5 face=Georgia>$title</font><br />";

/*
for( $i = 0 ; $i < $catalog_num ; $i++ ) {
	if( ! $nhd[$news_server[$i]] )
		$nhd[$i] = $nhd[$news_server[$i]] = nnrp_open( $news_server[$i] );
	else
		$nhd[$i] = $nhd[$news_server[$i]];
}
*/

$nhd = nnrp_open( $news_server[$curr_catalog], $news_nntps[$curr_catalog] );

echo "<br /><table width=95%><tr><td valign=top width=120>\n";

$maxr = 100;

echo "<table class=shadow border=1 cellpadding=0 cellspacing=0>\n<tr><td>\n";
echo "<table border=0 cellpadding=2 cellspacing=1>\n";
for( $i = 0 ; $i < $maxr ; $i++ ) {
	if( $i >= $catalog_num )
		break;
	if( $news_hidden[$i] )
		continue;
	echo "<tr>\n";
	if( $CFG['url_rewrite'] )
		$link = "section/$i";
	else
		$link = "$self?catalog=$i";
	if( $i >= $catalog_num )
		echo "<td class=menu align=center>&nbsp;</td>";
	elseif( $i == $curr_catalog )
		echo " <td class=menu_select align=center>$news_catalog[$i]</td>\n";
	elseif( $news_authperm[$i] )
		echo " <td class=menu_auth align=center onMouseover='this.className=\"menu_hover\";' onMouseout='this.className=\"menu_auth\";'><a class=menu href=\"$link\">$news_catalog[$i]</a></td>\n";
	else
		echo " <td class=menu align=center onMouseover='this.className=\"menu_hover\";' onMouseout='this.className=\"menu\";'><a class=menu href=\"$link\">$news_catalog[$i]</a></td>\n";
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
		echo "<tr><td class=logout align=center onMouseover='this.className=\"logout_hover\";' onMouseout='this.className=\"logout\";'><a class=menu href=\"$urlbase/logout\" title=\"$strLogout: $auth_user\">$strLogout</a></td></tr>";
	if( $CFG['auth_type'] == 'optional' && !$auth_success )
		echo "<tr><td class=login align=center onMouseover='this.className=\"login_hover\";' onMouseout='this.className=\"login\";'><a class=menu href=\"$urlbase/login\">$strLogin</a></td></tr>";
}
else {
	if( $CFG['auth_type'] != 'open' && $auth_success )
		echo "<tr><td class=logout align=center onMouseover='this.className=\"logout_hover\";' onMouseout='this.className=\"logout\";'><a class=menu href=\"auth.php?logout=1\" title=\"$strLogout: $auth_user\">$strLogout</a></td></tr>";
	if( $CFG['auth_type'] == 'optional' && !$auth_success )
		echo "<tr><td class=login align=center onMouseover='this.className=\"login_hover\";' onMouseout='this.className=\"login\";'><a class=menu href=\"auth.php?login=1\">$strLogin</a></td></tr>";
}

echo "</table>\n";
echo "</td></tr></table>\n";

echo "</td><td valign=top align=left>";

if( ! $nhd ) {
	echo "<br /><br /><font size=3>$strConnectServerError (" . $news_server[$curr_catalog] . ")</font></td></tr></table>\n";
	html_foot();
	html_tail();
	exit;
}

nnrp_authenticate( $nhd );

$active = nnrp_list_group( $nhd, $news_groups[$curr_catalog], $article_convert['to'] );

if( $active == null ) {
	echo "<br /><br /><font size=3>$strConnectServerError &lt;" . $news_server[$curr_catalog] . "&gt;</font></td></tr></table>\n";
	html_foot();
	html_tail();
	exit;
}

/*
if( $global_readonly ) {
	echo "<font color=red>* $strReadonlyNotify</font>\n";
	echo '<p>';
}
*/


echo "<table class=shadow border=1 cellpadding=0 cellspacing=0>\n<tr><td>\n";

// $row = array( $strNumber, $strPostNumber, $strGroup, $strGroupDescription );

if( $CFG['show_group_description'] )
	echo <<<EOH
<table border=0 cellspacing=2 cellpadding=2>
<tr class=header height=25>
  <td>$strNumber</td>
  <td align=right>$strPostNumber</td>
  <td>$strGroup</td>
  <td>$strGroupDescription</td>
</tr>

EOH;
else
	echo <<<EOH
<table border=0 cellspacing=2 cellpadding=2>
<tr class=header height=25>
  <td>$strNumber</td>
  <td align=right>$strPostNumber</td>
  <td>$strGroup</td>
</tr>

EOH;

if( $CFG['group_sorting'] )
	ksort( $active );

reset( $active );

$i = 0;

$server = $news_server[$curr_catalog];

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

	if( strlen( $value[2] ) > 50 )
		$value[2] = htmlspecialchars(substr( $value[2], 0, 50 )) . ' ..';
	elseif( $value[2] == '' )
		$value[2] = '&nbsp;';

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

echo "</td></tr></table>\n";

#echo "</center>\n";

html_foot();

if( $nhd ) nnrp_close($nhd);

if( $CFG['html_footer'] )
	readfile( $CFG['html_footer'] );

html_tail();

?>
