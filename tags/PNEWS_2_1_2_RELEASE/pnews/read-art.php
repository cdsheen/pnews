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

# -------------------------------------------------------------------

$artnum = $_GET['artnum'];

$nhd = nnrp_open( $server );

if( ! $nhd ) {
	html_head('Reading Error');
	echo "<p><font size=3>$strConnectServerError - " . $server . "</font><br>\n";
	html_foot();
	html_tail();
	exit;
}

list( $code, $count, $lowmark, $highmark ) = nnrp_group( $nhd, $group );

#list( $from, $email, $subject, $date, $msgid, $org )

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

html_head( "$subject ($group)", null, 'topmargin=0 leftmargin=0' );
#html_head( "$group - $subject", null, 'topmargin=0' );

#if( strlen( $subject ) > $subject_limit + 6 )
#	$subject = substr( $subject, 0, $subject_limit + 6 ) . ' ..';
#else
#	$subject = $subject;

$subject = htmlspecialchars( $subject );

#$date = str_replace( ' ', '<br>', $date );

if( strlen( $org ) > $org_limit )
	$org = substr( $org, 0, $org_limit ) . ' ..';

echo "<center>\n<table width=100% cellpadding=3 cellspacing=0>\n";

echo "<tr bgcolor=#DDDDFF><td class=x align=left><font size=2><b><a href=\"$uri\">$subject</a></b></font></td>\n";
echo "<td align=right>$date</td></tr>\n";
echo "<tr bgcolor=#FFFFEE><td class=x>$from (<a href=\"mailto:$email\">$email</a>)</td>\n";
echo "<td align=right class=x>$org</td></tr>\n";

#toolbar( $server, $group, $artnum, $subject );

echo "<tr><td colspan=2 bgcolor=#EEFFEE>";
echo "<hr><font size=2>";
if( $artconv['to'] )
	nnrp_body( $nhd, $artnum, "", "<br>\n", true, false, $artconv['to'] );
else
	nnrp_body( $nhd, $artnum, "", "<br>\n" );
echo "</font>";
nnrp_close($nhd);

echo "</td></tr><tr><td align=center colspan=2>\n";

toolbar( $server, $group, $artnum, $subject );
echo "</td></tr><tr><td align=right colspan=2>";
echo "</td></tr></table></center>";

html_tail();

function toolbar( $server, $group, $artnum, $title ) {
	global $post_restriction, $email, $auth_email;
	global $strCloseWindow, $strReplyDetail, $strReplyQuoteDetail;
	global $strCrossPostDetail, $strForwardDetail, $strDeleteDetail;
	global $strMyFavor;
	echo "<table border=1 cellspacing=0 cellpadding=2>\n";
	if( !$post_restriction ) {
		echo "<tr>";
		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
		echo reply_article( $server, $group, $artnum, $strReplyDetail, false, true );
		echo "</td>\n";

#		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
#		echo reply_article( $server, $group, $artnum, $strReplyQuoteDetail, true, true );
#		echo "</td>\n";

		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
		echo xpost_article( $server, $group, $artnum, $strCrossPostDetail, true );
		echo "</td>\n";

		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
		echo forward_article( $server, $group, $artnum, $strForwardDetail, true );
		echo "</td>\n";

		echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
		if( $email == $auth_email )
			echo delete_article( $server, $group, $artnum, $strDeleteDetail, true );
		else
			echo "&nbsp;";
		echo "</td>\n";
	}
#	echo "<tr>";
	$host = $_SERVER['HTTP_HOST'];
	echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
	if( strstr( $_SERVER["HTTP_USER_AGENT"], 'MSIE' ) )
		echo "<a href=\"javascript:myfavor('http://$host$uri', '$title')\">$strMyFavor</a>\n";
	else
		echo "&nbsp;";
	echo "</td>\n";
	echo "<td width=100 bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
	echo "<a href=\"javascript:close_window()\">$strCloseWindow</a>";
	echo "</td>";
	echo "<td bgcolor=#FFDDEE align=center onMouseover='this.bgColor=\"#FFFFC0\";' onMouseout='this.bgColor=\"#FFDDEE\";'>";
#	echo "Language: ";
	show_language_switch();
	echo "</td></tr></table>\n";
}

?>
