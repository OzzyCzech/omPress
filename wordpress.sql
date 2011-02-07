---------------------------------------
-- Optimize
---------------------------------------

DELETE a,b,c FROM wp_posts a
LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id)
LEFT JOIN wp_postmeta c ON (a.ID = c.post_id)
WHERE a.post_type = 'revision'; -- delete revision

DELETE FROM wp_comments WHERE comment_approved = 'spam'; -- delete spam comments

OPTIMIZE TABLE `wp_commentmeta`, `wp_comments`, `wp_links`, `wp_options`, `wp_postmeta`, `wp_posts`, `wp_term_relationships`, `wp_term_taxonomy`, `wp_terms`, `wp_usermeta`, `wp_users`; -- optimaliza page

DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient%_feed_%'); -- delete feed cache from wordpress

-- http://www.catswhocode.com/blog/wordpress-10-life-saving-sql-queries

---------------------------------------
-- Replacement
---------------------------------------

UPDATE wp_posts SET post_content = replace( post_content, 'video://', 'http://' ) ; -- replace inside post content
UPDATE wp_posts SET post_content = replace( post_content, 'cba', 'abc' ); -- text replace

---------------------------------------
-- Plugins
---------------------------------------

UPDATE wp_options SET option_value = '' WHERE option_name = 'active_plugins'; -- disable all plugins

---------------------------------------
-- Clean Wordpress after instalation
---------------------------------------

DELETE a,b,c FROM wp_posts a LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id) LEFT JOIN wp_postmeta c ON (a.ID = c.post_id); -- delete all posts
DELETE FROM wp_links; -- delete all links
DELETE FROM wp_commentmeta; -- delete all coment meta
DELETE FROM wp_comments; -- delete all coment meta

UPDATE wp_options SET option_value = '' WHERE option_name = 'blogdescription'; -- clean blog description
UPDATE wp_options SET option_value = 'admin@omdesign.cz' WHERE option_name = 'admin_email'; -- setup email
UPDATE wp_options SET option_value = '1' WHERE option_name = 'start_of_week'; -- change week start
UPDATE wp_options SET option_value = 'j.n.Y' WHERE option_name = 'date_format'; -- date format
UPDATE wp_options SET option_value = 'H:i' WHERE option_name = 'time_format'; -- time format
UPDATE wp_options SET option_value = '15' WHERE option_name = 'posts_per_rss'; -- post per RSS page
UPDATE wp_options SET option_value = '5' WHERE option_name = 'posts_per_page'; -- post per page
UPDATE wp_options SET option_value = '20' WHERE option_name = 'default_post_edit_rows'; -- default edit rows
UPDATE wp_options SET option_value = '/%postname%/' WHERE option_name = 'permalink_structure'; -- permalinks
UPDATE wp_options SET option_value = 'wp-content/uploads' WHERE option_name = 'upload_path'; -- path to upload multimedia

---------------------------------------
-- Exports and selects
---------------------------------------

SELECT DISTINCT comment_author_email FROM wp_comments; -- select all emails in comments