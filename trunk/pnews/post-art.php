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

	if( verifying( $server, $group ) == -1 )
		session_error( $server, $group );

	if( $post_restriction )
		readonly_error( $server, $group );

	$email = $_POST['email'];

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

	$nhd = nnrp_open( $server );

	if( ! ( $nhd && nnrp_authenticate( $nhd ) ) ) {
		html_head('ERROR');
		echo "<p><font size=3>$strConnectServerError - " . $server . "</font><br>\n";
		html_foot();
		html_tail();
		exit;
	}

	if( $article_convert['back'] ) {
		nnrp_post_begin( $nhd, $article_convert['back']($nickname), $email, $article_convert['back']($subject), $group, $article_convert['back']($organization), null, $auth_email, $news_charset[$curr_catalog] );
		nnrp_post_write( $nhd, $article_convert['back']($content) );
		if( $CFG['post_signature'] )
			nnrp_post_write( $nhd, $article_convert['back']($CFG['post_signature']) );
	}
	else {
		nnrp_post_begin( $nhd, $nickname, $email, $subject, $group, $organization, null, $auth_email, $news_charset[$curr_catalog] );
		nnrp_post_write( $nhd, $content );
		if( $CFG['post_signature'] )
			nnrp_post_write( $nhd, $CFG['post_signature'] );
	}
	nnrp_post_finish( $nhd );
	nnrp_close($nhd);

	html_head( "$group - $subject" );

	$time = strftime($CFG['time_format']);

	echo "<table width=100%><tr><td class=x>";
	echo "<font size=2 color=navy>$strArticlePosted</font>\n";
	echo '</td><td class=x align=right><input class=b type=button onClick="close_window()" value=' . $strCloseWindow . '></td></tr></table><hr>';
	echo "\n<table>\n";
	echo "<tr><td align=right>$strAuthor: </td><td><font color=blue>$nickname ($email)</font></td></tr>\n";
	echo "<tr><td align=right>$strTime: </td><td><font color=blue>$time</font></td></tr>\n";
	echo "<tr><td align=right>$strSubject: </td><td><font color=blue>" . htmlspecialchars( $subject ) . "</font></td></tr>\n";
	echo "<tr><td align=right>$strGroup: </td><td><font color=blue>$group</font></td></tr></table><hr>\n";
	echo '<font size=2 color=black face=monospace>' . nl2br(htmlspecialchars($content, ENT_NOQUOTES )) . "</font>\n";
	html_delay_close( 2000 );
	echo "<hr>\n";
	html_tail();
}
else {

	$server = $_GET['server'];
	$group  = $_GET['group'];

	if( $post_restriction )
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
echo <<<EOF
<form name=post action="$self" method=post>
<center>
<table cellpadding=0 cellspacing=0 width=100%>
 <tr><td class=x align=right>$strName:</td><td><input name=nickname size=20 value="$auth_user"></td></tr>
 <tr><td class=x align=right>$strEmail:</td><td><input name=email size=40 value="$auth_email"></td></tr>
 <tr><td class=x align=right>$strGroup:</td><td><input name=postgroup size=40 value="$group" disabled></td></tr>
 <tr><td class=x align=right>$strSubject:</td><td><input name=subject size=56 tabindex=1></td></tr>
 <tr><td class=x align=right>$strContent:</td><td align=right>
  <input class=b type=button value='$strFormConfirmPost' onClick='verify()' tabindex=3>
  <input class=b type=button value='$strFormCancelPost' onClick='really()' tabindex=4></td></tr>
 <tr><td colspan=2 class=x>
  <input name=server value="$server" type=hidden>
 <input name=group value="$group" type=hidden>
 <textarea name=content class=text rows=12 wrap=physical tabindex=2></textarea><br>
</td></tr>
</table>
</center>
</form>
EOF;
	html_focus( 'post', 'subject' );
	html_tail();
}


?>
