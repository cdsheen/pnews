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

include('utils.inc.php');

# ---------------------------------------------------------------------


html_head( $group );

echo "<center>";

if( $CFG['banner'] )
	echo "<a href=index.php>" . $CFG['banner'] . "</a><p>\n";

$nhd = nnrp_open( $server );

if( ! $nhd ) {
	echo "<p><font size=3>$strConnectServerError - " . $server . "</font><br>\n";
	html_foot();
	html_tail();
	exit;
}

list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

echo "\n<!-- ART. NO. FROM: $lowmark  TO: $highmark -->\n";

$totalpst = $highmark - $lowmark + 1 ;
$totalpg = ceil($totalpst / $lineppg) ;

if( !isset($_GET['cursor']) )
	$cursor = $highmark;
else
	$cursor = $_GET['cursor'];

$forward = isset($_GET['forward']);

echo "<!-- cursor: $cursor    lineppg: $lineppg -->\n";

if( $forward )
	$xover = nnrp_xover_limit( $nhd, $cursor, $lineppg, $highmark );
else
	$xover = nnrp_xover_limit( $nhd, $cursor, $lineppg, $lowmark, false );

$ncount = sizeof($xover);

$show_from = $xover[0][0];
$show_end  = $xover[$ncount-1][0];

$page = floor(($highmark - $show_from+1) / $lineppg);

if( $page == 1 && $show_end < $highmark )
	$page = 2;
elseif( $page == $totalpg && $show_from > $lowmark )
	$page = $totalpg - 1;

/*
	$page = $_GET['page'];

	if( !isset($_GET['page']) || $page < 1 || $page > $totalpg )
		$page = 1;

	$prev_pg = $page - 1;
	$next_pg = $page + 1;

	if( $page == $totalpg ) {
		$show_from = $lowmark;
		$show_end = $show_from + ( ( $totalpst > $lineppg ) ? ($lineppg-1) : $totalpst );
	}
	else {
		$show_from = $highmark - $lineppg*$page + 1 ;
		$show_end  = $show_from + $lineppg - 1;
	}

	if( $show_from < $lowmark )
		$show_from = $lowmark;

#	$xover = nnrp_xover( $nhd, $show_from, $show_end );
	$xover = nnrp_xover_limit( $nhd, $show_from, 20, $highmark );

	$ncount = sizeof($xover);
*/

echo "<!-- SHOW NO. FROM: $show_from  TO: $show_end -->\n";

echo "<table border=1 cellpadding=0 cellspacing=0 width=100%><tr><td>";

echo "<table width=100% border=1 cellpadding=2 cellspacing=0>";
echo "<tr><td bgcolor=#DDFFDD onMouseOver='this.bgColor=\"#FFFF80\";' onMouseout='this.bgColor=\"#DDFFDD\";'>\n";

if( $CFG['url_rewrite'] )
	echo "<font size=3 face=Georgia><a href=group/$server/$group><i><b>$group</i></b></a></font>";
else
	echo "<font size=3 face=Georgia><a href=indexing.php?server=$server&group=$group><i><b>$group</i></b></a></font>";

echo "</td>";

$uri = str_replace( '&login=1', '', $uri );
$uri = str_replace( '&logout=1', '', $uri );

if( $auth_success )
	echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"$uri&logout=1\">$strLogout</a></td>";
elseif( $CFG['auth_type'] == 'optional' )
	echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"$uri&login=1\">$strLogin</a></td>";
echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=index.php>$strReturnToGroupList</a></td>";
echo "</tr></table>\n";

#echo "</td></tr><tr><td align=center>\n";

echo "<table width=100% border=1 cellpadding=1 cellspacing=0>\n";

#$row = array( ':>:' . $strNumber, $strSubject, ':c2:' . $strAuthor, $strTime, $page_action );

$span = $ncount+1;
echo "
<tr class=head height=25>
  <td class=xhead align=right width=32pt>$strNumber</td>
  <td class=xhead>$strSubject</td>
  <td class=xhead align=center width=120pt>$strAuthor</td>
  <td class=xhead align=center width=100pt>$strTime</td>
</tr>
";

#table_head( $row, 'head', 'xhead', 25 );

#echo "<tr class=head height=25><td class=xhead align=right>$strNumber</td><td class=xhead>$strSubject</td><td class=xhead colspan=2>$strAuthor</td><td class=xhead>$strTime</td></tr>\n";
# $n, $rows, $oddcolor, $evencolor, $selectcolor, $tdclass, $height = 0

