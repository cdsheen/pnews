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

$c = check_group( $server, $group );

$nhd = nnrp_open( $server, $news_nntps[$c] );

if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
	connect_error( $server );

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

if( $forward ) {
	$limit = $cursor + $lineppg * 3;
	if( $limit > $highmark ) $limit = $highmark;
	$xover = nnrp_xover_limit( $nhd, $cursor, $lineppg, $limit );
}
else {
	$limit = $cursor - $lineppg * 3;
	if( $limit < $lowmark ) $limit = $lowmark;
	$xover = nnrp_xover_limit( $nhd, $cursor, $lineppg, $limit, false );
}

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
    <tr><td class=group onMouseOver='this.className="group_hover";' onMouseout='this.className="group";'>

EOH;

if( $server == $group_default_server )
	$reserver = '';
else
	$reserver = $server;


if( $CFG['url_rewrite'] )
	echo "<a href=group/$reserver/$group>$group</a>";
else
	echo "<a href=indexing.php?server=$server&group=$group>$group</a>";

echo "</td>";


if( $CFG['url_rewrite'] ) {
	if( $auth_success )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"$urlbase/logout\">$strLogout</a></td>";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td class=action align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'><a href=\"$urlbase/login\">$strLogin</a></td>";
}
else {
	if( $auth_success )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"auth.php?logout=1\">$strLogout</a></td>";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"auth.php?login=1\">$strLogin</a></td>";
}

echo <<<EOH
        <td class=action align=center onMouseover='this.className="action_hover";' onMouseout='this.className="action";'>
           <a href=index.php>$strReturnToGroupList</a></td>
    </tr>
    </table>
    <table width=100% border=1 cellpadding=1 cellspacing=0>

EOH;

echo <<<EOR
    <tr class=header height=25>
        <td align=right width=32pt>$strNumber</td>
        <td width=70%>$strSubject</td>
        <td align=center width=120pt>$strAuthor</td>
        <td align=center width=110pt>$strTime</td>
    </tr>

EOR;

if( $ncount == 0 ) {
	echo "<tr><td colspan=4 class=empty_group height=50>$strNoArticle</td></tr>\n";
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

	$artnum = $xover[$i][0] - $lowmark + 1 ;
	$readlink = read_article( $server, $group, $xover[$i][0], $subject, false, 'sub' );
	echo <<<ROW
<tr class=list onMouseover='this.className="list_hover";' onMouseout='this.className="list";'>
  <td align=right><i>$artnum</i></td>
  <td>$readlink</td>
  <td title="$email"><a href="mailto:$email">$nick</a></td>
  <td align=center><font face=serif>$datestr</font></td>
</tr>

ROW;

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

echo "<tr><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

if( $CFG["article_order_reverse"] )
	if( $show_end < $highmark ) {
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group\">$strFirstPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group\">$strFirstPage</a>";
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		$target = $show_end + 1 ;

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${target}r\">$strPreviousPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target&forward=1\">$strPreviousPage</a>";
	}
	else {
		echo $strFirstPage;
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
		echo "<font color=gray>$strPreviousPage</font>";
	}
else
	if( $show_from > $lowmark ) {
		$target = $show_from - 1;

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${lowmark}r\">$strFirstPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$lowmark&forward=1\">$strFirstPage</a>";

		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$target\">$strPreviousPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$strPreviousPage</a>";
	}
	else {
		echo $strFirstPage;
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
		echo "<font color=gray>$strPreviousPage</font>";
	}

echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

if( $CFG["article_order_reverse"] )
	if( $show_from > $lowmark ) {
		$target = $show_from - 1;
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$target\">$strNextPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$strNextPage</a>";

		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/${lowmark}r\">$strLastPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$lowmark&forward=1\">$strLastPage</a>";
	}
	else {
		echo "<font color=gray>$strNextPage</font>";
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
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

		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group\">$strLastPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group\">$strLastPage</a>";
	}
	else {
		echo "<font color=gray>$strNextPage</font>";
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
		echo $strLastPage;

		$totalpg = $page;
	}

echo "</td><td class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
printf( $strPageNumber, $page, $totalpg );
echo "</td>\n";

echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>\n";
if( !$post_restriction ) {
	echo post_article( $server, $group, $strPost );
}
else
	echo '&nbsp;';
echo "</td>";

echo <<<EOT
    <td class=action align=center onMouseover='this.className="action_hover";' onMouseout='this.className="action";'>
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
