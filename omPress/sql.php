<?php
/**
 * Copyright (c) 2011 Roman Ožana. All rights reserved.
 * @author Roman Ožana <ozana@omdesign.cz>
 * @link www.omdesign.cz
 */
/**
 * @package omPress
 * @link http://www.catswhocode.com/blog/wordpress-10-life-saving-sql-queries
 */
class omPressSQL
{


  /**
   * Delete all wordpress post revisons
   */
  public static function deleteAllRevisons()
  {
    global $wpdb;
    /* @var $wpdb wpdb */


    $sql = "DELETE a,b,c FROM " . $wpdb->posts . " a
            LEFT JOIN " . $wpdb->term_relationships . " b ON (a.ID = b.object_id)
            LEFT JOIN " . $wpdb->postmeta . " c ON (a.ID = c.post_id)
            WHERE a.post_type = 'revision';";

    return self::sql($sql);
  }


  /**
   * Deactivate all plugins 
   */
  private function deactivateAllPlugins()
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    return self::sql("UPDATE " . $wpdb->options . " SET option_value = '' WHERE option_name = 'active_plugins'");
  }


  /**
   * Delete all coment spam
   */
  public static function deleteAllCommentSpam()
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    return self::sql("DELETE FROM " . $wpdb->comments . " WHERE comment_approved = 'spam';");
  }


  /**
   * Delete whole feed cache from wordpress
   */
  public static function deleteFeedCache()
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    return self::sql("DELETE FROM `" . $wpdb->options . "` WHERE `option_name` LIKE ('_transient%_feed_%')");
  }


  /**
   * Optimize all tables
   */
  public static function optimizeTables()
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    $table[] = $wpdb->commentmeta;
    $table[] = $wpdb->comments;
    $table[] = $wpdb->links;
    $table[] = $wpdb->options;
    $table[] = $wpdb->postmeta;
    $table[] = $wpdb->posts;
    $table[] = $wpdb->term_relationships;
    $table[] = $wpdb->term_taxonomy;
    $table[] = $wpdb->terms;
    $table[] = $wpdb->usermeta;
    $table[] = $wpdb->users;

    $sql = "OPTIMIZE TABLE " . implode(',', $table) . ";";

    return self::sql($sql);
  }


  /**
   * Replace text in content
   */
  private function replacePostContent($text, $with)
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    return self::sql("UPDATE ".$wpdb->posts." SET post_content = replace(post_content, '" . $text . "', '" . $with . "')");
  }


  /**
   * Make default database settings after Wordpress instalation
   */
  public static function defaultDatabaseSettings()
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    // delete all posts
    self::sql("DELETE a,b,c
               FROM " . $wpdb->posts . " a 
               LEFT JOIN " . $wpdb->term_relationships . " b ON (a.ID = b.object_id)
               LEFT JOIN " . $wpdb->postmeta . " c ON (a.ID = c.post_id);");

    self::sql("DELETE FROM " . $wpdb->links);
    self::sql("DELETE FROM " . $wpdb->commentmeta);
    self::sql("DELETE FROM " . $wpdb->comments);

    if (!function_exists('update_option'))
        throw new Exception('Function update_option not exists');

    update_option('blogdescription', '');
    update_option('admin_email', 'admin@omdesign.cz');
    update_option('start_of_week', '1');
    update_option('date_format', 'j.n.Y');
    update_option('time_format', 'H:i');
    update_option('posts_per_rss', '15');
    update_option('posts_per_page', '5');
    update_option('default_post_edit_rows', '30');
    update_option('permalink_structure', '/%postname%/');
    update_option('upload_path', 'wp-content/uploads');
  }


  /* --------------------------------------------------------------------------
   * Private functions
   * -------------------------------------------------------------------------- */


  /**
   * Run SQL query
   */
  private static function sql()
  {
    global $wpdb;
    /* @var $wpdb wpdb */

    if (isset($wpdb))
    {
      return $wpdb->query($sql);
    }
    else
    {
      throw new Exception('Database connection object $wpdb isn\'t set');
    }
  }


}
