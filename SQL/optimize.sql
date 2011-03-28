-- Optimize

DELETE a,b,c FROM wp_posts a
LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id)
LEFT JOIN wp_postmeta c ON (a.ID = c.post_id)
WHERE a.post_type = 'revision'; -- delete revision

DELETE FROM wp_comments WHERE comment_approved = 'spam'; -- delete spam comments

OPTIMIZE TABLE `wp_commentmeta`, `wp_comments`, `wp_links`, `wp_options`, `wp_postmeta`, `wp_posts`, `wp_term_relationships`, `wp_term_taxonomy`, `wp_terms`, `wp_usermeta`, `wp_users`; -- optimaliza page

DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient%_feed_%'); -- delete feed cache from wordpress

-- http://www.catswhocode.com/blog/wordpress-10-life-saving-sql-queries