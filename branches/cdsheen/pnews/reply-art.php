<?

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

include('utils.inc.php');

$title .= " - $strReplyDetail";

# -------------------------------------------------------------------

$artnum   = $_GET['artnum'];

if( $_POST['content'] != '' ) {

	$server   = $_POST['server'];
	$group    = $_POST['group'];

	if( verifying( $server, $group ) == false )
		session_error( $server, $group );

	if( $post_restriction )
		readonly_error( $server, $group );

	$nickname   = $_POST['nickname'];
	$email      = $_POST['email'];
	$content    = $_POST['content'];
	$subject    = $_POST['subject'];
	$refid      = $_POST['refid'];
	$authormail = $_POST['authormail'];

	$nhd = nnrp_open( $server );

	if( ! $onlymail ) {
		if( $article_convert['back'] ) {
			nnrp_post_begin( $nhd, $article_convert['back']($nickname), $email, $article_convert['back']($subject), $group, $article_convert['back']($organization), $refid, $auth_email );
			nnrp_post_write( $nhd, $article_convert['back']($content) );
		}
		else {
			nnrp_post_begin( $nhd, $nickname, $email, $subject, $group, $organization, $refid, $auth_email );
			nnrp_post_write( $nhd, $content );
		}
		if( $CFG['post_signature'] )
			nnrp_post_write( $nhd, $CFG['post_signature'] );
		nnrp_post_finish( $nhd );
		nnrp_close($nhd);
	}

	html_head( "$newsgroup - $subject" );

	$content = str_replace( '\\"', '"', $content );
	$content = str_replace( '\\\'', "'", $content );
	$content = str_replace( '\\\\', '\\', $content );
	$subject = str_replace( '\\"', '"', $subject );
	$subject = str_replace( '\\\'', "'", $subject );
	$subject = str_replace( '\\\\', '\\', $subject );

	$time = strftime("%Y/%m/%d %H:%M:%S");

	echo "<table width=100%><tr><td class=x>";
	echo "<font size=2 color=navy>$strArticlePosted</font>\n";
	echo "</td><td class=x align=right><input class=b type=button onClick=\"close_window()\" value=$strCloseWindow></td></tr></table><hr>\n";
	echo "<table>";
	echo "<tr><td align=right>$strAuthor: </td><td><font color=blue>$nickname ($email)</font></td></tr>\n";
	echo "<tr><td align=right>$strTime: </td><td><font color=blue>$time</font></td></tr>\n";
	echo "<tr><td align=right>$strSubject: </td><td><font color=blue>" . htmlspecialchars( $subject ) . "</font></td></tr>\n";
	echo "<tr><td align=right>$strGroup: </td><td><font color=blue>$group</font></td></tr></table>\n<hr>\n";
	echo '<pre><font size=3 color=black>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</font></pre>\n";
	echo "<hr>\n";

	if( !$post_restriction && $replymail ) {
		if( $article_convert['back'] )
			mail( $authormail, $article_convert['back']($subject), $article_convert['back']($content), "From: $email\n$mail_add_header" );
		else
			mail( $authormail, $subject, $content, "From: $email\n$mail_add_header" );
	}
	html_delay_close( 2000 );
	html_tail();
}
elseif( $artnum != '' ) {

	$server   = $_GET['server'];
	$group    = $_GET['group'];

#	echo "server[$server] group[$group]<br>\n";

	if( verifying( $server, $group ) == false )
		session_error( $server, $group );

	if( $post_restriction )
		readonly_error( $server, $group );

	$nhd = nnrp_open( $server );

	list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

	list( $from, $email, $subject, $date, $msgid, $org ) = nnrp_head( $nhd, $artnum, $article_convert['to'] );

	if( !preg_match( '/^Re: /i', $subject ) )
		$subject = 'Re: ' . $subject ;

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
	function check_reply() {
		if( document.post.onlymail.checked && !document.post.replymail.checked ) {
			if( confirm('<? echo $strNoPostJustMail ?>') ) {
				document.post.replymail.checked = true;
			}
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
#	$subject = str_replace( '"', '\"', $subject );
	echo "<form name=post action=$self method=post>\n";
	echo "<center><table width=100% border=0 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td class=x align=right>$strName:</td><td><input name=nickname size=20 value=\"$auth_user\"></td>\n";
	echo " <td><input name=replymail type=checkbox>$strReplyToAuthor</td></tr>\n";
	echo "<tr><td class=x align=right>$strEmail:</td><td><input name=email size=40 value=\"$auth_email\"></td>\n";
	echo " <td><input name=onlymail type=checkbox onClick='check_reply();'>$strNotPostToGroup</td></tr>\n";
	echo "<tr><td class=x align=right>$strGroup:</td><td><input name=showgroup size=40 value=\"$group\" disabled></td>\n";
	echo " <td><input class=b type=button value=$strFormConfirmPost onClick='verify()' tabindex=2>\n";
	echo " <input class=b type=button value=$strFormCancelPost onClick='really()' tabindex=3></td></tr>\n";
	echo "<tr><td class=x align=right>$strSubject:</td><td colspan=2><input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=60></td></tr>\n";
#	echo "</table>\n<table>\n";
	echo "<input name=authormail value=\"$email\" type=hidden>";
	echo "<input name=server value=\"$server\" type=hidden>";
	echo "<input name=group value=\"$group\" type=hidden>\n";
	echo "<input name=refid type=hidden value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\">\n";
?>
<script language=javascript>
	function InsertQuote() {
		document.post.content.value = document.post.quote.value + "\n" + document.post.content.value;
	}
</script>
<?
	echo "<tr><td class=x align=right>\n";
	echo "$strContent:</td>";
#	echo "<td calss=x colspan=2 align=right><span class=link onClick='InsertQuote();'>$strFormInsertQuote</span></td></tr>\n";
	echo "<td calss=x colspan=2 align=right><input type=button onClick='InsertQuote();' value=\"$strFormInsertQuote\"></td></tr>\n";
	echo "<tr><td class=x colspan=3>";
	echo "<textarea name=content class=text wrap=physical tabindex=1>";
	echo "\n</textarea>\n";
	echo "</td></tr></table></center>\n";
	echo "<textarea name=quote style='display: none' disabled>";
	printf("\n$strQuoteFrom\n", "$from ($email)" );
	nnrp_body( $nhd, $artnum, "&gt ", "\n", false, true, $article_convert['to'] );
	nnrp_close($nhd);
	echo "</textarea>";
	echo "</form>\n";
	html_focus( 'post', 'content' );
	html_tail();
}


?>
 