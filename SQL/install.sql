-- Clean Wordpress after instalation

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