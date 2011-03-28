-- Replacement

UPDATE wp_posts SET post_content = replace( post_content, 'video://', 'http://' ) ; -- replace inside post content
UPDATE wp_posts SET post_content = replace( post_content, 'cba', 'abc' ); -- text replace
