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

if( $CFG['cache_dir'] )
	$artlist = nnrp_article_list( $nhd, $lowmark, $highmark, $CFG['cache_dir'] . "/$server-$group" );
else
	$artlist = nnrp_article_list( $nhd, $lowmark, $highmark );

$artsize = count($artlist);

if( $artsize > 0 ) {
	$highmark = $artlist[$artsize-1];
	$lowmark  = $artlist[0];
}

echo "\n<!-- ART. NO. FROM: $lowmark  TO: $highmark  (count: $artsize)-->\n";

$artsppg = $CFG['articles_per_page'];

if( !isset($_GET['cursor']) )
	$cursor = $highmark;
else
	$cursor = $_GET['cursor'];

echo <<<EOH
<table border=1 class=shadow cellpadding=0 cellspacing=0 width=100%>
<tr><td class=bg>
    <table width=100% border=0 cellpadding=2 cellspacing=1>
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

echo "</td>\n";


if( $CFG['url_rewrite'] ) {
	if( $auth_success )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"$urlbase/logout\">$strLogout</a></td>\n";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"$urlbase/login\">$strLogin</a></td>\n";
}
else {
	if( $auth_success )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"auth.php?logout=1\">$strLogout</a></td>\n";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"auth.php?login=1\">$strLogin</a></td>\n";
}

echo <<<EOH
        <td class=action align=center onMouseover='this.className="action_hover";' onMouseout='this.className="action";'>
           <a href=index.php>$strReturnToGroupList</a></td>
    </tr>
    </table>
    <table width=100% border=0 cellpadding=1 cellspacing=1>

EOH;

echo <<<EOR
    <tr class=header height=25>
        <td align=right width=32pt>$strNumber</td>
        <td width=70%>$strSubject</td>
        <td align=center width=120pt>$strAuthor</td>
        <td align=center width=110pt>$strTime</td>
    </tr>

EOR;

$ncount = 0;
$curlist = array();

for( $i = $cursor ; $i >= $lowmark && $ncount < $artsppg ; $i-- ) {
	if( in_array( $i, $artlist ) ) {
		$curlist[] = $i;
		$ncount++;
	}
}

$lower = $curlist[$ncount-1];

for( $i = $lower-1 ; $i >= $lowmark ; $i-- ) {
	if( in_array( $i, $artlist ) ) {
		$lower = $i;
		break;
	}
}

$higher = $curlist[0];

$s = 0;
for( $i = $higher+1 ; $i <= $highmark && $s < $artsppg ; $i++ ) {
	if( in_array( $i, $artlist ) ) {
		$higher = $i;
		$s++;
	}
}

if( !$CFG['show_newest_top'] ) {
	sort($curlist);
}

if( $ncount == 0 ) {
	echo "<tr><td colspan=4 class=empty_group height=50>$strNoArticle</td></tr>\n";
	$show_from = $show_end = $cursor;
}
else {
	if( $CFG['show_newest_top'] ) {
		$show_from = $curlist[$ncount - 1];
		$show_end  = $curlist[0];
	}
	else {
		$show_from = $curlist[0];
		$show_end  = $curlist[$ncount - 1];
	}

	$xover = nnrp_xover( $nhd, $show_from, $show_end );

	if( $CFG['show_newest_top'] )
		krsort($xover);

	foreach( $xover as $artnum => $ov ) {

		if( !$ov )
			continue;
		if( strlen( $ov[0] ) > $subject_limit )
			$subject = substr( $ov[0], 0, $subject_limit ) . ' ..';
		else
			$subject = $ov[0];

		if( $article_convert['to'] ) {
			$subject = $article_convert['to']( $subject );
			$ov[1] = $article_convert['to']( $ov[1] );
		}

		if( trim($subject) == '' )
			$subject = $strNoSubject;

		$subject = htmlspecialchars( $subject );

		if( strlen( $ov[1] ) > $nick_limit )
			$nick = substr( $ov[1], 0, $nick_limit ) . ' ..';
		else
			$nick = $ov[1];

		$nick = trim($nick);

		if( $nick == '' ) {
			$id = strtok( $ov[3], '@.' );

			if( strlen( $id ) > $id_limit )
				$id = substr( $id, 0, $id_limit ) . ' ..';
			elseif( $id == '' )
				$id = '&lt;author&gt;';
			$nick = $id;
		}
		$email = trim($ov[3]);
		$datestr = strftime( $CFG['time_format'], $ov[2] );

		$readlink = read_article( $server, $group, $artnum, $subject, false, 'sub' );

		$artidx = $artnum - $lowmark + 1;
		echo <<<ROW
<tr class=list onMouseover='this.className="list_hover";' onMouseout='this.className="list";'>
  <td align=right><i>$artidx</i></td>
  <td>$readlink</td>
  <td title="$email"><a href="mailto:$email">$nick</a></td>
  <td align=center><font face=serif>$datestr</font></td>
</tr>

ROW;

	}

}

