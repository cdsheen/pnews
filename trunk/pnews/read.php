<?

# PHP News Reader
# Copyright (C) 2001-2005 Shen Cheng-Da
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

# -------------------------------------------------------------------

$artnum = $_GET['artnum'];

#if( isset( $_GET['orig'] ) )
#	$newwin = ($_GET['orig']==0);
#else
#	$newwin = true;

if( $server == $group_default_server )
	$reserver = '';
else
	$reserver = $server;

$c = check_group( $server, $group );

if( ! ( $nnrp->open( $server, $news_nntps[$c] ) && nnrp_authenticate() ) )
	connect_error( $server );

list( $code, $count, $lowmark, $highmark ) = $nnrp->group( $group );

$nextnum = $nnrp->prev( $artnum );
$lastnum = $nnrp->next( $artnum );

if( $CFG['url_rewrite'] ) {
	$prefix = "$urlbase/article/$reserver/$group/";
	$idxurl  = "$urlbase/group/$reserver/$group/$artnum";
	$headerurl = $prefix . $artnum . 'h';
}
else {
	$prefix = "read.php?server=$server&group=$group&artnum=";
	$idxurl = "indexing.php?server=$server&group=$group&cursor=$artnum";
	$headerurl = $prefix . $artnum . "&header";
}

$nexturl = ($nextnum>0) ? $prefix . $nextnum : '';
$lasturl = ($lastnum>0) ? $prefix . $lastnum : '';

#list( $from, $email, $subject, $date, $msgid, $org )

if( isset($_GET['show_all']) ) {
}
else {
}

$artinfo = $nnrp->head( $artnum, $news_charset[$curr_category], $CFG['time_format'] );

if( !$artinfo ) {
	if( $CFG['url_rewrite'] )
		header( "Location: $urlbase/group/$reserver/$group/" );
	else
		header( "Location: indexing.php?server=$server&group=$group" );
	exit;
}

$show_mode |= SHOW_HYPER_LINK|SHOW_SIGNATURE|SHOW_NULL_LINE;

if( isset($_GET['header']) && $CFG['show_article_header'] )
	$show_mode |= SHOW_HEADER;
if( $CFG['image_inline'] )
	$show_mode |= IMAGE_INLINE;
if( $CFG['hide_email'] )
	$show_mode |= HIDE_EMAIL;

$dlbase = str_replace( 'https://', 'http://', $urlbase );

#if( $curr_charset == 'big5' || $curr_charset == 'gb2312' )
#	$artinfo['charset'] = $curr_charset;

$artconv = get_conversion( $artinfo['charset'], $curr_charset );

if( $artconv['to'] ) {
	$from  = $artconv['to']( $artinfo['name'] );
	$email = $artconv['to']( $artinfo['mail'] );
	$subject = $artconv['to']( $artinfo['subject'] );
	$org = $artconv['to']( $artinfo['org'] );
}
else {
	$from  = $artinfo['name'];
	$email = $artinfo['mail'];
	$subject = $artinfo['subject'];
	$org = $artinfo['org'];
}
$date = $artinfo['date'];
$msgid = $artinfo['msgid'];
if( strlen( $org ) > $org_limit )
	$org = substr( $org, 0, $org_limit ) . ' ..';

html_head( "$subject ($group)" );

$subject = htmlspecialchars( $subject );

echo "<center>\n";

echo "<table class=shadow border=0 width=100% cellpadding=0 cellspacing=0>\n";
echo "<tr><td class=bg>\n";
echo "<table width=100% border=0 cellpadding=2 cellspacing=2>";

echo "<tr><td class=group onMouseover='this.className=\"group_hover\";' onMouseout='this.className=\"group\";'>\n";
if( $CFG['url_rewrite'] )
	echo "<a href=group/$reserver/$group>$group</a>";
else
	echo "<a href=indexing.php?server=$server&group=$group>$group</a>";

# dada - do not show group level conversion
#if( $article_convert['to'] )
#	echo ' <font size=-1>(Convert from ' . $article_convert['source'] . ' to ' . $article_convert['result'] . ')</font>';

echo "</td>";

if( !isset($_GET['header']) && $CFG['show_article_header'] ) {
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	echo "<a href=\"$headerurl\">$pnews_msg[ShowHeader]</a>";
	echo "</td>\n";
}

echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";

if( $lasturl == '' )
	echo $pnews_msg['LastArticle'];
else
	echo "<a href=\"$lasturl\">$pnews_msg[LastArticle]</a>";

echo "</td>\n";

echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";

if( $nexturl == '' )
	echo $pnews_msg['NextArticle'];
else
	echo "<a href=\"$nexturl\">$pnews_msg[NextArticle]</a>";

