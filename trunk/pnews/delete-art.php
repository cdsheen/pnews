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

include('utils.inc.php');

# -------------------------------------------------------------------

$artnum  = $_GET['artnum'];
$confirm = $_POST['confirm'];

if( $confirm == 1 ) {

	$server = $_POST['server'];
	$group  = $_POST['group'];

	$c = check_group( $server, $group );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	$msgid   = $_POST['msgid'];

	if( $auto_slash )
		$content  = stripslashes($_POST['content']);
	else
		$subject  = $_POST['subject'];

	$artconv = get_conversion( $_POST['charset'], $curr_charset );

	$nhd = nnrp_open( $server, $news_nntps[$c] );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
		connect_error( $server );

	if( $artconv['back'] )
		nnrp_cancel( $nhd, $artconv['back']($auth_user), $auth_email, $msgid, $group, $artconv['back']($subject) );
	else
		nnrp_cancel( $nhd, $auth_user, $auth_email, $msgid, $group, $subject );
	nnrp_close($nhd);

	html_head( $pnews_str['Delete'] );

	$subject = htmlspecialchars( $subject );

	echo <<<EOT
<table width=100%>
<tr><td class=status>$pnews_str[ArticleIsDeleted]</td>
    <td class=field><input class=normal type=button onClick="close_window()" value="$pnews_str[CloseWindow]"></td>
</tr>
</table>
<hr />
<table>
<tr><td class=field>$pnews_str[Author]: </td><td class=value>$auth_user ($auth_email)</td></tr>
<tr><td class=field>$pnews_str[Subject]: </td><td class=value>$subject</td></tr>
<tr><td class=field>$pnews_str[Group]: </td><td class=value>$group</td></tr>
</table>
<hr />

EOT;

	html_delay_close( 2000 );

	html_tail();
}
elseif( $artnum != '' ) {

#	$server = $_GET['server'];
#	$group  = $_GET['group'];

	$c = check_group( $server, $group );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	$nhd = nnrp_open( $server, $news_nntps[$c] );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
		connect_error( $server );

	list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

	$artinfo = nnrp_head( $nhd, $artnum, $news_charset[$curr_catalog], $CFG['time_format'] );

	if( !$artinfo )
		kill_myself();

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

	html_head( "$pnews_str[Delete] - $subject" );

	echo $pnews_str['RealyDelete'] ;

	echo "<hr />";

	echo "<form style='margin-top: 0' name=post action=\"$self\" method=post>";
	echo "<center><table cellpadding=0 cellspacing=0 width=100%>\n";
	echo "<tr><td class=field>$pnews_str[Name]:</td><td><input name=nickname size=20 value=\"$auth_user\" disabled></td></tr>\n";
	echo "<tr><td class=field>$pnews_str[Email]:</td><td><input name=email size=40 value=\"$auth_email\" disabled></td></tr>\n";
	echo "<tr><td class=field>$pnews_str[Group]:</td><td><input size=40 value=\"$group\" disabled></td></tr>\n";
	echo "<tr><td class=field>$pnews_str[Subject]:</td><td><input value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=60 disabled></td></tr>\n";

	echo "<tr><td class=field>\n";
	echo "<input name=confirm value=1 type=hidden>\n";
	echo "<input name=msgid value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\" type=hidden>\n";
	echo "<input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" type=hidden>\n";
	echo "<input name=server value=\"$server\" type=hidden>\n";
	echo "<input name=group value=\"$group\" type=hidden>\n";
	echo "<input name=charset value=\"" . $artinfo['charset'] . "\" type=hidden>";

	echo "$pnews_str[Content]:</td><td align=right>";
	echo " <input class=normal type=submit value='$pnews_str[FormConfirmDelete]'>\n";
	echo " <input class=normal type=button value='$pnews_str[FormCancelDelete]' onClick='close_window();'></td></tr>\n";
	echo "<tr><td colspan=2>";
	echo "<textarea name=content class=content rows=10 wrap=hard disabled cols=82>";
	$show_mode |= SHOW_SIGNATURE|SHOW_NULL_LINE;
	nnrp_show( $nhd, $artnum, $artinfo, $show_mode, '', "\n", $article_convert['to'] );
	nnrp_close($nhd);
	echo "\n</textarea></td></tr>\n";
	echo "</table></center>\n";
	echo "</form>\n";

	html_tail();
}


?>
