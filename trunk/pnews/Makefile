
# PHP News Reader - Makefile

# Copyright (C) 2001-2003 - All rights reserved
# Shen Cheng-Da (cdsheen@csie.nctu.edu.tw)

VERSION=2.0

all:	gb	html	pkg

pkg:
	rm -rf pnews-$(VERSION)*
	mkdir pnews-$(VERSION)
	cp -r *.php *.css *.js *.html examples language auth pnews-$(VERSION)
	rm -rf pnews-$(VERSION)/examples/CVS
	rm -rf pnews-$(VERSION)/language/CVS
	rm -rf pnews-$(VERSION)/auth/CVS
	rm -f  pnews-$(VERSION)/examples/newsgroups-giga.lst
	tar zcvf pnews-$(VERSION).tgz pnews-$(VERSION)
	rm -rf pnews-$(VERSION)

html:
	/usr/local/bin/php release.php | tail +4 > release.html

gb:
	b2g < language/chinese.inc.php | sed 's/Traditional/Simplified/' | sed 's/BIG5/GB2312/' > language/chinese_gb.inc.php

clean:
	rm -f *~
