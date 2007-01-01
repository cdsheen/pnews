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

include('utils.inc.php');

# ---------------------------------------------------------------------

html_head( $group );

echo "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td>";

if( $CFG['advertise_banner'] )
	echo '<div style="float: right">'.$CFG['advertise_banner'].'</div>';

if( $CFG['html_header'] ) {
	if( preg_match( '/\.php$/', $CFG['html_header'] ) )
		include( $CFG['html_header'] );
	else
		readfile( $CFG['html_header'] );
}
elseif( $CFG['banner'] )
	echo "<a href=index.php>" . $CFG['banner'] . "</a>\n";
else
	echo "<a href=index.php><span class=title>$title</span></a>\n";
echo "</td>";

echo "</td></tr></table>\n<center>";

$c = check_group( $server, $group );

if( ! ( $nnrp->open( $server, $news_nntps[$c] ) && nnrp_authenticate() ) )
	connect_error( $server );

list( $code, $count, $lowmark, $highmark ) = $nnrp->group( $group );

$artlist = $nnrp->article_list( $lowmark, $highmark );

$artsize = count($artlist);

if( $artsize > 0 ) {
	$highmark = $artlist[$artsize-1];
	$lowmark  = $artlist[0];
}

echo "\n<!-- ART. NO. FROM: $lowmark  TO: $highmark  (count: $artsize)-->\n";

$artsppg = $CFG['articles_per_page'];

$totalpg = ceil($artsize / $artsppg) ;

if( !isset($_GET['cursor']) ) {
	if( isset( $_GET['page'] ) ) {
		$cpg = $_GET['page'];
		$tmpidx = $artsize - ($cpg-1)*$artsppg - 1;
		$cursor = $artlist[$tmpidx];
	}
	else
		$cursor = $highmark;
}
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
	echo "<a href=group/$reserver/$group class=text>$group</a>";
else
	echo "<a href=indexing.php?server=$server&group=$group class=text>$group</a>";

if( $article_convert['to'] )
	echo ' <font size=-1>(Convert from ' . $article_convert['source'] . ' to ' . $article_convert['result'] . ')</font>';

echo "</td>\n";


if( $CFG['url_rewrite'] ) {
	if( $auth_success )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"$urlbase/logout\">$pnews_msg[Logout]</a></td>\n";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"$urlbase/login\">$pnews_msg[Login]</a></td>\n";
}
else {
	if( $auth_success )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"auth.php?logout=1\">$pnews_msg[Logout]</a></td>\n";
	elseif( $CFG['auth_type'] == 'optional' )
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'><a href=\"auth.php?login=1\">$pnews_msg[Login]</a></td>\n";
}

echo <<<EOH
        <td class=action align=center onMouseover='this.className="action_hover";' onMouseout='this.className="action";'>
           <a href=index.php>$pnews_msg[ReturnToGroupList]</a></td>
    </tr>
    </table>
    <table width=100% border=0 cellpadding=1 cellspacing=1>

EOH;

echo <<<EOR
    <tr class=header height=25>
        <td align=right width=32pt>$pnews_msg[Number]</td>
        <td width=70%>$pnews_msg[Subject]</td>
        <td align=center width=120pt>$pnews_msg[Author]</td>
        <td align=center width=110pt>$pnews_msg[Time]</td>
    </tr>

EOR;

$ncount = 0;
$curlist = array();

echo "<!-- cursor = $cursor   lowmark = $lowmark -->\n";

$higher = $lower = '';

$i = $cursor;
while( $i >= $lowmark ) {
	$cut_end = array_search( $i, $artlist );
	if( $cut_end !== false ) {
		echo "<!-- found $i at $cut_end -->\n";
		if( $artsize <= $artsppg ) {
			$cut_from = 0;
			$cut_end = $artsize-1;
		}
		else {
			$cut_from = $cut_end - $artsppg + 1;
			if( $cut_from < 0 )
				$cut_from = 0;
		}
		$ncount = $cut_end - $cut_from + 1;
		for( $j = $cut_end ; $j >= $cut_from ; $j-- )
			$curlist[] = $artlist[$j];
		if( $cut_from > 0 )
			$lower = $artlist[$cut_from-1];
		else
			$lower = $lowmark;
		if( $cut_end + $artsppg + 1 < $artsize )
			$higher = $artlist[$cut_end+$artsppg];
		else
			$higher = $highmark;
		break;
	}
	$i--;
	if( $i < $cursor - 1000 )
		break;
}

if( $ncount > 0 ) {
	$show_from = $curlist[$ncount - 1];
	$show_end  = $curlist[0];
	echo "<!-- XOVER: $show_from-$show_end -->\n";
	$xover = $nnrp->xover( $show_from, $show_end );

	$ncount = count($xover);
}

