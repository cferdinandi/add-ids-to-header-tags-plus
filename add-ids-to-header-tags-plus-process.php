<?php

require_once( dirname( __FILE__) . '/add-ids-to-header-tags-plus-options.php' );

function add_ids_to_header_tags( $content ) {

	if ( ! is_single() ) {
		return $content;
	}

	$link_text = addIDs_get_link_text();
	$pattern = '#(?P<full_tag><(?P<tag_name>h\d)(?P<tag_extra>[^>]*)>(?P<tag_contents>[^<]*)</h\d>)#i';

	if ( preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
		$find = array();
		$replace = array();
		foreach( $matches as $match ) {

			if ( strlen( $match['tag_extra'] ) && false !== stripos( $match['tag_extra'], 'id=' ) ) {
				continue;
			}

			$find[]    = $match['full_tag'];
			$id        = sanitize_title( $match['tag_contents'] );
			$id_attr   = sprintf( ' id="%s"', $id );

			if ( $link_text === '' ) {
				$replace[] = sprintf( '<%1$s%2$s%3$s>%4$s</%1$s>', $match['tag_name'], $match['tag_extra'], $id_attr, $match['tag_contents'] );
			} else {
				$extra     = sprintf( ' <a href="#%s">' . $link_text . '</a>', $id );
				$replace[] = sprintf( '<%1$s%2$s%3$s>%4$s%5$s</%1$s>', $match['tag_name'], $match['tag_extra'], $id_attr, $match['tag_contents'], $extra );
			}

		}
		$content = str_replace( $find, $replace, $content );
	}

	return $content;
}
add_filter( 'the_content', 'add_ids_to_header_tags' );