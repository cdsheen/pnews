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
Requirement
<hr>
The requirement for PHP News Reader on the server side:
<ul>
<li>Web Server with PHP modules installed ( PHP 4.1.0 or greater )
<li>News Server with <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980</a> Extensions
</ul>
<p>As for <a href="http://www.csie.nctu.edu.tw/~cdsheen/rfc/index.php?query=2980" target=_blank>RFC 2980</a>, PHP News Reader only requires the News Server to have the following extensions:<br>
<ul>
<li>LIST &nbsp;ACTIVE &nbsp;[wildmat]
<li>LIST &nbsp;NEWSGROUPS  &nbsp;[wildmat]
</ul>
The <a href="http://www.isc.org/products/INN/" target=_blank>INN News Server</a> package
 (with the version greater than <b><a href="http://www.isc.org/products/INN/">INN 1.5</a></b>) includes a NNRPD daemon to support this.
<p>
If your server is operated by INN, you should check about the NNRP access permission.
For example, when you telnet to an INN News server at port 119, you must get response like this: (the <b>NNRP</b> in bold)
<ul>
200 nopy.com InterNetNews <b>NNRP</b> server INN 2.3.2 ready (posting ok).
</ul>
instead of,
<ul>
200 nopy.com InterNetNews server INN 2.3.2 ready<br>
-or-<br>
502 You have no permission to talk.  Goodbye.
</ul>
<p>
The requirement for PHP News Reader on the client side (web browser) :
<ul>
<li>Support Cascade Style Sheet 1.0
<li>Support Javascript >= 1.2
<li>Support Document Object Model
<li>Accept Cookies (not necessary)
</ul>
The Mozilla 1.2 and Internet Explorer 5.5 works fine for me.
';
echo "<hr><table width=100% cellspacing=0 cellpadding=0><tr><td><font size=2>$pnews_claim</font><br>\n";
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
