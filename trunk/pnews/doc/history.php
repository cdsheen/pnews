<?

# PHP News Reader
# Copyright (C) 2001-2004 Shen Cheng-Da
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

if( isset($_SERVER['HTTPS']) )
	$sflogo = 'https://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';
else
	$sflogo = 'http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1';

echo <<<EOH
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5" />
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css" />
<title>PHP News Reader - Release notes and History</title>
</head>
<body style="background-color: #EEFFFF">
EOH;

include('header.php');

echo <<<EOH
<font face=Georgia>
PHP News Reader v2.6.0 (2004/10/)
<ul>
<li>All language strings (\$strXXX) are renamed as \$pnews_str array.
<li>Hierarchical directories are used for storing cache and thread data.
</ul>
PHP News Reader v2.5.9 (2004/08/11)
<ul>
<li>Fix the login-fail bug which introduced by the configuration cache support.
<li>Cleaning for most PHP Notice warnings.
</ul>
PHP News Reader v2.5.8 (2004/08/05)
<ul>
<li>Fix the paging bug while <a href=guide.php#url_rewrite>\$CFG["url_rewrite"]</a> is disabled.
<li>Group descriptions are escaped to prevent from destroying HTML layout.
<li>Fix the warning when <a href=guide.php#html_footer>\$CFG['html_footer']</a> is not defined.
<li>Configuraton is now cached by PHP session to increase performance.
<li>Added Slovak translation (By Tichu)
</ul>
PHP News Reader v2.5.7 (2004/06/02)
<ul>
<li>Group description can be eliminated by setting <a href=guide.php#show_group_description>\$CFG['show_group_description']</a> to <i>false</i>
<li>Support <a href=guide.php#html_header>\$CFG['html_header']</a> and <a href=guide.php#html_footer>\$CFG['html_footer']</a> to customize the header and the footer.
<li><a href=guide.php#show_latest_top>\$CFG["show_newest_top"]</a> is renamed as <a href=guide.php#show_latest_top>\$CFG["show_latest_top"]</a>
<li>The page number of group indexing is now displayed as a selector, which can be switched easily.
</ul>
PHP News Reader v2.5.6 (2004/04/01)
<ul>
<li>New authentication module 'phpbb' for seamless integration with phpBB. (<a href=guide.php#auth_method>\$CFG['auth_method']</a>)
<li>HTML tags are now displayed as-is for articles with 'text/html' as Content-Type.
<li>Fix the word wrapping bug in textarea.
<li>Workaround for the news server which does not support <b>LIST NEWSGROUPS [wildmat]</b> of RFC 2980
</ul>
PHP News Reader v2.5.5 (2004/03/14)
<ul>
<li>Fixed the quote problem of the Italian language translation.
<li>News server can be configured on an <a href=guide.php#grouplst_server>alternative port number</a> (host.domain:port)
</ul>
PHP News Reader v2.5.4 (2004/03/02)
<ul>
<li>Fixed the incorrect restoring of \$_POST variable after authentication.
</ul>
PHP News Reader v2.5.3 (2004/02/22)
<ul>
<li>Added Italian translation (by Francesco Rolando)
<li>Various fixes for the javascript errors for single quote problem.
<li>Fix for the showing of the first article in a new newsgroup.
<li>Support for the configuration of HTML META description and keywords (<a href=guide.php#meta_description>\$CFG['meta_description']</a>,<a href=guide.php#meta_keywords>\$CFG['meta_keywords']</a>)
<li>All articles will be listed when the number of articles is less than one page.
</ul>
PHP News Reader v2.5.2 (2004/01/22)
<ul>
<li>MODE READER is now performed after NNTP authentication.
<li>Added German translation (by Jochen Staerk)
<li>Newsgroups whose name contains plus (+) is now handled without problem.
<li>Fix the IE download problem which related to cache control headers.
<li>The name of uuencoded file may contain space now.
<li>Various fixes for the javascript errors when <a href=guide.php#hide_email>\$CFG['hide_email']</a> is enabled.
<li>HTTP authenticatin info can be used in NNTP authentication. (<a href=guide.php#grouplst_auth>%http_user</a>, <a href=guide.php#grouplst_auth>%http_pw</a>)
<li>Catalog can be hided by 'hidden' in the <a href=guide.php#grouplst_option>option</a> directive.
<li>Debug information can be examined by setting <a href=guide.php#debug_level>\$CFG["debug_level"]</a>.
<li>Documentation for newsgroups.lst is refined.
</ul>
PHP News Reader v2.5.1 (2003/12/25)
<ul>
<li>Rewrite the `uudecode' codes to fix the bug which result in wrong outputs.
<li>Anti-Spam: E-Mail address is now encoded by default to prevent spamlist collection (<a href=guide.php#hide_email>\$CFG['hide_email']</a>)
<li>Uuencoded image attachment is shown inline unless explicitly setting <a href=guide.php#image_inline>\$CFG['image_inline']</a> to <i>false</i>.
<li>Fix the charset information in the login-failed page.
<li>E-Mail link auto-detection is refined for multi-bytes environment.
<li>Correct many words used for GB to/from BIG5 coding conversion.
</ul>
PHP News Reader v2.5.0 (2003/11/15)
<ul>
<li>Supporting attach files (by UUENCODE) when posting or replying article (<a href=guide.php#allow_attach_file>\$CFG['allow_attach_file']</a>)
<li>Mail authentication module now support <a href=guide.php#mail_auth>pop3s</a>.
<li>References header in replied article now works exactly as defined in <a href="http://rfc.giga.net.tw/rfc2822" target=_blank>RFC 2822</a>.
<li>The correct E-Mail domain is always used after login from Mail authentication module.
<li>Domain selector can be turned on by <a href=guide.php#mail_auth>\$CFG['domain_select']</a> for Mail authentication module.
<li>Per-catalog readonly can be configured in newsgroups.lst.
<li>Validate the correctness of the current session before performing authentication.
<li>GB2312 translation is refined by Czz and does not depend on the BIG5 translation any more.
<li>Log verbose level can be tuned by changing <a href=guide.php#log_level>\$CFG["log_level"]</a>.
<li>Fix the group verification bug when cross-posting article.
<li>Organization header now correctly overrides the setting of news server.
<li>The wrong regular expression used in split() is now fixed, this problem exists in most authentication modules.
<li>The downloaded attachment is now in the exactly correct size.
<li>Fix the problem of CAS login which may result in empty page.
<li><a href=guide.php#show_sourceforge_logo>\$CFG['show_sourceforge_logo']</a> is now default to false.
<li><a href=guide.php#post_restriction>\$CFG["post_restriction"]</a> is deprecated, and replaced by <a href=guide.php#global_readonly>\$CFG["global_readonly"]</a>
</ul>
PHP News Reader v2.4.4 (2003/11/09)
<ul>
<li>Fix a bug of parsing NNTP headers, which may cause coding conversion fail to work.
</ul>
PHP News Reader v2.4.3 (2003/11/08)
<ul>
<li>Workaround for MSIE SSL bug about attachment downloads (<a href="http://support.microsoft.com/default.aspx?scid=kb%3Ben-us%3B323308" target=_blank>Q323308</a>)
</ul>
PHP News Reader v2.4.2 (2003/11/01)
<ul>
<li>Support the downloading of uuencoded attachment.
<li>Reading article which encoded by "base64" or "quoted-printable".
<li>Workaround for broken news server which expiration does not work correctly.
<li>Article headers can be displayed if necessary.
<li>Empty lines are deleted in the quoted article when replying.
<li>Fix a bug of parsing Content-Type header.
<li>Added Finnish translation (by Markus Oversti)
</ul>
PHP News Reader v2.4.1 (2003/10/25)
<ul>
<li>Fix a potential bug which cause heavy loading if caching is enabled.
<li>Fix a bug about indexing articles which cause execution timeout.
<li>Login fail message is now displayed when "form" style login is used.
<li>Refined interface and style sheet.
<li>Default value of <a href=guide.php#magic_tag>\$CFG["magic_tag"]</a> is <i>false</i> now.
<li>Support <a href=guide.php#pop3s_auth>POP3S</a> (POP3 over SSL) authentication module
<li>Support <a href=guide.php#ftps_auth>FTPS</a> (FTP over SSL) authentication module
</ul>
PHP News Reader v2.4.0 (2003/10/04)
<ul>
<li>Support News Server with NNTP over SSL connection (also known as NNTPS or SNEWS)
<li>Support <a href=guide.php#nntps_auth>NNTPS</a> (NNTP over SSL) authentication module
<li>Overview format is confirmed by <a href="http://rfc.giga.net.tw/rfc2980" target=_blank>RFC 2980</a> (<b>LIST OVERVIEW.FMT</b>)
<li>Caching can be enabled to accelerate the indexing process. (<a href=guide.php#cache_dir>\$CFG["cache_dir"]</a>)
<li>Check for the invalid access to groups not listed in newsgroups.lst
<li><a href=guide.php#article_order_reverse>\$CFG["article_order_reverse"]</a> is deprecated, and replaced by <a href=guide.php#show_newest_top>\$CFG["show_newest_top"]</a>
<li>Fix the problem of filtering ANSI coloring codes.
</ul>
PHP News Reader v2.3.1 (2003/09/27)
<ul>
<li>Reading article with invalid artnum will be redirected into indexing page.
<li>Dead news server will not slow down the access for groups in other servers.
<li>Better error handling when news server is unavailable.
<li>Remove the read-only indication when <a href=guide.php#post_restriction>\$CFG["post_restriction"]</a> is enabled.
<li>Fix several missing style sheet errors.
<li>Auto-correction for some invalid uri if <a href=guide.php#url_rewrite>\$CFG["url_rewrite"]</a> is enabled.
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
PHP News Reader v1.9 (2003/01/01)
<ul>
<li>LDAP authentication module support
<li>Add X-User-Real-E-Mail: header for real E-Mail address
<li>Fix a typo error of default language file loading
</ul>
<p>
PHP News Reader v1.8 (2002/10/26)
<ul>
<li>Support encodings including Unicode/Chinese (UTF-8), Simplified Chinese (GB2312) and Traditional Chinese (BIG5).
<li>Code conversion between any two of the above three common Chinese encodings
<li>Interface re-formatting for various action
<li>Fix a bug of forwarding article in english interface
</ul>
<p>
PHP News Reader v1.7 (2002/10/05)
<ul>
<li>Support Chinese bi-directional coding conversion of BIG5/GB2312
<li>Support Chinese on-the-fly coding conversion while posting articles
<li>Fix the multiple MIME encoding problem of the subject
</ul>
<p>
PHP News Reader v1.6 (2002/08/10)
<ul>
<li>Support square-bracketed catalog in newsgroups.lst
<li>Couple of fixs for register_globals setting 'off'
<li>HTML entities in Subject is now correctly escaped
<li>Mail forwarding bug fixed
<li>Check for valid E-Mail entered by user
</ul>
<p>
PHP News Reader v1.5 (2002/07/06)
<ul>
<li>Multiple news server
<li>Run with PHP's register_globals setting turned off
<li>Security enhancement for newsgroup verification
<li>Switch interface language on the fly
</ul>
<p>
PHP News Reader v1.4 (2002/06/09)
<ul>
<li>Dynamic highlight of selected item 
<li>Correct the problem about author information of XOVER, HEAD
<li>Fix a bug of checking both "Not post to newsgroup" and "Reply to author"
</ul>
<hr />
<table cellspacing=0 cellpadding=0 width=100%>
<tr><td>
<font size=2>$pnews_claim</font><br />
<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="$sflogo" border="0" alt="SourceForge.net">
</a>
</td></tr></table>
</font>
</body>
</html>

EOH;

?>
