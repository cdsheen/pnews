<?

// Copyright (C) 2001-2003 - All rights reserved
// Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

include('utils.inc.php');

# -------------------------------------------------------------------

$artnum  = $_GET['artnum'];
$confirm = $_POST['confirm'];

if( $confirm == 1 ) {

	$server = $_POST['server'];
	$group  = $_POST['group'];

	if( $post_restriction )
		readonly_error( $server, $group );

	$msgid   = $_POST['msgid'];
	$subject = $_POST['subject'];

	$nhd = nnrp_open( $server );
	if( $article_convert['back'] )
		nnrp_cancel( $nhd, $article_convert['back']($auth_user), $auth_email, $msgid, $group, $article_convert['back']($subject) );
	else
		nnrp_cancel( $nhd, $auth_user, $auth_email, $msgid, $group, $subject );
	nnrp_close($nhd);

	html_head( $strDeleteDetail );

	echo "<font size=2 color=navy>$strArticleIsDeleted</font><hr>\n<table>";
	echo "<tr><td align=right>$strAuthor: </td><td><font color=blue>$auth_user ($auth_email)</font></td></tr>\n";
	echo "<tr><td align=right>$strSubject: </td><td><font color=blue>" . htmlspecialchars($subject) . "</font></td></tr>\n";
	echo "<tr><td align=right>$strGroup: </td><td><font color=blue>$group</font></td></tr></table><hr>\n";
	echo "<a href=\"javascript:close_window();\">$strCloseWindow</a>";

	html_delay_close( 2000 );

	html_tail();
}
elseif( $artnum != '' ) {

	$server = $_GET['server'];
	$group  = $_GET['group'];

	if( $post_restriction )
		readonly_error( $server, $group );

	$nhd = nnrp_open( $server );

	list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

	list( $from, $email, $subject, $date, $msgid, $org ) = nnrp_head( $nhd, $artnum, $article_convert['to'] );

	html_head( "$strDeleteDetail - $subject" );

	echo $strRealyDelete ;

	echo "<hr>";

	echo "<form class=x name=post action=$self method=post>";
	echo "<center><table cellpadding=0 cellspacing=0 width=100%>\n";
	echo "<tr><td class=x align=right>$strName:</td><td><input name=nickname size=20 value=\"$auth_user\" disabled></td></tr>\n";
	echo "<tr><td class=x align=right>$strEmail:</td><td><input name=email size=40 value=\"$auth_email\" disabled></td></tr>\n";
	echo "<tr><td class=x align=right>$strGroup:</td><td><input size=40 value=$group disabled></td></tr>\n";
	echo "<tr><td class=x align=right>$strSubject:</td><td><input value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" size=60 disabled></td></tr>\n";

	echo "<tr><td align=right class=x>\n";
	echo "<input name=confirm value=1 type=hidden>\n";
	echo "<input name=msgid value=\"" . htmlspecialchars($msgid, ENT_NOQUOTES ) . "\" type=hidden>\n";
	echo "<input name=subject value=\"" . htmlspecialchars($subject, ENT_QUOTES ) . "\" type=hidden>\n";
	echo "<input name=server value=$server type=hidden>\n";
	echo "<input name=group value=$group type=hidden>\n";

	echo "$strContent:</td><td align=right>";
	echo " <input class=b type=submit value='$strFormConfirmDelete'>\n";
	echo " <input class=b type=button value='$strFormCancelDelete' onClick='close_window();'></td></tr>\n";
	echo "<tr><td colspan=2>";
	echo "<textarea name=content class=text rows=10 wrap=physical disabled>";
	nnrp_body( $nhd, $artnum, '', "\n", false, false, $article_convert['to'] );
	nnrp_close($nhd);
	echo "\n</textarea></td></tr>\n";
	echo "</table></center>\n";
	echo "</form>\n";

	html_tail();
}


?>
