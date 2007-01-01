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

# -------------------------------------------------------------------

$artnum = intval($_GET['artnum']);

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
	$threadurl = "$urlbase/thread/$reserver/$group/$artnum";
	$idxurl  = "$urlbase/group/$reserver/$group/$artnum";
	$headerurl = $prefix . $artnum . 'h';
}
else {
	$prefix = "read.php?server=$server&group=$group&artnum=";
	$threadurl = "read.php?server=$server&group=$group&show_all=1&artnum=$artnum";
	$idxurl = "indexing.php?server=$server&group=$group&cursor=$artnum";
	$headerurl = $prefix . $artnum . "&header";
}

$nexturl = ($nextnum>0) ? $prefix . $nextnum : '';
$lasturl = ($lastnum>0) ? $prefix . $lastnum : '';

#list( $from, $email, $subject, $date, $msgid, $org )

$thread_all = ( isset($_GET['show_all']) && $_GET['show_all'] == 1 );

$show_mode |= SHOW_HYPER_LINK|SHOW_SIGNATURE|SHOW_NULL_LINE;

if( isset($_GET['header']) && $CFG['show_article_header'] )
	$show_mode |= SHOW_HEADER;
if( $CFG['image_inline'] )
	$show_mode |= IMAGE_INLINE;
if( $CFG['hide_email'] )
	$show_mode |= HIDE_EMAIL;

$dlbase = str_replace( 'https://', 'http://', $urlbase );

$artinfo = $nnrp->head( $artnum, $news_charset[$curr_category], $CFG['time_format'] );

if( !$artinfo ) {
	if( $CFG['url_rewrite'] )
		header( "Location: $urlbase/group/$reserver/$group/" );
	else
		header( "Location: indexing.php?server=$server&group=$group" );
	exit;
}

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

if( strlen( $org ) > $org_limit )
	$org = substr( $org, 0, $org_limit ) . ' ..';

if( $thread_all )
	$subject = preg_replace( '/^((RE|FW):\s*)+/i', '', $subject );

if( $CFG['thread_enable'] ) {
	$thlist = $nnrp->get_thread( $group, $artinfo['subject'] );
	if( count($thlist) < 2 )
		$thread_all = false;
}
html_head( "$subject ($group)" );

$subject = htmlspecialchars( $subject );

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
	echo "<a href=index.php>" . $CFG['banner'] . "</a><br />\n";
else
	echo "<a href=index.php><span class=title>$title</span><br />";
echo "</td>";
echo "</tr></table>\n<center>\n";

echo "<table class=shadow border=0 width=100% cellpadding=0 cellspacing=0>\n";
echo "<tr><td class=bg>\n";
echo "<table width=100% border=0 cellpadding=2 cellspacing=2>";

echo "<tr><td class=group onMouseover='this.className=\"group_hover\";' onMouseout='this.className=\"group\";'>\n";
if( $CFG['url_rewrite'] )
	echo "<a href=group/$reserver/$group class=text>$group</a>";
else
	echo "<a href=indexing.php?server=$server&group=$group class=text>$group</a>";

# dada - do not show group level conversion
#if( $article_convert['to'] )
#	echo ' <font size=-1>(Convert from ' . $article_convert['source'] . ' to ' . $article_convert['result'] . ')</font>';

echo "</td>";

if( !isset($_GET['header']) && $CFG['show_article_header'] && !$thread_all ) {
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

echo "<tr><td class=subject align=left width=70%><a href=\"$uri\">$subject </a></td>\n";
if( $thread_all )
	echo "<td class=server>&nbsp;</td></tr>\n";
else
	echo "<td class=server>$org</td></tr>\n";

if( $thread_all ) {
	foreach( $thlist as $an ) {
		$artinfo = $nnrp->head( $an, $news_charset[$curr_category], $CFG['time_format'] );

		if( !$artinfo )
			continue;

		$artconv = get_conversion( $artinfo['charset'], $curr_charset );

		if( $artconv['to'] ) {
			$from  = $artconv['to']( $artinfo['name'] );
			$email = $artconv['to']( $artinfo['mail'] );
		}
		else {
			$from  = $artinfo['name'];
			$email = $artinfo['mail'];
		}

		$date = $artinfo['date'];

		$hmail = $CFG['hide_email'] ? hide_mail_link( $email ) : "<a href=\"mailto:$email\">$email</a>";

		echo <<<ARTINFO
<tr><td class=author>$from ($hmail)</td>
<td class=date>$date</td></tr>
<tr><td colspan=2 class=content>
<table width=100%><tr><td>\n
ARTINFO;
		$nnrp->show( $an, $at, $show_mode, '', " <br />\n", $artconv['to'],
			$CFG['url_rewrite'] ? "$dlbase/dl/$server/$group/$an/%s"
			:	"$dlbase/download.php?server=$server&group=$group&artnum=$an&type=uuencode&filename=%s" );
		echo "</td><td align=right valign=top>";
		echo "</td></tr></table>";
		echo "<hr />\n";
	}
}
else {
	$hmail = $CFG['hide_email'] ? hide_mail_link( $email ) : "<a href=\"mailto:$email\">$email</a>";

	echo <<<ARTINFO
<tr><td class=author>$from ($hmail)</td>
<td class=date>$date</td></tr>
<tr><td colspan=2 class=content>
<hr /><table width=100%><tr><td>\n
ARTINFO;

	if( $CFG['advertise_article'] )
		echo '<div style="float: right">'.$CFG['advertise_article'].'</div>';
	$nnrp->show( $artnum, $artinfo, $show_mode, '', " <br />\n", $artconv['to'],
		$CFG['url_rewrite'] ? "$dlbase/dl/$server/$group/$artnum/%s"
		: "$dlbase/download.php?server=$server&group=$group&artnum=$artnum&type=uuencode&filename=%s" );

	echo "</td></tr></table>";
}

if( $CFG['thread_enable'] ) {
	if( !$thread_all )
		echo "<hr />\n";
	if( count($thlist) > 1 ) {
		echo "<table border=0 cellpadding=0 cellspacing=0><tr>\n";
		if( $thread_all )
		  echo "<td class=thread_current>#</td>";
		else
		  echo "<td class=thread onClick='window.location=\"$threadurl\"' onMouseover='this.className=\"thread_hover\"' onMouseout='this.className=\"thread\"'>#</td>";
		$i = 0;
		foreach( $thlist as $art ) {
			$i++;
			if( $i > 1 && ($i+1) % 30 == 1 )
				echo "</tr>\n<tr>";
			if( $art == $artnum && !$thread_all )
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

if( !$global_readonly && !$news_readonly[$c] && !$thread_all ) {

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

echo "<a href=\"javascript:myfavor('http://$host$uri', '$subject')\">$pnews_msg[MyFavor]</a>\n";

echo "</td>\n";

echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";

show_language_switch();

echo "</td></tr></table>\n";

echo "</td></tr></table>\n";

echo "</center>";

html_foot(false);

if( $CFG['html_footer'] ) {
	if( preg_match( '/\.php$/', $CFG['html_footer'] ) )
		include( $CFG['html_footer'] );
	else
		readfile( $CFG['html_footer'] );
}

html_tail();

?>
