<?php
/**
 * Basic wordpress 
 * Copyright (c) 2011 Roman Ožana. All rights reserved.
 * @author Roman Ožana <ozana@omdesign.cz>
 * @link www.omdesign.cz
 */
class om
{


  /**
   * Generate pagination link from $wp_query
   * @global WP_Query $wp_query
   * @return string
   */
  public static function paginator()
  {
    global $wp_query;
    $pagination = array (
        'base' => str_replace('91919', '%#%', get_pagenum_link(91919)),
        'format' => '',
        'total' => ceil($wp_query->found_posts / get_settings('posts_per_page')),
        'current' => absint(get_query_var('paged')),
        'end_size' => 2,
        'mid_size' => 3,
    );

    $total_pages = (!absint($wp_query->max_num_pages)) ? 1 : absint($wp_query->max_num_pages);

    if ($total_pages > 1)
    {
      $after = (absint(get_query_var('paged')) == 0) ? '<a href="' . get_pagenum_link('2') . '">' . __('Next &raquo;') . '</a>' : '';
      return '<div class="paginator">' . paginate_links($pagination) . $after . '</div>';
    }
    else
    {
      return;
    }
  }


  /**
   * Shorten version of post
   * @param string $text
   * @param integer $excerpt_length
   * @param string $excerpt_more
   * @param boolean $apply_filters
   * @return string
   */
  public static function the_excerpt($text, $excerpt_length = 55, $excerpt_more = '&hellip;', $apply_filters = false)
  {
    $text = strip_shortcodes($text);

    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $text = strip_tags($text);

    if ($apply_filters)
    {
      $excerpt_length = apply_filters('excerpt_length', 55);
      $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    }

    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if (count($words) > $excerpt_length)
    {
      array_pop($words);
      $text = implode(' ', $words);
      $text = $text . $excerpt_more;
    }
    else
    {
      $text = implode(' ', $words);
    }
    return $text;
  }


  /**
   * Render human time diff
   *  - almost same function as human_time_diff();
   * @param integer $from
   * @param integer $to
   * @return string
   */
  public static function human_time_diff($from, $to = null)
  {
    if ($to == null) $to = time();
    $diff = (int) abs($to - $from);

    if ($diff <= 3600)
    {
      $mins = round($diff / 60);
      if ($mins <= 1)
      {
        $mins = 1;
      }
      /* translators: min=minute */
      $since = sprintf(_n('%s min', '%s mins', $mins), $mins);
    }
    else if (($diff <= 86400) && ($diff > 3600))
    {
      $hours = round($diff / 3600);
      if ($hours <= 1)
      {
        $hours = 1;
      }
      $since = sprintf(_n('%s hour', '%s hours', $hours), $hours);
    }
    elseif ($diff >= 86400)
    {
      $days = round($diff / 86400);
      if ($days <= 1)
      {
        $days = 1;
      }

      $since = sprintf(_n('%s day', '%s days', $days), $days);

      // more then 2 weeks
      if ($days > 14)
      {
        $date = date(get_option('date_format'), $from);

        $since = apply_filters('get_the_date', $date, get_option('date_format'));
      }
    }
    return $since;
  }
  
 /**
   * Get page content by slug
   */
  public static function get_page($slug, $args = array ())
  {
    $defaults = array (
        'before' => '',
        'after' => '',
        'before_content' => '',
        'after_content' => '',
        'show_title' => 0,
        'before_title' => '',
        'after_title' => '',
        'echo' => 1
    );

    $r = wp_parse_args($args, $defaults);
    extract($r, EXTR_SKIP);

    $page = new WP_Query('pagename=' . $slug);

    if ($page->have_posts())
    {
      $page->the_post();
      $title = ( $show_title) ? get_the_title() : '';

      $content = get_the_content();
      $content = apply_filters('the_content', $content); // filtr na obsah
      $content = str_replace(']]>', ']]&gt;', $content);

      return $before . $before_title . $title . $after_title . $before_content . $content . $after_content . $after;
    }
    else
    {
      return '';
    }
  }

}