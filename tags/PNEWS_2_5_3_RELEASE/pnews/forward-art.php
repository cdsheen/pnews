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

$title .= " - $strForward";

# -------------------------------------------------------------------


$artnum   = $_GET['artnum'];

if( $_POST['content'] != '' ) {

	$server   = $_POST['server'];
	$group    = $_POST['group'];

	$c = check_group( $server, $group );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	$receiver   = $_POST['receiver'];
#	$refid      = $_POST['refid'];

	if( $auto_slash ) {
		$content  = stripslashes($_POST['content']);
		$subject  = stripslashes($_POST['subject']);
	}
	else {
		$content  = $_POST['content'];
		$subject  = $_POST['subject'];
	}

	html_head( "$group - $subject" );

	$time = strftime($CFG['time_format']);

	echo "<table width=100%><tr><td class=status>$strArticleIsForwarded</td>\n";
	echo "<td class=field><input class=normal type=button onClick=\"close_window();\" value=\"$strCloseWindow\"></td></tr></table><hr />\n";

	$artconv = get_conversion( $_POST['charset'], $curr_charset );

	echo "<table>\n";
	echo "<tr><td class=field>$strReceiver: </td><td class=value>$receiver</td></tr>\n";
	echo "<tr><td class=field>$strTime: </td><td class=value>$time</td></tr>\n";
	echo "<tr><td class=field>$strSubject: </td><td class=value>" . htmlspecialchars( $subject ) . "</td></tr></table>\n";
	echo '<hr />
	<pre class=content>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</pre>\n";
	echo "<hr />\n";

	$mime_headers = "Mime-Version: 1.0\nContent-Type: text/plain; charset=\"" . $_POST['charset'] . "\"\nContent-Transfer-Encoding: 8bit\n";

	if( $artconv['back'] )
		mail( $receiver, $artconv['back']($subject), $artconv['back']($content), "From: $auth_email\n$mail_add_header\n$mime_headers" );
	else
		mail( $receiver, $subject, $content, "From: $auth_email\n$mail_add_header\n$mime_headers" );

	html_delay_close( 2000 );
	html_tail();
}
elseif( $artnum != '' ) {

#	$server   = $_GET['server'];
#	$group    = $_GET['group'];

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

	$subject = 'FW: ' . preg_replace( '/^Re: /i', '', $subject ) ;

	html_head( "$group - $subject" );
?>
<script language="javascript">
	function really() {
		if( document.post.content.value == "" ) {
			window.close();
			return(true);
		}
		if( confirm('<? echo addslashes($strRealyQuit); ?>') ) {
			window.close();
			return(true);
		}
		return(false);
	}
	function verify() {
		if( document.post.receiver.value == "" || ! /^[_.\d\w-]+@([\d\w][\d\w-]+\.)+[\w]{2,3}$/.test(document.post.receiver.value)) {
			alert('<? echo addslashes($strPleaseEnterReceiver); ?>');
			document.post.receiver.focus();
			return(false);
		}
		if( document.post.subject.value == "" ) {
			alert('<? echo addslashes($strPleaseEnterSubject); ?>');
			document.post.subject.focus();
			return(false);
		}
		if( document.post.content.value == "" ) {
			alert('<? echo addslashes($strPleaseEnterContent); ?>');
			document.post.content.focus();
			return(false);
		}
		document.post.submit();
		return(true);
	}
</script>
<?
	echo "<form name=post action=\"$self\" method=post>\n";
	echo "<center><table width=100% cellspacing=0 cellpadding=0>\n";
	echo "<tr><td class=field>$strReceiver:</td><td><input name=receiver size=50></td></tr>\n";
	echo "<tr><td class=field>$strGroup:</td><td><input name=showgroup size=40 value=\"$group\" disabled></td></tr>\n";
	echo "<tr><td class=field>$strSubject:</td><td><input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=55></td></tr>\n";

	echo "<tr><td class=field>\n";
	echo "<input name=charset value=\"" . $artinfo['charset'] . "\" type=hidden>";

	echo "$strContent:</td><td align=right>\n";
	echo " <input class=normal type=button value=\"$strFormConfirmForward\" onClick='verify();' tabindex=2>\n";
	echo " <input class=normal type=button value=\"$strFormCancelForward\" onClick='really();' tabindex=3></td></tr>\n";
	echo "<tr><td colspan=3><textarea name=content class=content wrap=physical tabindex=1 cols=82>";

	printf("\n\n\n$strForwardFrom\n", "$from ($email)" );
	printf( "$strPostStatus\n\n", $date, $group );
	$show_mode |= SHOW_SIGNATURE|SHOW_NULL_LINE;
	nnrp_show( $nhd, $artnum, $artinfo, $show_mode, '', "\n", $article_convert['to'] );
	nnrp_close($nhd);

	echo "\n</textarea>\n";
	echo "</td></tr></table></center>\n";

	echo "<input name=server value=\"$server\" type=hidden>\n";
	echo "<input name=group value=\"$group\" type=hidden>\n";
#	echo "<input name=refid type=hidden value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\">\n";

	echo "</form>\n";
	html_focus( 'post', 'content' );
	html_tail();
}


?>
