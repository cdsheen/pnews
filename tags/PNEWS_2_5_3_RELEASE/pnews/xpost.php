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

$title .= " - $strCrossPost";

# -------------------------------------------------------------------

$server     = $_POST['server'];
$content    = $_POST['content'];
$postgroup  = $_POST['postgroup'];
$artnum     = $_GET['artnum'];

if( $content != '' && $postgroup != '' ) {

	$c = check_group( $server, $postgroup );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $postgroup );

	$group  = $_POST['group'];

	check_group( $server, $group );

	$nickname   = $_POST['nickname'];
	$content    = $_POST['content'];
	$subject    = $_POST['subject'];
#	$refid      = $_POST['refid'];

	if( $CFG['email_editing'] )
		$email = $_POST['email'];
	else
		$email = $auth_email;

	if( $auto_slash ) {
		$subject  = stripslashes( $_POST['subject'] );
		$content  = stripslashes( $_POST['content'] );
		$nickname = stripslashes( $_POST['nickname'] );
	}
	else {
		$subject  = $_POST['subject'];
		$content  = $_POST['content'];
		$nickname = $_POST['nickname'];
	}

	$artconv = get_conversion( $_POST['charset'], $curr_charset );

	$nhd = nnrp_open( $server, $news_nntps[$c] );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
		connect_error( $server );

	if( $artconv['back'] ) {
		nnrp_post_begin( $nhd, $artconv['back']($nickname), $email, $artconv['back']($subject), $postgroup, $artconv['back']($CFG['organization']), null, $auth_email, $_POST['charset'] );
		nnrp_post_write( $nhd, $article_convert['back']($content) );
	}
	else {
		nnrp_post_begin( $nhd, $nickname, $email, $subject, $postgroup, $CFG['organization'], null, $auth_email, $_POST['charset'] );
		nnrp_post_write( $nhd, $content );
	}

	nnrp_post_finish( $nhd );
	nnrp_close($nhd);


	html_head( "$postgroup - $subject" );

	$time = strftime($CFG['time_format']);
	$subject = htmlspecialchars( $subject );
	echo <<<EOT
<table width=100%>
 <tr><td class=status>$strArticlePosted</td>
 <td class=field><input class=normal type=button onClick="close_window();" value="$strCloseWindow"></td></tr>
</table>
<hr />
<table>
 <tr><td class=field>$strAuthor: </td><td class=value>$nickname ($email)</td></tr>
 <tr><td class=field>$strTime: </td><td class=value>$time</td></tr>
 <tr><td class=field>$strSubject: </td><td class=value>$subject</td></tr>
 <tr><td class=field>$strGroup: </td><td class=value>$postgroup</td></tr>
</table>
<hr />

EOT;
	echo '<pre class=content>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</pre>\n";
	echo "<hr />\n";

	html_delay_close( 2000 );
	html_tail();
}
elseif( $artnum != '' ) {

	$server = $_GET['server'];
#	$group  = $_GET['group'];

	$c = check_group( $server, $group );

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

#	if( !preg_match( '/^Re: /i', $subject ) )
#		$subject = 'Re: ' . $subject ;

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
		if( document.post.nickname.value == "" ) {
			alert('<? echo addslashes($strPleaseEnterName); ?>');
			document.post.nickname.focus();
			return(false);
		}
		if( document.post.email.value == "" || ! /^[_.\d\w-]+@([\d\w][\d\w-]+\.)+[\w]{2,3}$/.test(document.post.email.value) ) {
			alert('<? echo addslashes($strPleaseEnterEmail); ?>');
			document.post.email.focus();
			return(false);
		}
		if( document.post.postgroup.value == "" ) {
			alert('<? echo addslashes($strPleaseEnterGroup); ?>');
			document.post.postgroup.focus();
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
	$mail_disable = $CFG['email_editing'] ? '' : ' disabled';

	echo "<form name=post action=\"$self\" method=post>\n";
	echo "<center><table cellspacing=0 cellpadding=0 width=100%>\n";
	echo "<tr><td class=field>$strName:</td><td><input name=nickname size=20 value=\"$auth_user\"></td></tr>\n";
	echo "<tr><td class=field>$strEmail:</td><td><input name=email size=40 value=\"$auth_email\" $mail_disable></td></tr>\n";
	echo "<tr><td class=field>$strGroup:</td><td><input name=postgroup size=40 value=\"\"></td></tr>\n";
	echo "<tr><td class=field>$strSubject:</td><td><input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=60></td></tr>\n";

	echo "<tr><td class=field>\n";
	echo "<input name=authormail value=\"$email\" type=hidden>\n";
	echo "<input name=server value=\"$server\" type=hidden>\n";
	echo "<input name=group value=\"$group\" type=hidden>\n";
#	echo "<input name=refid type=hidden value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\">\n";
	echo "<input name=charset value=\"" . $artinfo['charset'] . "\" type=hidden>";

	echo "$strContent:</td><td align=right>";
	echo " <input class=normal type=button value='$strFormConfirmPost' onClick='verify()' tabindex=2>\n";
	echo " <input class=normal type=button value='$strFormCancelPost' onClick='really()' tabindex=3></td></tr>\n";
	echo "<tr><td colspan=2>";
	echo "<textarea name=content class=content wrap=physical tabindex=1 cols=82>";

	printf("\n$strCrossPostAuthor\n", "$from ($email)" );
	printf("$strPostStatus\n\n", $date, $group );
#	echo "-------------------------------------------------------------\n";

	$show_mode |= SHOW_SIGNATURE|SHOW_NULL_LINE;
	nnrp_show( $nhd, $artnum, $artinfo, $show_mode, '', "\n", $article_convert['to'] );
	nnrp_close($nhd);

	echo "\n</textarea>\n";
	echo "</td></tr></table></center>\n";
	echo "</form>\n";
	html_focus( 'post', 'content' );
	html_tail();
}


?>
