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

$title .= " - $strCrossPostDetail";

# -------------------------------------------------------------------

$server     = $_POST['server'];
$content    = $_POST['content'];
$postgroup  = $_POST['postgroup'];
$artnum     = $_GET['artnum'];

if( $content != '' && $postgroup != '' ) {

	if( $post_restriction )
		readonly_error( $server, $postgroup );

	$server = $_POST['server'];
	$group  = $_POST['group'];

	if( verifying( $server, $group ) == -1 )
		session_error();

	$nickname   = $_POST['nickname'];
	$email      = $_POST['email'];
	$content    = $_POST['content'];
	$subject    = $_POST['subject'];
	$refid      = $_POST['refid'];
#	$authormail = $_POST['authormail'];

	$artconv = get_conversion( $_POST['charset'], $curr_charset );

	$nhd = nnrp_open( $server );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) ) {
		html_head('ERROR');
		echo "<p><font size=3>$strConnectServerError - " . $server . "</font><br>\n";
		html_foot();
		html_tail();
		exit;
	}

	if( $artconv['back'] ) {
		nnrp_post_begin( $nhd, $artconv['back']($nickname), $email, $artconv['back']($subject), $postgroup, $artconv['back']($organization), $refid, $auth_email, $_POST['charset'] );
		nnrp_post_write( $nhd, $article_convert['back']($content) );
	}
	else {
		nnrp_post_begin( $nhd, $nickname, $email, $subject, $postgroup, $organization, $refid, $auth_email, $_POST['charset'] );
		nnrp_post_write( $nhd, $content );
	}

	nnrp_post_finish( $nhd );
	nnrp_close($nhd);

	html_head( "$postgroup - $subject" );

	$content = str_replace( '\\"', '"', $content );
	$content = str_replace( '\\\'', "'", $content );
	$content = str_replace( '\\\\', '\\', $content );
	$subject = str_replace( '\\"', '"', $subject );
	$subject = str_replace( '\\\'', "'", $subject );
	$subject = str_replace( '\\\\', '\\', $subject );

	$time = strftime($CFG['time_format']);

	echo "<table width=100%><tr><td class=x>";
	echo "<font size=2 color=navy>$strArticlePosted</font>\n";
	echo "</td><td class=x align=right><input class=b type=button onClick=\"close_window();\" value=$strCloseWindow></td></tr></table><hr>\n<table>\n";
	echo "<tr><td align=right>$strAuthor: </td><td><font color=blue>$nickname ($email)</font></td></tr>\n";
	echo "<tr><td align=right>$strTime: </td><td><font color=blue>$time</font></td></tr>\n";
	echo "<tr><td align=right>$strSubject: </td><td><font color=blue>" . htmlspecialchars( $subject ) . "</font></td></tr>\n";
	echo "<tr><td align=right>$strGroup: </td><td><font color=blue>$postgroup</font></td></tr></table><hr>\n";
	echo '<pre><font size=3 color=black>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</font></pre>\n";
	echo "<hr>\n";

	html_delay_close( 2000 );
	html_tail();
}
elseif( $artnum != '' ) {

	$server = $_GET['server'];
	$group  = $_GET['group'];

	if( verifying( $server, $group ) == -1 )
		session_error( $server, $group );

	$nhd = nnrp_open( $server );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) ) {
		html_head('ERROR');
		echo "<p><font size=3>$strConnectServerError - " . $server . "</font><br>\n";
		html_foot();
		html_tail();
		exit;
	}

	list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

	$artinfo = nnrp_head( $nhd, $artnum, $news_charset[$curr_catalog] );

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
		if( confirm('<? echo $strRealyQuit; ?>') ) {
			window.close();
			return(true);
		}
		return(false);
	}
	function verify() {
		if( document.post.nickname.value == "" ) {
			alert('<? echo $strPleaseEnterName; ?>');
			document.post.nickname.focus();
			return(false);
		}
		if( document.post.email.value == "" || ! /^[_.\d\w-]+@([\d\w][\d\w-]+\.)+[\w]{2,3}$/.test(document.post.email.value) ) {
			alert('<? echo $strPleaseEnterEmail; ?>');
			document.post.email.focus();
			return(false);
		}
		if( document.post.postgroup.value == "" ) {
			alert('<? echo $strPleaseEnterGroup; ?>');
			document.post.postgroup.focus();
			return(false);
		}
		if( document.post.subject.value == "" ) {
			alert('<? echo $strPleaseEnterSubject; ?>');
			document.post.subject.focus();
			return(false);
		}
		if( document.post.content.value == "" ) {
			alert('<? echo $strPleaseEnterContent; ?>');
			document.post.content.focus();
			return(false);
		}
		document.post.submit();
		return(true);
	}
</script>
<?
	echo "<form name=post action=$self method=post>\n";
	echo "<center><table cellspacing=0 cellpadding=0 width=100%>\n";
	echo "<tr><td class=x align=right>$strName:</td><td><input name=nickname size=20 value=\"$auth_user\"></td></tr>\n";
	echo "<tr><td class=x align=right>$strEmail:</td><td><input name=email size=40 value=\"$auth_email\"></td></tr>\n";
	echo "<tr><td class=x align=right>$strGroup:</td><td><input name=postgroup size=40 value=\"\"></td></tr>\n";
	echo "<tr><td class=x align=right>$strSubject:</td><td><input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=60></td></tr>\n";

	echo "<tr><td class=x align=right>\n";
	echo "<input name=authormail value=\"$email\" type=hidden>\n";
	echo "<input name=server value=\"$server\" type=hidden>\n";
	echo "<input name=group value=\"$group\" type=hidden>\n";
	echo "<input name=refid type=hidden value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\">\n";
	echo "<input name=charset value=\"" . $artinfo['charset'] . "\" type=hidden>";

	echo "$strContent:</td><td align=right>";
	echo " <input class=b type=button value='$strFormConfirmPost' onClick='verify()' tabindex=2>\n";
	echo " <input class=b type=button value='$strFormCancelPost' onClick='really()' tabindex=3></td></tr>\n";
	echo "<tr><td class=x colspan=2>";
	echo "<textarea name=content class=text wrap=physical tabindex=1>";

	printf("\n$strCrossPostAuthor\n", "$from ($email)" );
	printf("$strPostStatus\n\n", $date, $group );
#	echo "-------------------------------------------------------------\n";

	nnrp_body( $nhd, $artnum, "", "\n", false, false, $article_convert['to'] );
	nnrp_close($nhd);

	echo "\n</textarea>\n";
	echo "</td></tr></table></center>\n";
	echo "</form>\n";
	html_focus( 'post', 'content' );
	html_tail();
}


?>