echo "</table>\n";

$totalpg = ceil($artsize / $artsppg) ;

echo "<!-- Total arts: $artsize   Arts/page: $artsppg   Total pages: $totalpg -->\n";

if( $totalpg == 1 )
	$page = 1;
elseif( $CFG['show_newest_top'] ) {
	$page = floor( ( $artsize - array_search( $show_from, $artlist ) + 1 ) / $artsppg);
#	echo "<!-- show_newest_top: $page -->\n";
	if( $page == 1 && $show_end < $highmark )
		$page = 2;
	elseif( $page == $totalpg && $show_from > $lowmark )
		$page = $totalpg - 1;
	elseif( $page > $totalpg || $show_from == $lowmark )
		$page = $totalpg;
}
else {
	$page = floor( (array_search( $show_end, $artlist ) + $artsppg -1 ) / $artsppg);

	if( $page == 1 && $show_from > $lowmark )
		$page = 2;
	elseif( $page == $totalpg && $show_end < $highmark )
		$page = $totalpg - 1;
	elseif( $page > $totalpg || $show_end == $highmark )
		$page = $totalpg;
}

echo "<!-- SHOW NO. FROM: $show_from  TO: $show_end -->\n";

echo "<table width=100% border=0 cellpadding=2 cellspacing=1>";

echo "<tr><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

if( $CFG['show_newest_top'] )
	if( $show_end < $highmark ) {
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group\">$strFirstPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group\">$strFirstPage</a>";
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$higher\">$strPreviousPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$higher\">$strPreviousPage</a>";
	}
	else {
		echo $strFirstPage;
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
		echo "<font color=gray>$strPreviousPage</font>";
	}
else
	if( $show_from > $lowmark ) {

		$target = isset($artlist[$artsppg-1])?$artlist[$artsppg-1]:$artlist[$artsize-1];

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$target\">$strFirstPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$strFirstPage</a>";

		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$lower\">$strPreviousPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$lower\">$strPreviousPage</a>";
	}
	else {
		echo $strFirstPage;
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
		echo "<font color=gray>$strPreviousPage</font>";
	}

echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

if( $CFG['show_newest_top'] )
	if( $show_from > $lowmark ) {
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$lower\">$strNextPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$lower\">$strNextPage</a>";

		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

		$target = isset($artlist[$artsppg-1])?$artlist[$artsppg-1]:$artlist[$artsize-1];

		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$target\">$strLastPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$strLastPage</a>";
	}
	else {
		echo "<font color=gray>$strNextPage</font>";
		echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
		echo $strLastPage;

#		$totalpg = $page;
	}
else
	if( $show_end < $highmark ) {
		if( $CFG['url_rewrite'] )
			echo "<a href=\"group/$reserver/$group/$higher\">$strNextPage</a>";
		else
			echo "<a href=\"$self?server=$server&group=$group&cursor=$higher\">$strNextPage</a>";

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

#		$totalpg = $page;
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
