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

$title .= " - $strPost";

# -------------------------------------------------------------------

if( $_POST['content'] != '' ) {

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

	$nhd = nnrp_open( $server, $news_nntps[$c] );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) )
		connect_error( $server );

	if( $article_convert['back'] ) {
		nnrp_post_begin( $nhd, $article_convert['back']($nickname), $email, $article_convert['back']($subject), $group, $article_convert['back']($CFG['organization']), null, $auth_email, $news_charset[$curr_catalog] );
		nnrp_post_write( $nhd, $article_convert['back']($content) );
	}
	else {
		nnrp_post_begin( $nhd, $nickname, $email, $subject, $group, $CFG['organization'], null, $auth_email, $news_charset[$curr_catalog] );
		nnrp_post_write( $nhd, $content );
	}

	$an = intval($CFG['allow_attach_file']);
	for( $i = 1 ; $i <= $an ; $i++ ) {
#		echo "attach$i [" .$HTTP_POST_FILES["attach$i"]['name']. "]<br>\n";
		if( isset( $HTTP_POST_FILES["attach$i"]['name'] ) ) {
			$filename = $HTTP_POST_FILES["attach$i"]['name'];
			uuencode( $nhd, $filename, $HTTP_POST_FILES["attach$i"]['tmp_name'] );
		}
	}

	if( $CFG['post_signature'] ) {
		if( $article_convert['back'] )
			nnrp_post_write( $nhd, $article_convert['back']($CFG['post_signature']) );
		else
			nnrp_post_write( $nhd, $CFG['post_signature'] );
	}

	nnrp_post_finish( $nhd );
	nnrp_close($nhd);

	html_head( "$group - $subject" );

	$time = strftime($CFG['time_format']);
	$subject = htmlspecialchars( $subject );

	echo <<<EOT
<table width=100%>
 <tr><td class=status>$strArticlePosted</td>
     <td class=field><input class=normal type=button onClick="close_window()" value="$strCloseWindow"></td>
</tr>
</table>
<hr />
<table>
 <tr><td class=field>$strAuthor: </td><td class=value>$nickname ($email)</td></tr>
 <tr><td class=field>$strTime: </td><td class=value>$time</td></tr>
 <tr><td class=field>$strSubject: </td><td class=value>$subject</font></td></tr>
 <tr><td class=field>$strGroup: </td><td class=value>$group</td></tr>
</table>
<hr />

EOT;
	echo '<pre class=content>' . nl2br(htmlspecialchars($content, ENT_NOQUOTES )) . "</div>\n";
	html_delay_close( 2000 );
	echo "<hr />\n";
	html_tail();
}
else {

	$server = $_GET['server'];
	$group  = $_GET['group'];

	if( $global_readonly || $news_readonly[$c] )
		readonly_error( $server, $group );

	html_head( $title );

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
$mail_disable = $CFG['email_editing'] ? '' : ' disabled';
echo <<<EOF
<form name=post action="$self" method=post enctype="multipart/form-data">
<center>
<table cellpadding=0 cellspacing=0 width=100%>
 <tr><td class=field>$strName:</td><td><input name=nickname size=20 value="$auth_user"></td>
 <td class=field align=right>
  <input class=normal type=button value='$strFormConfirmPost' onClick='verify()' tabindex=3>
  <input class=normal type=button value='$strFormCancelPost' onClick='really()' tabindex=4>
 </td>
 </tr>
 <tr><td class=field>$strEmail:</td><td colspan=2><input name=email size=40 value="$auth_email" $mail_disable></td></tr>
 <tr><td class=field>$strGroup:</td><td colspan=2><input name=postgroup size=40 value="$group" disabled></td></tr>
 <tr><td class=field>$strSubject:</td><td colspan=2><input name=subject size=56 tabindex=1></td></tr>
 <tr><td class=field>$strContent:</td>
 <td colspan=2 align=right></td></tr>
 <tr><td colspan=3>
 <input name=server value="$server" type=hidden>
 <input name=group value="$group" type=hidden>
 <textarea name=content class=content rows=12 wrap=physical tabindex=2></textarea><br /><br />
 </td></tr>
EOF;
	$an = intval($CFG['allow_attach_file']);
	for( $i = 1; $i <= $an ; $i++ ) {
		$ti = 4+$i;
		if( $i % 2 == 1 ) {
			echo <<<EOA
 <tr><td class=field>
 $strAttachment $i:</td>
 <td><input name="attach$i" type="file" tabindex="$ti">
 </td>
EOA;
		}
		else {
			echo <<<EOA
 <td class=field align=right>$strAttachment $i:
 <input name="attach$i" type="file" tabindex="$ti">
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