if( $ncount == 0 ) {
	echo "<tr class=a><td colspan=4 class=x height=50>$strNoArticle</td></tr>\n";
}
for( $i = $ncount-1 ; $i >= 0 ; $i-- ) {
	if( strlen( $xover[$i][1] ) > $subject_limit )
		$subject = substr( $xover[$i][1], 0, $subject_limit ) . ' ..';
	else
		$subject = $xover[$i][1];

	if( $article_convert['to'] ) {
		$subject = $article_convert['to']( $subject );
		$xover[$i][2] = $article_convert['to']( $xover[$i][2] );
	}

	if( trim($subject) == '' )
		$subject = $strNoSubject;

	$subject = htmlspecialchars( $subject );

	if( strlen( $xover[$i][2] ) > $nick_limit )
		$nick = substr( $xover[$i][2], 0, $nick_limit ) . ' ..';
	else
		$nick = $xover[$i][2];

	$nick = trim($nick);

	if( $nick == '' ) {
		$id = strtok( $xover[$i][5], '@.' );

		if( strlen( $id ) > $id_limit )
			$id = substr( $id, 0, $id_limit ) . ' ..';
		elseif( $id == '' )
			$id = '&lt;author&gt;';
		$nick = $id;
	}
	$email = trim($xover[$i][5]);
	$pos = strrpos( $xover[$i][3] , ':' );
	$datestr = substr( $xover[$i][3], 0, $pos);
#	$onclick = "onClick='javascript:read_article( \"$server\", \"$group\", " . $xover[$i][0] . ");'";
?>
<tr bgcolor=#EEFFFF onMouseover='this.bgColor="#FFFFA0";' onMouseout='this.bgColor="#EEFFFF";'>
  <td class=index align=right><i>
<?
	if( $CFG['url_rewrite'] )
		echo "<a href=\"article/$server/$group/" . $xover[$i][0] . '">' . ( $xover[$i][0]-$lowmark+1 ) . '</a>';
	else
		echo $xover[$i][0]-$lowmark+1; ?>
</i></td>
  <td class=index>
  <? echo read_article( $server, $group, $xover[$i][0], $subject, false, 'sub' ); ?>
  </td>
  <td class=index title="<? echo $email; ?>"><a href=mailto:<? echo $email; ?>><? echo $nick; ?></a></td>
  <td class=index align=right><font face=serif><? echo $datestr; ?></font></td>
</tr>
<?

}
echo "</table>";
echo "<table width=100% border=1 cellpadding=2 cellspacing=0>";

echo "<tr><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>\n";

if( $show_end < $highmark ) {
	if( $CFG['url_rewrite'] )
		echo "<a href=group/$server/$group>$strFirstPage</a>";
	else
		echo "<a href=$self?server=$server&group=$group>$strFirstPage</a>";
	echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

	$target = $show_end + 1 ;

	if( $CFG['url_rewrite'] )
		echo "<a href=group/$server/$group/$target>$strFirstPage</a>";
	else
		echo "<a href=$self?server=$server&group=$group&cursor=$target&forward=1>$strPreviousPage</a>";
}
else {
	echo $strFirstPage;
	echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";
	echo "<font color=gray>$strPreviousPage</font>";
}

echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

if( $show_from > $lowmark ) {
	$target = $show_from - 1;
	if( $CFG['url_rewrite'] )
		echo "<a href=group/$server/$group/$target>$strNextPage</a>";
	else
		echo "<a href=$self?server=$server&group=$group&cursor=$target>$strNextPage</a>";

	echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

	if( $CFG['url_rewrite'] )
		echo "<a href=group/$server/$group/$lowmark>$strLastPage</a>";
	else
		echo "<a href=$self?server=$server&group=$group&cursor=$lowmark&forward=1>$strLastPage</a>";
}
else {
	echo "<font color=gray>$strNextPage</font>";
	echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";
	echo $strLastPage;

	$totalpg = $page;
}

echo "</td>";

echo "<td bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";
printf( $strPageNumber, $page, $totalpg );
echo "</td>\n";

echo "<td width=10% bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
if( !$post_restriction ) {
	echo post_article( $server, $group, $strPost );
}
else
	echo '&nbsp;';
echo "</td>";

echo "<td width=10% bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
echo '<a href="javascript:reload()">' . $strRefresh . '</a>';

echo "</td>";
echo "</tr></table>\n";

echo "</td></tr></table>";

echo "</center>\n";

html_foot();

nnrp_close($nhd);

html_tail();

?>
