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

require_once('utils.inc.php');

# ---------------------------------------------------------------------

html_head( $title );

echo "<center>\n";

if( $CFG['banner'] )
	echo "<a href=index.php>" . $CFG['banner'] . "</a><br>\n";
else
	echo "<font color=black size=5 face=Georgia>$title</font><p>";

for( $i = 0 ; $i < $catalog_num ; $i++ ) {
	if( ! $nhd[$news_server[$i]] ) {
#		echo "open " . $news_server[$i] . " as $i<br>\n";
		$nhd[$i] = $nhd[$news_server[$i]] = nnrp_open( $news_server[$i] );

		if( ! $nhd[$i] ) {
			echo "<p><font size=3>$strConnectServerError - " . $news_server[$i] . "</font><br>\n";
			html_foot();
			html_tail();
			exit;
		}
	}
	else
		$nhd[$i] = $nhd[$news_server[$i]];
}

$active = nnrp_list_group( $nhd[$curr_catalog], $news_groups[$curr_catalog], $article_convert['to'] );

if( $active == null ) {
	echo "<p><font size=3 color=black>$strConnectServerError</font>\n";
	html_foot();
	html_tail();
	exit;
}

if( $post_restriction ) {
	echo "<font color=red>* $strReadonlyNotify</font>\n";
	echo '<p>';
}
$maxr = 30;
$maxc = $catalog_num / $maxr;

echo "<br><table><tr><td valign=top width=120>\n";

echo "<table border=1 cellpadding=2 cellspacing=0>\n";
for( $i = 0 ; $i < $maxr ; $i++ ) {
	if( $i >= $catalog_num )
		break;
	echo "<tr>\n";
	for( $j = 0 ; $j < $maxc ; $j++ ) {
		$cn = $i + $j * $maxr ;
		if( $cn >= $catalog_num )
			echo "<td width=100 bgcolor=#EEFFEE>&nbsp;</td>";
		elseif( $cn == $curr_catalog )
			echo " <td width=100 bgcolor=#D0D0FF align=center>$news_catalog[$cn]</td>\n";
		elseif( $news_authperm[$cn] )
			echo " <td width=100 bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#D0D0FF\";' onMouseout='this.bgColor=\"#DDFFDD\";'><a class=cat href=$self?catalog=$i>$news_catalog[$cn]</a></td>\n";
		else
			echo " <td width=100 bgcolor=#EEFFEE align=center onMouseover='this.bgColor=\"#D0D0FF\";' onMouseout='this.bgColor=\"#EEFFEE\";'><a class=cat href=$self?catalog=$i>$news_catalog[$cn]</a></td>\n";
	}
	echo "</tr>\n";
}

if( is_array($CFG['links']) )
	foreach( $CFG['links'] as $text => $link ) {
		if( $config_convert['to'] ) {
			$text = $config_convert['to']($text);
			$link = $config_convert['to']($link);
		}
		echo "<tr><td colspan=$maxc width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"" . $link . '">' . $text . '</a></td></tr>';
	}
echo "<tr><td colspan=$maxc width=100 bgcolor=#EEFFFF align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#EEFFFF\";'><a href=\"javascript:reload()\">$strRefresh</a></td></tr>";
if( $CFG['auth_type'] != 'open' && $auth_success )
	echo "<tr><td colspan=$maxc width=100 bgcolor=#EEFFFF align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#EEFFFF\";'><a href=\"$self?logout=1\">$strLogout</a></td></tr>";
if( $CFG['auth_type'] == 'optional' && !$auth_success )
	echo "<tr><td colspan=$maxc width=100 bgcolor=#EEFFFF align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#EEFFFF\";'><a href=\"$self?login=1\">$strLogin</a></td></tr>";

echo "</table>\n";

echo "</td><td valign=top>";


echo "<table border=1 cellpadding=1 cellspacing=0>\n";

$row = array( $strNumber, $strPostNumber, $strGroup, $strGroupDescription );

echo "<table border=1 cellspacing=0 cellpadding=2>\n";

table_head( $row, 'head', 'xhead', 25 );

if( $CFG['group_sorting'] )
	ksort( $active );

reset( $active );

$i = 0;

$server = $news_server[$curr_catalog];

while ( list ($group, $value) = each ($active) ) {

	$i++;

	$magic = $value[0];
	$glink = "<a class=sub href=indexing.php?server=$server&group=$group&magic=$magic>$group</a>";

	if( strlen( $value[2] ) > 45 )
		$value[2] = substr( $value[2], 0, 45 ) . ' ..';
	elseif( $value[2] == '' )
		$value[2] = '&nbsp;';
?>
<tr bgcolor=#EEFFFF onMouseover='this.bgColor="#FFFFA0";' onMouseout='this.bgColor="#EEFFFF";'>
  <td class=index align=right><i><? echo $i; ?></i></td>
  <td class=index align=right><font color=#202020><? echo ($value[0]-$value[1]+1); ?></font></td>
  <td class=index><? echo $glink; ?></td>
  <td class=index><? echo $value[2]; ?></td>
</tr>
<?
}

echo "</table>";

echo "</td></tr></table>\n";

echo "</center>\n";

html_foot();

for( $i = 0 ; $i < $catalog_num ; $i++ ) {
	if( $nhd[$news_server[$i]] ) {
		nnrp_close($nhd[$i]);
		$nhd[$news_server[$i]] = 0;
	}
}

html_tail();

?>
