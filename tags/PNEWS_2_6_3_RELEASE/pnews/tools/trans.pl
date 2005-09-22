#!/usr/bin/perl

die "Usage: $0 [lang-files]\n" unless @ARGV > 0;

for( $i = 0 ; $i < @ARGV ; $i++ ) {
	open( LANG, $ARGV[$i] );
	open( LANG_NEW, '>' . $ARGV[$i] . '.new' );
	while( <LANG> ) {
		s/\$str(\w+)/\$pnews_str\[\1\]/g;
		print LANG_NEW;
	}
	close(LANG);
	close(LANG_NEW);
	rename( $ARGV[$i] . '.new', $ARGV[$i] );
}
