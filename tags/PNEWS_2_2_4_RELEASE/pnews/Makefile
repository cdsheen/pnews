
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

# Copyright (C) 2001-2003 - All rights reserved
# Shen Cheng-Da (cdsheen@users.sourceforge.net)

ZIPNAME=pnews224

all:	html	pkg

pkg:
	rm -rf $(ZIPNAME)*
	mkdir $(ZIPNAME)
	cp -r .htaccess *.php *.js examples doc language auth css $(ZIPNAME)
	rm -rf $(ZIPNAME)/examples/CVS
	rm -rf $(ZIPNAME)/language/CVS
	rm -rf $(ZIPNAME)/auth/CVS
	rm -rf $(ZIPNAME)/doc/CVS
	rm -rf $(ZIPNAME)/css/CVS
	tar zcvf $(ZIPNAME).tgz $(ZIPNAME)
	zip -r $(ZIPNAME) $(ZIPNAME)
	rm -rf $(ZIPNAME)
	mv -f $(ZIPNAME).tgz $(ZIPNAME).zip ..

html:
	php doc/index.php | tail +4 > doc/release.html

gb:
	b2g < language/chinese.inc.php | sed 's/Traditional/Simplified/' | sed 's/BIG5/GB2312/' > language/chinese_gb.inc.php

www:	html
	cp -f version.inc.php /home/users/c/cd/cdsheen/groups/
	cp -f doc/style.css doc/release.html doc/history.php doc/copying.php doc/requirement.php doc/guide.php doc/url_rewrite.php /home/users/c/cd/cdsheen/groups/htdocs/

clean:
	rm -f *~
