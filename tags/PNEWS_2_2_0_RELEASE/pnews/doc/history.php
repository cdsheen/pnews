<?

require_once('../version.inc.php');

echo '<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>History of PHP News Reader</title>
</head>
<body style="background-color: #EEFFFF">
<table width=100% cellpadding=0 cellspacing=0><tr><td>
';
echo "<font face=\"Georgia\"><h3>$pnews_name $pnews_version</h3></font>\n";
echo "</td><td align=right valign=bottum>";
echo "<font face=\"Georgia\" size=1>Release Date: $pnews_release</font>\n";
echo '
</td></tr></table>
<font size=3 color=black face="Georgia">
<a href=index.php>PHP News Reader</a> Release histroy
<hr>
<!--
<li>RFC 2046 - MIME multipart document and attachment download support. (not yet)
-->
PHP News Reader v2.2.0 (2003/05/25)
<ul>
<li>Support news server which requires authorization.
<li>The ANSI coloring codes are filtered by default (<a href=guide.php#filter_ansi_color>$CFG["filter_ansi_color"]</a>).
<li>Support links to Next and Previous articles.
<li>Support URL rewrite function (<a href=guide.php#url_rewrite>$CFG["url_rewrite"]</a> and <a href=guide.php#url_base>$CFG["url_base"]</a>)
<li>Deleted articles are skipped and exactly 20 articles are displayed in one page
<li>Add option (<a href=guide.php#article_order_reverse>$CFG["article_order_reverse"]</a>) to config the article numbering order.
<li>Add option (<a href=guide.php#show_article_popup>$CFG["show_article_popup"]</a>) to config the use of popup window.
<li>Support NNTP authentication module for authenticate with News server.
<li>Number of articles per page is configurable by <a href=guide.php#articles_per_page>$CFG["articles_per_page"]</a> (default is 20).
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
<li>The format of time displayed can be configured by <a href=guide.php#time_format>$CFG["time_format"]</a>.
<li>Show only group name in the title of indexing page.
<li>Add more error handling codes for invalid news server settting.
<li>Several author name/mail parsing problems fixed.
<li>Fix variable missing problem in \'open\' authentication type.
</ul>
PHP News Reader v2.1.0 (2003/03/02)
<ul>
<li>Installation Guide released.
<li>Use \'charset\' instead of \'language\' in the configuration file
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
<li>Fix the bug of \'required\' authentication model
</ul>
<p>
PHP News Reader v2.0.1 (2003/01/22)
<ul>
<li>Correct the link of SourceForge logo
</ul>
<p>
PHP News Reader v2.0 (2003/01/19)
<ul>
<li>First public release and hosts on SourceForge
<li>Rewrite the authentication codes and the use of the ticket system
<li>Logging out support
<li>Support \'optional\' authentication model for Read-Only access without authentication
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
<li>Couple of fixs for register_globals setting \'off\'
<li>HTML entities in Subject is now correctly escaped
<li>Mail forwarding bug fixed
<li>Check for valid E-Mail entered by user
</ul>
<p>
PHP News Reader v1.5 (released on 2002/07/06)
<ul>
<li>Multiple news server
<li>Run with PHP\'s register_globals setting turned off
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
';
echo "<font size=2>$pnews_claim</font><br>\n";
echo "<a href=http://sourceforge.net/projects/pnews/ target=_blank>http://sourceforge.net/projects/pnews/</a>\n";
echo '
</td><td align=right>
<a href="http://sourceforge.net/" alt="http://sourceforge.net/" target=_blank>
<img src="http://sourceforge.net/sflogo.php?group_id=71412&amp;type=1" border="0" alt="SourceForge.net Logo">
</a>
</td></tr></table>
</font>
</body>
</html>
';
?>