echo "</td>\n";
echo "</tr></table>\n";

echo "<table class=fixed width=100% cellpadding=3 cellspacing=0>\n";

echo <<<ARTINFO
<tr><td class=subject align=left width=70%><a href="$uri">$subject </a></td>
<td class=server>$org</td></tr>\n
ARTINFO;

if( $CFG['thread_enable'] ) {
	$thlist = $nnrp->get_thread( $group, $artinfo['subject'] );
}

if( $CFG['hide_email'] )
	$hmail = hide_mail_link( $email );
else
	$hmail = "<a href=\"mailto:$email\">$email</a>";

echo <<<ARTINFO
<tr><td class=author>$from ($hmail)</td>
<td class=date>$date</td></tr>
<tr><td colspan=2 class=content>
<hr />\n
ARTINFO;

if( $CFG['url_rewrite'] )
	$nnrp->show( $artnum, $artinfo, $show_mode, '', " <br />\n", $artconv['to'], "$dlbase/dl/$server/$group/$artnum/%s" );
else
	$nnrp->show( $artnum, $artinfo, $show_mode, '', " <br />\n", $artconv['to'], "$dlbase/download.php?server=$server&group=$group&artnum=$artnum&type=uuencode&filename=%s" );

if( $CFG['thread_enable'] ) {
	if( count($thlist) > 1 ) {
		echo "<hr /><table border=0 cellpadding=0 cellspacing=0><tr>\n";
		$i = 0;
		foreach( $thlist as $art ) {
			$i++;
			if( $i > 1 && $i % 30 == 1 )
				echo "</tr>\n<tr>";
			if( $art == $artnum )
				echo "<td class=thread_current>$i</td>";
			else
				echo "<td class=thread onClick='window.location=\"$prefix$art\"' onMouseover='this.className=\"thread_hover\"' onMouseout='this.className=\"thread\"'>$i</td>";
		}
		echo "</tr></table>";
	}
}

$nnrp->close();

echo "</td></tr>";

echo "</table>";

toolbar( $server, $group, $c, $artnum, $subject );

echo "</td></tr></table>\n";

echo "</center>";
html_foot(false);
html_tail();

function toolbar( $server, $group, $c, $artnum, $title ) {
	global $global_readonly, $email, $auth_email, $news_readonly;
	global $pnews_msg, $auth_success, $uri;
	global $CFG, $nexturl, $lasturl, $idxurl;
	echo "<table width=100% border=0 cellspacing=2 cellpadding=2>\n";
	echo "<tr>\n";

	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	if( $nexturl == '' )
		echo $pnews_msg['NextArticle'];
	else
		echo "<a href=\"$nexturl\">$pnews_msg[NextArticle]</a>";
	echo "</td>\n";
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	if( $lasturl == '' )
		echo $pnews_msg['LastArticle'];
	else
		echo "<a href=\"$lasturl\">$pnews_msg[LastArticle]</a>";
	echo "</td>\n";
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	echo "<a href=\"$idxurl\">$pnews_msg[ReturnToIndexing]</a>";
	echo "</td>\n";

	if( !$global_readonly && !$news_readonly[$c] ) {

		if( !$auth_success && $CFG['auth_prompt'] == 'other' ) {
			echo "<td class=action align=center>$pnews_msg[Reply]</td>\n";
			echo "<td class=action align=center>$pnews_msg[CrossPost]</td>\n";
			echo "<td class=action align=center>$pnews_msg[Forward]</td>\n";
		}
		else {
			echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
			echo reply_article( $server, $group, $artnum, $pnews_msg['Reply'], false );
			echo "</td>\n";

			echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
			echo xpost_article( $server, $group, $artnum, $pnews_msg['CrossPost'] );
			echo "</td>\n";

			echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
			echo forward_article( $server, $group, $artnum, $pnews_msg['Forward'] );
			echo "</td>\n";
		}

		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
		if( $email == $auth_email ) {
			if( !$auth_success && $CFG['auth_prompt'] == 'other' )
				echo $pnews_msg['Delete'];
			else
				echo delete_article( $server, $group, $artnum, $pnews_msg['Delete'] );
		}
		else
			echo "&nbsp;";
		echo "</td>\n";
	}
	else {
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>&nbsp;</td>\n";
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>&nbsp;</td>\n";
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>&nbsp;</td>\n";
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>&nbsp;</td>\n";
	}

	$host = $_SERVER['HTTP_HOST'];
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";

	echo "<a href=\"javascript:myfavor('http://$host$uri', '$title')\">$pnews_msg[MyFavor]</a>\n";

	echo "</td>\n";

	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";

	show_language_switch();
	echo "</td></tr></table>\n";
}

?>