if( $ncount == 0 ) {
	echo "<tr><td colspan=4 class=empty_group height=50>$pnews_msg[NoArticle]</td></tr>\n";
	$show_from = $show_end = $cursor;
}
else {
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
			$subject = $pnews_msg['NoSubject'];

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
		if( $CFG['article_numbering_reverse'] )
			$artidx = $artnum - $lowmark + 1;
		else
			$artidx = $highmark - $artnum + 1;
		if( $CFG['hide_email'] )
			$hmail = hide_mail_link( $email, "$nick " );
		else
			$hmail = "<a href=\"mailto:$email\">$nick</a>";
		echo <<<ROW
<tr class=list onMouseover='this.className="list_hover";' onMouseout='this.className="list";'>
  <td align=right><i>$artidx</i></td>
  <td>$readlink</td>
  <td>$hmail</td>
  <td align=center><font face=serif>$datestr</font></td>
</tr>

ROW;

	}

}

echo "</table>\n";


echo "<!-- Total arts: $artsize   Arts/page: $artsppg   Total pages: $totalpg -->\n";

if( $totalpg == 1 )
	$page = 1;
else {
	$page = floor( ( $artsize - array_search( $show_from, $artlist ) + 1 ) / $artsppg);
	if( $page == 1 && $show_end < $highmark )
		$page = 2;
	elseif( $page == $totalpg && $show_from > $lowmark )
		$page = $totalpg - 1;
	elseif( $page > $totalpg || $show_from == $lowmark )
		$page = $totalpg;
}

echo "<!-- SHOW NO. FROM: $show_from  TO: $show_end -->\n";

echo "<table width=100% border=0 cellpadding=2 cellspacing=1>";

echo "<tr><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

if( $show_end < $highmark ) {
	if( $CFG['url_rewrite'] )
		echo "<a href=\"group/$reserver/$group\">$pnews_msg[FirstPage]</a>";
	else
		echo "<a href=\"$self?server=$server&group=$group\">$pnews_msg[FirstPage]</a>";
	echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

	if( $CFG['url_rewrite'] )
		echo "<a href=\"group/$reserver/$group/$higher\">$pnews_msg[PreviousPage]</a>";
	else
		echo "<a href=\"$self?server=$server&group=$group&cursor=$higher\">$pnews_msg[PreviousPage]</a>";
}
else {
	echo $pnews_msg['FirstPage'];
	echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
	echo "<font color=gray>$pnews_msg[PreviousPage]</font>";
}

echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

if( $show_from > $lowmark ) {
	if( $CFG['url_rewrite'] )
		echo "<a href=\"group/$reserver/$group/$lower\">$pnews_msg[NextPage]</a>";
	else
		echo "<a href=\"$self?server=$server&group=$group&cursor=$lower\">$pnews_msg[NextPage]</a>";

	echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";

	$target = isset($artlist[$artsppg-1])?$artlist[$artsppg-1]:$artlist[$artsize-1];

	if( $CFG['url_rewrite'] )
		echo "<a href=\"group/$reserver/$group/$target\">$pnews_msg[LastPage]</a>";
	else
		echo "<a href=\"$self?server=$server&group=$group&cursor=$target\">$pnews_msg[LastPage]</a>";
}
else {
	echo "<font color=gray>$pnews_msg[NextPage]</font>";
	echo "</td><td width=10% class=page align=center onMouseover='this.className=\"page_hover\";' onMouseout='this.className=\"page\";'>\n";
	echo $pnews_msg['LastPage'];
#		$totalpg = $page;
}

$pg_str = sprintf( "<form style='display: inline;' name=select><select name=pgidx class=pgidx onLoad='initPage(document.select.pgidx)' onChange='changePage(document.select.pgidx)'></select></form>" );
$pg_str = sprintf( $pnews_msg['PageNumber'], $pg_str, $totalpg );

echo <<<PG
</td><td class=page align=center onMouseover='this.className="page_hover";' onMouseout='this.className="page";'>
<script type="text/javascript">
function initPage(pgidx) {
	pgidx.length = $totalpg;
	for( i = 0 ; i < $totalpg; i++ ) {
		pg = i+1;
		pgidx.options[i].text = pg;
		pgidx.options[i].value = pg;
	}
	pgidx.selectedIndex = $page-1;
}
PG;
if( $CFG['url_rewrite'] )
	$pageurl = $CFG['url_base'] . "group/$reserver/$group/p";
else
	$pageurl = "$self?server=$server&group=$group&page=";
?>

function changePage(pgidx) {
	window.location = '<? echo $pageurl; ?>' + pgidx.options[pgidx.selectedIndex].value;
}
</script>
<? echo $pg_str; ?>
</td>
<?
echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>\n";
if( !$global_readonly && !$news_readonly[$c] ) {
	if( !$auth_success && $CFG['auth_prompt'] == 'other' )
		echo $pnews_msg['Post'];
	else
		echo post_article( $server, $group, $pnews_msg['Post'] );
}
else
	echo '&nbsp;';
echo "</td>";
echo <<<EOT
<script type="text/javascript">
  initPage(document.select.pgidx);
</script>
    <td class=action align=center onMouseover='this.className="action_hover";' onMouseout='this.className="action";'>
      <a href="javascript:reload()">$pnews_msg[Refresh]</a>
    </td>
    </tr></table>
</td></tr>
</table>
</center>

EOT;

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
