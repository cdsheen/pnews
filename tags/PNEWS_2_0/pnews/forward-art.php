<?

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

include('utils.inc.php');

$title .= " - $$strForwardDetail";

# -------------------------------------------------------------------


$artnum   = $_GET['artnum'];

if( $_POST['content'] != '' ) {

	$server   = $_POST['server'];
	$group    = $_POST['group'];

	if( $post_restriction )
		readonly_error( $server, $group );

	if( verifying( $server, $group ) == false )
		session_error( $server, $group );

	$receiver   = $_POST['receiver'];
	$content    = $_POST['content'];
	$subject    = $_POST['subject'];
	$refid      = $_POST['refid'];

	html_head( "$newsgroup - $subject" );

	$time = strftime("%Y/%m/%d %H:%M:%S");

	echo "<table width=100%><tr><td class=x>";
	echo "<font size=2 color=navy>$strArticleIsForwarded</font>\n";
	echo "</td><td class=x align=right><input class=b type=button onClick=\"close_window();\" value=$strCloseWindow></td></tr></table><hr>\n";

	$content = str_replace( '\\"', '"', $content );
	$content = str_replace( '\\\'', "'", $content );
	$content = str_replace( '\\\\', '\\', $content );
	$subject = str_replace( '\\"', '"', $subject );
	$subject = str_replace( '\\\'', "'", $subject );
	$subject = str_replace( '\\\\', '\\', $subject );
	echo "<table>\n";
	echo "<tr><td align=right>$strReceiver: </td><td><font color=blue>$receiver</font></td></tr>\n";
	echo "<tr><td align=right>$strTime: </td><td><font color=blue>$time</font></td></tr>\n";
	echo "<tr><td align=right>$strSubject: </td><td><font color=blue>" . htmlspecialchars( $subject ) . "</font></td></tr></table>\n";
	echo '<hr><pre><font size=3 color=black>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</font></pre>\n";
	echo "<hr>\n";

	mail( $receiver, $subject, $content, "From: $auth_email\n$mail_add_header" );

	html_delay_close( 2000 );
	html_tail();
}
elseif( $artnum != '' ) {

	$server   = $_GET['server'];
	$group    = $_GET['group'];

	if( $post_restriction )
		readonly_error( $server, $group );

	if( verifying( $server, $group ) == false )
		session_error( $server, $group );

	$nhd = nnrp_open( $server );

	list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

	list( $from, $email, $subject, $date, $msgid, $org ) = nnrp_head( $nhd, $artnum, $article_convert['to'] );

	$subject = 'FW: ' . preg_replace( '/^Re: /i', '', $subject ) ;

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
		if( document.post.receiver.value == "" || ! /^[_.\d\w-]+@([\d\w][\d\w-]+\.)+[\w]{2,3}$/.test(document.post.receiver.value)) {
			alert('<? echo $strPleaseEnterReceiver; ?>');
			document.post.receiver.focus();
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
	echo "<center><table width=100% cellspacing=0 cellpadding=0>\n";
	echo "<tr><td class=x align=right>$strReceiver:</td><td><input name=receiver size=50></td></tr>\n";
	echo "<tr><td class=x align=right>$strGroup:</td><td><input name=showgroup size=40 value=$group disabled></td></tr>\n";
	echo "<tr><td class=x align=right>$strSubject:</td><td><input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=55></td></tr>\n";

	echo "<tr><td class=x align=right>\n";

	echo "$strContent:</td><td align=right>\n";
	echo " <input class=b type=button value=\"$strFormConfirmForward\" onClick='verify();' tabindex=2>\n";
	echo " <input class=b type=button value=\"$strFormCancelForward\" onClick='really();' tabindex=3></td></tr>\n";
	echo "<tr><td colspan=3><textarea name=content class=text wrap=physical tabindex=1>";

	printf("\n\n\n$strForwardFrom\n", "$from ($email)" );
	printf( "$strPostStatus\n\n", $date, $group );
	nnrp_body( $nhd, $artnum, '', "\n", false, false, $article_convert['to'] );
	nnrp_close($nhd);

	echo "\n</textarea>\n";
	echo "</td></tr></table></center>\n";

	echo "<input name=server value=$server type=hidden>";
	echo "<input name=group value=$group type=hidden>";
	echo "<input name=refid type=hidden value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\">\n";

	echo "</form>\n";
	html_focus( 'post', 'content' );
	html_tail();
}


?>
