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

echo "<center>\n";

if( $CFG['banner'] )
	echo "<a href=index.php>" . $CFG['banner'] . "</a><p>\n";

$nhd = nnrp_open( $server );

if( ! ( $nhd && nnrp_authenticate( $nhd ) ) ) {
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

#if( $CFG['article_order_reverse'] )

if( $forward )
	$xover = nnrp_xover_limit( $nhd, $cursor, $lineppg, $highmark );
else
	$xover = nnrp_xover_limit( $nhd, $cursor, $lineppg, $lowmark, false );

$ncount = sizeof($xover);

$show_from = $xover[0][0];
$show_end  = $xover[$ncount-1][0];

if( $totalpg == 1 )
	$page = 1;
elseif( $CFG['article_order_reverse'] ) {
	$page = floor(($highmark - $show_from+1) / $lineppg);
	if( $page == 1 && $show_end < $highmark )
		$page = 2;
	elseif( $page == $totalpg && $show_from > $lowmark )
		$page = $totalpg - 1;
}
else {
	$page = floor(($show_end - $lowmark+1) / $lineppg);
	if( $page == 1 && $show_from > $lowmark )
		$page = 2;
	elseif( $page == $totalpg && $show_end < $highmark )
		$page = $totalpg - 1;
}

echo "<!-- SHOW NO. FROM: $show_from  TO: $show_end -->\n";

echo <<<EOH
<table border=1 cellpadding=0 cellspacing=0 width=100%>
<tr><td>
    <table width=100% border=1 cellpadding=2 cellspacing=0>
    <tr><td bgcolor=#DDFFDD onMouseOver='this.bgColor="#FFFF80";' onMouseout='this.bgColor="#DDFFDD";'>

EOH;

if( $server == $group_default_server )
	$reserver = '';
else
	$reserver = $server;


if( $CFG['url_rewrite'] )
	echo "<font size=3 face=Georgia><a href=group/$reserver/$group><i><b>$group</i></b></a></font>";
else
	echo "<font size=3 face=Georgia><a href=indexing.php?server=$server&group=$group><i><b>$group</i></b></a></font>";

echo "</td>";


if( $CFG['url_rewrite'] ) {
	if( $auth_success )
		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"$urlbase/logout\">$strLogout</a></td>";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"$urlbase/login\">$strLogin</a></td>";
}
else {
	if( $auth_success )
		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"auth.php?logout=1\">$strLogout</a></td>";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"auth.php?login=1\">$strLogin</a></td>";
}

echo <<<EOH
        <td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor="#FFFFC0";' onMouseout='this.bgColor="#FFDDEE";'>
           <a href=index.php>$strReturnToGroupList</a></td>
    </tr>
    </table>
    <table width=100% border=1 cellpadding=1 cellspacing=0>

EOH;


#$row = array( ':>:' . $strNumber, $strSubject, ':c2:' . $strAuthor, $strTime, $page_action );

$span = $ncount+1;
echo <<<EOR
    <tr class=head height=25>
        <td class=xhead align=right width=32pt>$strNumber</td>
        <td class=xhead>$strSubject</td>
        <td class=xhead align=center width=120pt>$strAuthor</td>
        <td class=xhead align=center width=100pt>$strTime</td>
    </tr>

EOR;

#table_head( $row, 'head', 'xhead', 25 );

#echo "<tr class=head height=25><td class=xhead align=right>$strNumber</td><td class=xhead>$strSubject</td><td class=xhead colspan=2>$strAuthor</td><td class=xhead>$strTime</td></tr>\n";
# $n, $rows, $oddcolor, $evencolor, $selectcolor, $tdclass, $height = 0

if( $ncount == 0 ) {
	echo "<tr class=a><td colspan=4 class=x height=50>$strNoArticle</td></tr>\n";
}
else {

$i = ( $CFG['article_order_reverse'] ) ? $ncount - 1 : 0 ;

for( ; ; ) {
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
  <td class=index align=right><i><? echo $xover[$i][0]-$lowmark+1; ?></i></td>
  <td class=index>
  <? echo read_article( $server, $group, $xover[$i][0], $subject, false, 'sub' ); ?>
  </td>
  <td class=index title="<? echo $email; ?>"><a href="mailto:<? echo $email; ?>"><? echo $nick; ?></a></td>
  <td class=index align=center><font face=serif><? echo $datestr; ?></font></td>
</tr>
<?
	if( $CFG['article_order_reverse'] ) {
		$i--;
		if( $i < 0 ) break;
	}
	else {
		$i++;
		if( $i >= $ncount ) break;
	}
}

}

echo "</table>";
echo "<table width=100% border=1 cellpadding=2 cellspacing=0>";

echo "<tr><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>\n";

if( $CFG["article_order_reverse"] )
	if( $show_end < $highmark ) {
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group\">$strFirstPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group\">$strFirstPage</a>";
		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

		$target = $show_end + 1 ;

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${target}r\">$strPreviousPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target&forward=1\">$strPreviousPage</a>";
	}
	else {
		echo $strFirstPage;
		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";
		echo "<font color=gray>$strPreviousPage</font>";
	}
else
	if( $show_from > $lowmark ) {
		$target = $show_from - 1;

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${lowmark}r\">$strFirstPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$lowmark&forward=1\">$strFirstPage</a>";

		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$target\">$strPreviousPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$strPreviousPage</a>";
	}
	else {
		echo $strFirstPage;
		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";
		echo "<font color=gray>$strPreviousPage</font>";
	}

echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

if( $CFG["article_order_reverse"] )
	if( $show_from > $lowmark ) {
		$target = $show_from - 1;
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$target\">$strNextPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$strNextPage</a>";

		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${lowmark}r\">$strLastPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$lowmark&forward=1\">$strLastPage</a>";
	}
	else {
		echo "<font color=gray>$strNextPage</font>";
		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";
		echo $strLastPage;

		$totalpg = $page;
	}
else
	if( $show_end < $highmark ) {
		$target = $show_end + 1 ;

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${target}r\">$strNextPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target&forward=1\">$strNextPage</a>";

		echo "</td><td width=10% bgcolor=#DDFFDD align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#DDFFDD\";'>";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group\">$strLastPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group\">$strLastPage</a>";
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

echo <<<EOT
    <td width=10% bgcolor=#FFDDEE align=center onMouseover='this.bgColor="#FFFFC0";' onMouseout='this.bgColor="#FFDDEE";'>
      <a href="javascript:reload()">$strRefresh</a>
    </td>
    </tr></table>
</td></tr>
</table>
</center>

EOT;

html_foot();

nnrp_close($nhd);

html_tail();

?>
