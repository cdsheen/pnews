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

$nhd = nnrp_open( $server, $news_nntps[$c] );

if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
	connect_error( $server );

list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

if( $CFG['show_newest_top'] ) {
	$nextnum = nnrp_last( $nhd, $artnum );
	$lastnum = nnrp_next( $nhd, $artnum );
}
else {
	$nextnum = nnrp_next( $nhd, $artnum );
	$lastnum = nnrp_last( $nhd, $artnum );
}

if( $CFG['url_rewrite'] ) {
	$nexturl = ($nextnum>0) ? "$urlbase/article/$reserver/$group/$nextnum" : '';
	$lasturl = ($lastnum>0) ? "$urlbase/article/$reserver/$group/$lastnum" : '';
	$idxurl  = "$urlbase/group/$reserver/$group/$artnum";
}
else {
	$nexturl = ($nextnum>0) ? "read-art.php?server=$server&group=$group&artnum=$nextnum" : '';
	$lasturl = ($lastnum>0) ? "read-art.php?server=$server&group=$group&artnum=$lastnum" : '';
	$idxurl = "indexing.php?server=$server&group=$group&cursor=$artnum";
}

#list( $from, $email, $subject, $date, $msgid, $org )

$artinfo = nnrp_head( $nhd, $artnum, $news_charset[$curr_catalog] );

if( !$artinfo ) {
	if( $CFG['show_article_popup'] )
		kill_myself();
	elseif( $CFG['url_rewrite'] )
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
$msgid = $artinfo['msgid'];

if( $CFG['show_article_popup'] )
	html_head( "$subject ($group)", null, 'topmargin=0 leftmargin=0' );
else
	html_head( "$subject ($group)" );

#if( strlen( $subject ) > $subject_limit + 6 )
#	$subject = substr( $subject, 0, $subject_limit + 6 ) . ' ..';
#else
#	$subject = $subject;

$subject = htmlspecialchars( $subject );

#$date = str_replace( ' ', '<br />', $date );

if( strlen( $org ) > $org_limit )
	$org = substr( $org, 0, $org_limit ) . ' ..';

echo "<center>\n";

#if( !$CFG['show_article_popup'] ) {
	echo "<table width=100% border=1 cellpadding=2 cellspacing=0>";

	if( $CFG['show_article_popup'] ) {
		echo "<tr><td class=group>\n";
		echo "$group";
	}
	else {
		echo "<tr><td class=group onMouseover='this.className=\"group_hover\";' onMouseout='this.className=\"group\";'>\n";
		if( $CFG['url_rewrite'] )
			echo "<a href=group/$reserver/$group>$group</a>";
		else
			echo "<a href=indexing.php?server=$server&group=$group>$group</a>";
	}

	echo "</td>";
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	if( $lasturl == '' )
		echo $strLastArticle;
	else
		echo "<a href=\"$lasturl\">$strLastArticle</a>";
	echo "</td>\n";
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	if( $nexturl == '' )
		echo $strNextArticle;
	else
		echo "<a href=\"$nexturl\">$strNextArticle</a>";
	echo "</td>\n";
	echo "</tr></table>\n";
#}

echo "<table width=100% cellpadding=3 cellspacing=0>\n";

echo "<tr><td class=subject align=left><a href=\"$uri\">$subject</a></td>\n";
echo "<td class=date>$date</td></tr>\n";
echo "<tr><td class=author>$from (<a href=\"mailto:$email\">$email</a>)</td>\n";
echo "<td class=server>$org</td></tr>\n";

#toolbar( $server, $group, $artnum, $subject );

echo "<tr><td colspan=2 class=content>";

echo "<hr />";
if( $artconv['to'] )
	nnrp_body( $nhd, $artnum, "", "<br />\n", true, false, $artconv['to'] );
else
	nnrp_body( $nhd, $artnum, "", "<br />\n" );
nnrp_close($nhd);

echo "</td></tr><tr><td align=center colspan=2>\n";


echo "</td></tr>";
echo "</table>";

toolbar( $server, $group, $artnum, $subject );

echo "</center>";
html_foot(false);
html_tail();

function toolbar( $server, $group, $artnum, $title ) {
	global $post_restriction, $email, $auth_email;
	global $strCloseWindow, $strReply;
	global $strCrossPost, $strForward, $strDelete;
	global $strMyFavor, $strReturnToIndexing, $strNextArticle, $strLastArticle;
	global $CFG, $nexturl, $lasturl, $idxurl;
	echo "<table width=100% border=1 cellspacing=0 cellpadding=2>\n";
	if( !$post_restriction ) {
		echo "<tr>";
		if( ! $CFG['show_article_popup'] ) {
			echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
			if( $nexturl == '' )
				echo $strNextArticle;
			else
				echo "<a href=\"$nexturl\">$strNextArticle</a>";
			echo "</td>\n";
			echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
			if( $lasturl == '' )
				echo $strLastArticle;
			else
				echo "<a href=\"$lasturl\">$strLastArticle</a>";
			echo "</td>\n";
			echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
			echo "<a href=\"$idxurl\">$strReturnToIndexing</a>";
			echo "</td>\n";
		}

		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
		echo reply_article( $server, $group, $artnum, $strReply, false, $CFG['show_article_popup'] );
		echo "</td>\n";

		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
		echo xpost_article( $server, $group, $artnum, $strCrossPost, $CFG['show_article_popup'] );
		echo "</td>\n";

		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
		echo forward_article( $server, $group, $artnum, $strForward, $CFG['show_article_popup'] );
		echo "</td>\n";

		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
		if( $email == $auth_email )
			echo delete_article( $server, $group, $artnum, $strDelete, $CFG['show_article_popup'] );
		else
			echo "&nbsp;";
		echo "</td>\n";
	}

	$host = $_SERVER['HTTP_HOST'];
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
	if( strstr( $_SERVER["HTTP_USER_AGENT"], 'MSIE' ) )
		echo "<a href=\"javascript:myfavor('http://$host$uri', '$title')\">$strMyFavor</a>\n";
	else
		echo "&nbsp;";
	echo "</td>\n";

	if( $CFG['show_article_popup'] ) {
		echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
		echo "<a href=\"javascript:close_window()\">$strCloseWindow</a>";
		echo "</td>";
	}
	echo "<td class=action align=center onMouseover='this.className=\"action_hover\";' onMouseout='this.className=\"action\";'>";
#	echo "Language: ";
	show_language_switch();
	echo "</td></tr></table>\n";
}

?>
