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

$title .= " - $pnews_msg[Reply]";

# -------------------------------------------------------------------

$artnum   = intval($_GET['artnum']);

if( isset($_POST['content']) && $_POST['content'] != '' ) {

	$server   = $_POST['server'];
	$group    = $_POST['group'];

	$c = check_group( $server, $group );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	if( $CFG['email_editing'] )
		$email = $_POST['email'];
	else
		$email = $auth_email;

	$refid      = $_POST['refid'];
	$authormail = $_POST['authormail'];
	$onlymail   = $_POST['onlymail'];
	$replymail  = $_POST['replymail'];

	if( $auto_slash ) {
		$nickname = stripslashes($_POST['nickname']);
		$content  = stripslashes($_POST['content']);
		$subject  = stripslashes($_POST['subject']);
	}
	else {
		$nickname = $_POST['nickname'];
		$content  = $_POST['content'];
		$subject  = $_POST['subject'];
	}

	$nickname = rtrim($nickname);
	$subject = rtrim($subject);

	$artconv = get_conversion( $_POST['charset'], $curr_charset );

	if( ! ( $nnrp->open( $server, $news_nntps[$c] ) && nnrp_authenticate() ) )
		connect_error( $server );

	if( ! $onlymail ) {
		if( $artconv['back'] ) {
			$nnrp->post_init( $artconv['back']($nickname), $email, $artconv['back']($subject), $group, $artconv['back']($CFG['organization']), $refid, $auth_email, $_POST['charset'] );
			$nnrp->post_write( $artconv['back']($content) );
		}
		else {
			$nnrp->post_init( $nickname, $email, $subject, $group, $CFG['organization'], $refid, $auth_email, $_POST['charset'] );
			$nnrp->post_write( $content );
		}
		$an = intval($CFG['allow_attach_file']);
		for( $i = 1 ; $i <= $an ; $i++ ) {
			if( isset( $_FILES['attach'.$i]['name'] ) ) {
				$filename = $_FILES['attach'.$i]['name'];
				uuencode_file( $filename, $_FILES['attach'.$i]['tmp_name'] );
			}
		}

		if( $CFG['post_signature'] ) {
			if( $artconv['back'] )
				$nnrp->post_write( $artconv['back']($CFG['post_signature']) );
			else
				$nnrp->post_write( $CFG['post_signature'] );
		}
		$nnrp->post_end();
		$nnrp->close();
	}

	html_head( "$group - $subject" );

	$time = strftime($CFG['time_format']);

	if( !$global_readonly && !$news_readonly[$c] && $replymail ) {
		$mime_headers = "Mime-Version: 1.0\nContent-Type: text/plain; charset=\"" . $_POST['charset'] . "\"\nContent-Transfer-Encoding: 8bit\n";
		if( $artconv['back'] )
			mail( $authormail, $artconv['back']($subject), $artconv['back']($content), "From: $email\n$mail_add_header\n$mime_headers" );
		else
			mail( $authormail, $subject, $content, "From: $email\n$mail_add_header" );
	}
	$subject = htmlspecialchars( $subject );

	echo <<<EOT
<table width=100%>
<tr><td class=status>$pnews_msg[ArticlePosted]</td>
    <td class=field><input class=normal type=button onClick="close_window()" value="$pnews_msg[CloseWindow]"></td>
</tr>
</table>
<hr />
<table>
<tr><td class=field>$pnews_msg[Author]: </td><td class=value>$nickname ($email)</td></tr>
<tr><td class=field>$pnews_msg[Time]: </td><td class=value>$time</td></tr>
<tr><td class=field>$pnews_msg[Subject]: </td><td class=value>$subject</td></tr>
<tr><td class=field>$pnews_msg[Group]: </td><td class=value>$group</td></tr>
</table>
<hr />

EOT;
	echo '<pre class=content>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</pre>\n";
	echo "<hr />\n";

	html_delay_close( 2000 );
	html_tail();
}
elseif( $artnum != '' ) {

#	$server   = $_GET['server'];
#	$group    = $_GET['group'];
#	$group    = get_group();

#	echo "server[$server] group[$group]<br />\n";

	$c = check_group( $server, $group );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	if( ! ( $nnrp->open( $server, $news_nntps[$c] ) && nnrp_authenticate() ) )
		connect_error( $server );

	list( $code, $count, $lowmark, $highmark ) = $nnrp->group( $group );

	$artinfo = $nnrp->head( $artnum, $news_charset[$curr_category], $CFG['time_format'] );

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
	if( $artinfo['ref'] )
		$refid = implode( ' ', $artinfo['ref'] ) . ' ' . $msgid;
	else
		$refid = $msgid;

	if( !preg_match( '/^Re: /i', $subject ) )
		$subject = 'Re: ' . $subject ;

	html_head( "$group - $subject" );
?>
<script type="text/javascript">
	function really() {
		if( document.post.content.value == "" ) {
			window.close();
			return(true);
		}
		if( confirm('<? echo addslashes($pnews_msg['ReallyQuit']); ?>') ) {
			window.close();
			return(true);
		}
		return(false);
	}
	function check_reply() {
		if( document.post.onlymail.checked && !document.post.replymail.checked ) {
			if( confirm('<? echo addslashes($pnews_msg['NoPostJustMail']); ?>') ) {
				document.post.replymail.checked = true;
			}
		}
		return(false);
	}
	function verify() {
		if( document.post.nickname.value == "" ) {
			alert('<? echo addslashes($pnews_msg['PleaseEnterName']); ?>');
			document.post.nickname.focus();
			return(false);
		}
		if( document.post.email.value == "" || ! /^[_.\d\w-]+@([\d\w][\d\w-]+\.)+[\w]{2,3}$/.test(document.post.email.value) ) {
			alert('<? echo addslashes($pnews_msg['PleaseEnterEmail']); ?>');
			document.post.email.focus();
			return(false);
		}
		if( document.post.subject.value == "" ) {
			alert('<? echo addslashes($pnews_msg['PleaseEnterSubject']); ?>');
			document.post.subject.focus();
			return(false);
		}
		if( document.post.content.value == "" ) {
			alert('<? echo addslashes($pnews_msg['PleaseEnterContent']); ?>');
			document.post.content.focus();
			return(false);
		}
<?
		if( $CFG['confirm_post'] ) {
			echo <<<CONFIRM
		if( !confirm( '$CFG[confirm_post]' ) ) {
			return(false);
		}

CONFIRM;
		}
?>
		document.post.submit();
		return(true);
	}
</script>
<?
	$mail_disable = $CFG['email_editing'] ? '' : ' disabled';
#	$subject = str_replace( '"', '\"', $subject );
	echo "<form name=post action=\"$self\" method=post enctype=\"multipart/form-data\">\n";
	echo "<center><table width=100% border=0 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td class=field width=12%>$pnews_msg[Name]:</td><td><input class=input name=nickname size=20 value=\"$auth_user \"></td>\n";
	echo " <td class=text><input name=replymail type=checkbox>$pnews_msg[ReplyToAuthor]</td></tr>\n";
	echo "<tr><td class=field>$pnews_msg[Email]:</td><td><input class=input name=email size=40 value=\"$auth_email\" $mail_disable></td>\n";
	echo " <td class=text><input name=onlymail type=checkbox onClick='check_reply();'>$pnews_msg[NotPostToGroup]</td></tr>\n";
	echo "<tr><td class=field>$pnews_msg[Group]:</td><td><input class=input name=showgroup size=40 value=\"$group\" disabled></td>\n";
	echo " <td class=text><input class=normal type=button value=\"$pnews_msg[FormConfirmPost]\" onClick='verify()' tabindex=2>\n";
	echo " <input class=normal type=button value=\"$pnews_msg[FormCancelPost]\" onClick='really()' tabindex=3></td></tr>\n";
	echo "<tr><td class=field>$pnews_msg[Subject]:</td><td colspan=2><input class=input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . " \" size=60></td></tr>\n";
#	echo "</table>\n<table>\n";
	echo "<input name=authormail value=\"$email\" type=hidden>";
	echo "<input name=charset value=\"" . $artinfo['charset'] . "\" type=hidden>";
	echo "<input name=server value=\"$server\" type=hidden>";
	echo "<input name=group value=\"$group\" type=hidden>\n";
	echo "<input name=refid type=hidden value=\"" . htmlspecialchars($refid, ENT_NOQUOTES ) . "\">\n";
?>
<script type="text/javascript">
	function InsertQuote() {
		document.post.content.value = document.post.quote.value + "\n" + document.post.content.value;
	}
</script>
<?
	echo <<<EOF
<tr><td class=field>$pnews_msg[Content]:</td>
<td colspan=2 align=right>
<input class=normal type=button onClick='InsertQuote();' value="$pnews_msg[FormInsertQuote]">
</td></tr>
<tr><td colspan=3>
<textarea name=content class=content wrap=hard tabindex=1 cols=82>
</textarea>
</td></tr></table>

EOF;
	$an = intval($CFG['allow_attach_file']);
	for( $i = 1; $i <= $an ; $i++ ) {
		$ti = 4+$i;
		if( $i == 1 )
			echo "<table width=100%>\n";
		if( $i % 2 == 1 ) {
			echo <<<EOA
 <tr><td class=field align=right>
 $pnews_msg[Attachment] $i:</td>
 <td align=left><input class=input name="attach$i" type="file" tabindex="$ti">
 </td>
EOA;
		}
		else {
			echo <<<EOA
 <td class=field align=right>$pnews_msg[Attachment] $i:
 <input class=input name="attach$i" type="file" tabindex="$ti">
 </td></tr>
EOA;
		}
	}

	if( $i % 2 == 0 )
		echo "</tr>\n";
	if( $an > 0 )
		echo "</table>\n";
	echo "</center>\n";
	echo "<textarea name=quote style='display: none' disabled>";
	printf("\n$pnews_msg[QuoteFrom]\n", "$from ($email)" );

	$show_mode |= SPACE_ASIS;
	$nnrp->show( $artnum, $artinfo, $show_mode, '&gt; ', "\n", $artconv['to'] );
	$nnrp->close();
	echo "</textarea>";
	echo "</form>\n";
	html_focus( 'post', 'content' );
	html_tail();
}


?>
