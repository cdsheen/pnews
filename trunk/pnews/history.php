<?

require_once('version.inc.php');

echo '<html>
<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=big5">
<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
<title>PHP News Reader</title>
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
Release histroy
<hr>
PHP News Reader v2.0
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
<p>
Future works:
<ul>
<li>Article search functionality
<li>Cross post with smart group selection
<li>MIME content support
</ul>
<p>
The source code of PHP News Reader v2.0 will be released soon.
I think I need to write some documents before make it public.
If you really need this program, you are welcomed to write a mail to me,
telling me your plan, I\'ll see how I can help.
<p>
Any bug report or functional suggestion is appreciated. Please send your
requests by mail or leave a messages on the web.
<p>
PHP News Reader official site:
<blockquote>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/php-news/" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/php-news/</a>
</blockquote>
<p>
Demo:
<blockquote>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/news/" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/news/</a>
</blockquote>
<p>
Suggestion Board:
<blockquote>
<a href="http://www.csie.nctu.edu.tw/~cdsheen/php-news/board.php" target=_blank>http://www.csie.nctu.edu.tw/~cdsheen/php-news/board.php</a>
</blockquote>
<p>
Author:
<blockquote>
Shen Cheng-Da<br>
Taipei, Taiwan<br>
+886-926-356815<br>
ICQ: <a href="http://wwp.icq.com/scripts/search.dll?to=73013633" title="Add ME">73013633</a><br>
<a href="mailto:cdsheen@csie.nctu.edu.tw">cdsheen@csie.nctu.edu.tw</a><br>
<a target="_blank" href="http://www.csie.nctu.edu.tw/~cdsheen/">
http://www.csie.nctu.edu.tw/~cdsheen/</a>
</blockquote>
<hr>
';
echo "<font size=2>$pnews_claim</font>\n";
echo '
</font>
</body>
</html>
';
?>
