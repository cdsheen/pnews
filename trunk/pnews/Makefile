
# PHP News Reader
# Copyright (C) 2001-2007 Shen Cheng-Da
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

# Copyright (C) 2001-2007 - All rights reserved
# Shen Cheng-Da (cdsheen at users.sourceforge.net)

ZIPNAME=pnews265

all:	pkg

pkg:
	rm -rf $(ZIPNAME)*
	mkdir $(ZIPNAME)
	cp -r .htaccess *.php *.js favicon.ico examples doc language auth css $(ZIPNAME)
	chmod 600 $(ZIPNAME)/clear-cache.php
	rm -rf $(ZIPNAME)/examples/CVS
	rm -rf $(ZIPNAME)/language/CVS
	rm -rf $(ZIPNAME)/auth/CVS
	rm -rf $(ZIPNAME)/doc/CVS
	rm -rf $(ZIPNAME)/css/CVS
	tar zcvf $(ZIPNAME).tgz $(ZIPNAME)
	zip -r $(ZIPNAME) $(ZIPNAME)
	rm -rf $(ZIPNAME)

upload:
	ncftpput upload.sourceforge.net /incoming pnews265.tgz
	ncftpput upload.sourceforge.net /incoming pnews265.zip

gb:
	b2g < language/chinese.inc.php | sed 's/Traditional/Simplified/' | sed 's/BIG5/GB2312/' > language/chinese_b2g.inc.php

www:
	cp -f favicon.ico version.inc.php /home/users/c/cd/cdsheen/groups/
	cp -f doc/style.css doc/header.php doc/history.php doc/copying.php doc/requirement.php doc/guide.php doc/url_rewrite.php doc/acknowlege.php doc/download.php /home/users/c/cd/cdsheen/groups/htdocs/
	cp -f doc/index.php /home/users/c/cd/cdsheen/groups/htdocs/index.php

clean:
	rm -f *~

update:
	cvs update -dP

commit:
	cvs ci

ci:
	cvs ci

