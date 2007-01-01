<?

# PHP News Reader
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

$title .= " - $pnews_msg[Post]";

# -------------------------------------------------------------------
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

	if( ! ( $nnrp->open( $server, $news_nntps[$c] ) && nnrp_authenticate() ) )
		connect_error( $server );

	if( $article_convert['back'] ) {
		$nnrp->post_init( $article_convert['back']($nickname), $email, $article_convert['back']($subject), $group, $article_convert['back']($CFG['organization']), null, $auth_email, $news_charset[$curr_category] );
		$nnrp->post_write( $article_convert['back']($content) );
	}
	else {
		$nnrp->post_init( $nickname, $email, $subject, $group, $CFG['organization'], null, $auth_email, $news_charset[$curr_category] );
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
		if( $article_convert['back'] )
			$nnrp->post_write( $article_convert['back']($CFG['post_signature']) );
		else
			$nnrp->post_write( $CFG['post_signature'] );
	}

	$nnrp->post_end();
	$nnrp->close();

	html_head( "$group - $subject" );

	$time = strftime($CFG['time_format']);
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
 <tr><td class=field>$pnews_msg[Subject]: </td><td class=value>$subject</font></td></tr>
 <tr><td class=field>$pnews_msg[Group]: </td><td class=value>$group</td></tr>
</table>
<hr />

EOT;
	echo '<pre class=content>' . htmlspecialchars($content, ENT_NOQUOTES ) . "</div>\n";
	html_delay_close( 2000 );
	echo "<hr />\n";
	html_tail();
}
else {

#	$server = $_GET['server'];
#	$group  = $_GET['group'];

	$c = check_group( $server, $group );

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	html_head( $title );

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
echo <<<EOF
<form name=post action="$self" method=post enctype="multipart/form-data">
<center>
<table cellpadding=0 cellspacing=0 width=100%>
 <tr><td class=field width=12%>$pnews_msg[Name]:</td><td><input class=input name=nickname size=20 value="$auth_user "></td>
 <td class=field align=right>
  <input class=normal type=button value='$pnews_msg[FormConfirmPost]' onClick='verify()' tabindex=3>
  <input class=normal type=button value='$pnews_msg[FormCancelPost]' onClick='really()' tabindex=4>
 </td>
 </tr>
 <tr><td class=field>$pnews_msg[Email]:</td><td colspan=2><input class=input name=email size=40 value="$auth_email" $mail_disable></td></tr>
 <tr><td class=field>$pnews_msg[Group]:</td><td colspan=2><input class=input name=postgroup size=40 value="$group" disabled></td></tr>
 <tr><td class=field>$pnews_msg[Subject]:</td><td colspan=2><input class=input name=subject size=56 tabindex=1></td></tr>
 <tr><td class=field>$pnews_msg[Content]:</td>
 <td colspan=2 align=right></td></tr>
 <tr><td colspan=3>
 <input name=server value="$server" type=hidden>
 <input name=group value="$group" type=hidden>
 <textarea name=content class=content rows=12 cols=78 wrap=hard tabindex=2></textarea><br /><br />
 </td></tr>
EOF;
	$an = intval($CFG['allow_attach_file']);
	for( $i = 1; $i <= $an ; $i++ ) {
		$ti = 4+$i;
		if( $i % 2 == 1 ) {
			echo <<<EOA
 <tr><td class=field>
 $pnews_msg[Attachment] $i:</td>
 <td><input class=input name="attach$i" type="file" tabindex="$ti">
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

	echo <<<EOF
</table>
</center>
</form>
EOF;
	html_focus( 'post', 'subject' );
	html_tail();
}

?>
