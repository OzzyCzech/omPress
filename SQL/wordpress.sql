-- Wordpress SQL

UPDATE wp_options SET option_value = '' WHERE option_name = 'active_plugins'; -- disable all plugins