<?php
/**
 * Copyright (c) 2011 Roman Ožana. All rights reserved.
 * @author Roman Ožana <ozana@omdesign.cz>
 * @link www.omdesign.cz
 */
/**
 * 
 * @package omPress
 */
class omPress
{

  /**
   * 
   */
  public static function databaseBackup()
  {
    
  }


  /**
   * Build 
   */
  public static function build()
  {
    self::improveDatabase();
  }


  /**
   * Improve database
   */
  private static function improveDatabase()
  {
    self::log('delete comment spam');
    omPressSQL::deleteAllCommentSpam();

    self::log('delete post revisions');
    omPressSQL::deleteAllRevisons();

    self::log('optimizing tables');
    omPressSQL::optimizeTables();

    self::log('delete feed cache');
    omPressSQL::deleteFeedCache();
  }


  /**
   * Log text to console
   * @param string $text 
   */
  private static function log($text)
  {
    echo $text . "\n";
  }


}

/* --------------------------------------------------------------------------
 * Process application call
 * -------------------------------------------------------------------------- */


require_once __DIR__ . '/sql.php'; // sql tools
require_once __DIR__ . '/../wp-config.php'; // wp-config.php


if (isset($_SERVER['argv'][1]))
{
  omPress::$_SERVER['argv'][1]();
}
