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

require_once('../version.inc.php');

if( $_SERVER['HTTPS'] )
	$sflogo = 'https://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';
else
	$sflogo = 'http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';

echo <<<EOH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>History of PHP News Reader</title>
</head>
<body style="background-color: #EEFFFF">
<table width=100% cellpadding=0 cellspacing=0>
<tr>
<td>
 <font face="Georgia"><h3>$pnews_name $pnews_version</h3></font>
 </td>
<td align=right valign=bottum>
 <font face="Georgia" size=1>Release Date: $pnews_release</font>
</td>
</tr>
</table>
<font size=3 color=black face="Georgia">
<a href=index.php>PHP News Reader</a> Release histroy
<hr>
<!--
<li>RFC 2046 - MIME multipart document and attachment download support. (not yet)
-->
PHP News Reader v2.3.1 (2003/09/27)
<ul>
<li>Reading article with invalid artnum will be redirected into indexing page.
<li>Better error handling when news server is unavailable.
<li>Remove the read-only indication when <a href=guide.php#post_restriction>\$CFG["post_restriction"]</a> is enabled.
<li>Fix several missing style sheet errors.
</ul>
PHP News Reader v2.3.0 (2003/09/06)
<ul>
<li>Added French translation (by Pascal Aubry)
<li>Support <a href=http://www.yale.edu/tp/auth target=_blank>CAS</a> authentication module (by Pascal Aubry)
<li>E-Mail editing can be disabled by setting <a href=guide.php#email_editing>\$CFG["email_editing"]</a> as off
<li>The character '#' is included in the uri of hyperlink auto-detection.
<li>Support more flexible <a href=guide.php#group_match>group matching syntax</a> in newsgroup.lst.
<li>Directly linking to the next/previous article
<li>Language switching reworked, relation between charset and language is more clear.
<li><a href=guide.php#interface_language>\$CFG["interface_language"]</a> makes the decision at the language used in interface.
</ul>
PHP News Reader v2.2.4 (2003/07/26)
<ul>
<li>Fix the bug in MySQL authentication module.
<li>Use of <a href=guide.php#magic_tag>\$CFG["magic_tag"]</a> to indicate the visit state of newsgroup
</ul>
PHP News Reader v2.2.3 (2003/07/20)
<ul>
<li>Fix the bug at replying if the quoted article has leading spaces.
</ul>
PHP News Reader v2.2.2 (2003/07/05)
<ul>
<li>Option to force using SSL(HTTPS) when performing login. (<a href=guide.php#https_login>\$CFG["https_login"]</a>)
<li><a href=guide.php#url_base>\$CFG["url_base"]</a> must be configured even if <a href=guide.php#url_rewrite>\$CFG["url_rewrite"]</a> is off
</ul>
PHP News Reader v2.2.1 (2003/06/21)
<ul>
<li>Initial support for style sheet configuration via <a href=guide.php#style_sheet>\$CFG["style_sheet"]</a>
<li>The default news server address can be ignored when <a href=guide.php#url_rewrite>\$CFG["url_rewrite"]</a> is enabled
<li>Handling quotes correctly with the <i>magic_quotes_gpc</i> state of PHP.
<li>The leading spaces are displayed as-is while reading articles.
<li>Article is displayed in 'mono-space' font by default.
<li>Fix the bug of the configuration checking about NNTP authentication module.
<li>Fix the group verification error while forwarding article.
<li>Language switch can be turned off by setting <a href=guide.php#language_switch>\$CFG["language_switch"]</a> as false.
<li>Fix the lower-case problem of nnrp authentication parameter in newsgroups.lst
</ul>
PHP News Reader v2.2.0 (2003/05/25)
<ul>
<li>Support news server which requires authorization.
<li>The ANSI coloring codes are filtered by default (<a href=guide.php#filter_ansi_color>\$CFG["filter_ansi_color"]</a>).
<li>Support links to Next and Previous articles.
<li>Support URL rewrite function (<a href=guide.php#url_rewrite>\$CFG["url_rewrite"]</a> and <a href=guide.php#url_base>\$CFG["url_base"]</a>)
<li>Deleted articles are skipped and exactly 20 articles are displayed in one page
<li>Add option (<a href=guide.php#article_order_reverse>\$CFG["article_order_reverse"]</a>) to config the article numbering order.
<li>Add option (<a href=guide.php#show_article_popup>\$CFG["show_article_popup"]</a>) to config the use of popup window.
<li>Support NNTP authentication module for authenticate with News server.
<li>Number of articles per page can be configured by <a href=guide.php#articles_per_page>\$CFG["articles_per_page"]</a> (default is 20).
<li>Fix a bug when post after timeout with "form" style login.
</ul>
PHP News Reader v2.1.2 (2003/04/05)
<ul>
<li>Do not strip whitespace from the beginning of each line in the article posted.
<li>Jump to the correct catalog when returning from indexing page.
<li>Correct the problem of lost for POST variables when launching the login dialog.
</ul>
PHP News Reader v2.1.1 (2003/03/08)
<ul>
<li>The format of time displayed can be configured by <a href=guide.php#time_format>\$CFG["time_format"]</a>.
<li>Only show the group name in the title of indexing page.
<li>Add more error handling codes for invalid news server settting.
<li>Several author name/mail parsing problems fixed.
<li>Fix variable missing problem in 'open' authentication type.
</ul>
PHP News Reader v2.1.0 (2003/03/02)
<ul>
<li>Installation Guide released.
<li>Use 'charset' instead of 'language' in the configuration file
<li>The correct charset of group is used while loading pages without session.
<li>The MIME charset of article will be honored while processing news articles.
<li>The article will be posted and mailed with correct MIME headers about charset information based on the RFC 2045 standard.
</ul>
<p>
PHP News Reader v2.0.4 (2003/02/18)
<ul>
<li>Change the article numbering order (descent)
</ul>
<p>
PHP News Reader v2.0.3 (2003/02/05)
<ul>
<li>Use UTF-8 for English locale
</ul>
<p>
PHP News Reader v2.0.2 (2003/01/24)
<ul>
<li>Fix the bug of 'required' authentication model
</ul>
<p>
PHP News Reader v2.0.1 (2003/01/22)
<ul>
<li>Correct the link of SourceForge logo
</ul>
<p>
PHP News Reader v2.0 (2003/01/19)
<ul>
<li>First public release under <a href=copying.php>GPL</a> and hosts on <a href=http://sourceforge.net/ target=_blank>SourceForge</a>
<li>Rewrite the authentication codes and the use of the ticket system
<li>Logging out support
<li>Support 'optional' authentication model for Read-Only access without authentication
<li>Rewrite the LDAP and POP3 authentication modules
<li>Support Mail authentication module for multiple POP3 server environment
<li>Support MySQL and PostgreSQL authentication modules, easy to integrate with <a href="http://www.phpbb.com/" target=_blank>phpBB</a>
<li>FTP authentication module support
<li>User information can be extracted from LDAP attributes or Database table fields along with authentication
<li>Standardize the requirements for writing an authentication module
<li>Support FORM style login process other than the original HTTP authentication
<li>More flexible configuration based on a new config.inc.php syntax
<li>Enable interface language to switch without cookie support
<li>Different encodings can be used in different catalogs
<li>Support private groups which only accessible by authenticated user
<li>Fix the E-Mail parsing bug in XOVER command of NNRP library
</ul>
<p>
PHP News Reader v1.9 (released on 2003/01/01)
<ul>
<li>LDAP authentication module support
<li>Add X-User-Real-E-Mail: header for real E-Mail address
<li>Fix a typo error of default language file loading
</ul>
<p>
PHP News Reader v1.8 (released on 2002/10/26)
<ul>
<li>Support encodings including Unicode/Chinese (UTF-8), Simplified Chinese (GB2312) and Traditional Chinese (BIG5).
<li>Code conversion between any two of the above three common Chinese encodings
<li>Interface re-formatting for various action
<li>Fix a bug of forwarding article in english interface
</ul>
<p>
PHP News Reader v1.7 (released on 2002/10/05)
<ul>
<li>Support Chinese bi-directional coding conversion of BIG5/GB2312
<li>Support Chinese on-the-fly coding conversion while posting articles
<li>Fix the multiple MIME encoding problem of the subject
</ul>
<p>
PHP News Reader v1.6 (released on 2002/08/10)
<ul>
<li>Support square-bracketed catalog in newsgroups.lst
<li>Couple of fixs for register_globals setting 'off'
<li>HTML entities in Subject is now correctly escaped
<li>Mail forwarding bug fixed
<li>Check for valid E-Mail entered by user
</ul>
<p>
PHP News Reader v1.5 (released on 2002/07/06)
<ul>
<li>Multiple news server
<li>Run with PHP's register_globals setting turned off
<li>Security enhancement for newsgroup verification
<li>Switch interface language on the fly
</ul>
<p>
PHP News Reader v1.4 (released on 2002/06/09)
<ul>
<li>Dynamic highlight of selected item 
<li>Correct the problem about author information of XOVER, HEAD
<li>Fix a bug of checking both "Not post to newsgroup" and "Reply to author"
</ul>
<hr>
<table cellspacing=0 cellpadding=0 width=100%>
<tr><td>
<font size=2>$pnews_claim</font><br>
<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" alt="SourceForge.net Logo">
</a>
</td></tr></table>
</font>
</body>
</html>

EOH;

?>
