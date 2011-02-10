<?php
/*
 * Copyright (c) 2011 Roman Ožana. All rights reserved.
 * @author Roman Ožana <ozana@omdesign.cz>
 * @link www.omdesign.cz
 * @version 8.2.2011
 */


/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Forms
 */
function isValidEmailAddress($email)
{
  $atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
  $localPart = "(\"([ !\\x23-\\x5B\\x5D-\\x7E]*|\\\\[ -~])+\"|$atom+(\\.$atom+)*)"; // quoted or unquoted
  $chars = "a-z0-9\x80-\xFF"; // superset of IDN
  $domain = "[$chars]([-$chars]{0,61}[$chars])"; // RFC 1034 one domain component
  return (bool) preg_match("(^$localPart@($domain?\\.)+[a-z]{2,14}\\z)i", $email); // strict top-level domain
}


/**
 * Validate czech phone number
 * @param string $phone
 * @return boolean
 */
function isValidPhoneNumber($phone)
{
  return (bool) preg_match("/^(\+420)? ?\d{3} ?\d{3} ?\d{3}$/", $phone);
}


/* --------------------------------------------------------------------------
 * Ajax send email to admin email
 * -------------------------------------------------------------------------- */

// handle send_email action
add_action('wp_ajax_send_email', 'send_email');
add_action('wp_ajax_nopriv_send_email', 'send_email');


/**
 * Send email to admin from frontend
 */
function send_email()
{
  $kontakt = htmlspecialchars($_REQUEST['email']);
  $text = htmlspecialchars($_REQUEST['text']);

  /* --------------------------------------------------------------------------
   * Input check
   * -------------------------------------------------------------------------- */

  if ($_REQUEST['captcha'] !== 'Ostrava')
  {
    echo '<p class="error">Zpráva nebyla odeslána napište na <a href="mailto:' . get_settings('admin_email') . '">' . get_settings('admin_email') . '</a></p>';
    die();
  }

  if (!isValidPhoneNumber($kontakt) && !isValidEmailAddress($kontakt))
  {
    echo '<p class="error">Zadejte prosím platný kontakt.</p>';
    die();
  }

  /* --------------------------------------------------------------------------
   * render email header
   * -------------------------------------------------------------------------- */

  $header = "MIME-Version: 1.0\n";

  if (isValidEmailAddress($kontakt))
  {
    $header .= "From: \"$kontakt\" <$kontakt>\n";
  }
  else
  {
    $header .= "From: \"omRobot \" <" . get_settings('admin_email') . ">\n";
  }

  $header .= "Content-Type: text/html; charset=\"" . get_settings('blog_charset') . "\"\n";


  /* --------------------------------------------------------------------------
   * Render text
   * -------------------------------------------------------------------------- */
  
  $texy_class = dirname(__FILE__) . '/texy.min.php';
  if (file_exists($texy_class))
  {
    require_once $texy_class;
    $texy = new Texy();
    if (isValidPhoneNumber($kontakt)) $text .= "\n\nTelefon: $kontakt";
    $html = $texy->process($text);
  } else
  {
    $html = $text;
  }

  /* --------------------------------------------------------------------------
   * Send email
   * -------------------------------------------------------------------------- */

  if (wp_mail(get_settings('admin_email'), 'Kontaktní formulář', $html, $header))
  {
    echo '<p class="success">Vaše zpráva byla odeslána. Díky!</p><script>$(\'#text\').val(\'\');</script>';
  }
  else
  {
    echo '<p class="error">Zpráva nebyla odeslána, zkuste to později.</p>';
  }

  die();
}


/**
 * Render contact form
 */
function get_contact_form()
{
?>
  <div class="contact-form">
    <form action="index.php" method="POST" id="contact-form">
      <input type="hidden" id="action" name="action" value="send_email" />
      <p>
        <label for="email">Kontakt</label>
        <input type="text" id="email" name="email" value="" />
        <small>email nebo telefon</small>
      </p>
      <p>
        <label for="text">Vaše zpráva</label>
        <textarea id="text" name="text" cols="30" rows="10"></textarea>
      </p>

      <script type="text/javascript">/* <![CDATA[ */
        document.write('<input type="hidden" name="captcha" id="captcha" value="O' + 's' + 't' + 'r' + 'a' + 'v' + 'a" />');
        /* ]]> */
      </script>
      <noscript>
        <p><label for="captcha">Antispam: napište "<em>Ostrava</em>"</label><input id="captcha" name="captcha" value="" class="input" /></p>
      </noscript>

      <div id="response"></div>

      <p><input type="submit" id="submit" name="submit" class="button" value="Odeslat email" /></p>
    </form>
  </div>

  <script type="text/javascript">
    $(document).ready(function()
    {
      var emailPattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var phonePattern = new RegExp(/^(\+420)? ?\d{3} ?\d{3} ?\d{3}$/i);

      $("#contact-form").submit(function() {

        $('#response').html('');

        if (!emailPattern.test($('#email').val()) && !phonePattern.test($('#email').val()))
        {
          $('#response').html('<p class="error">Kontakt není zadaný správně</p>');
          return false;
        }

        if ($('#text').val() == '')
        {
          $('#response').html('<p class="error">Nenapsali jste mi žádnou zprávu</p>');
          return false;
        }

        $(this).addClass('wait');
        $.post('<?php bloginfo('siteurl') ?>/wp-admin/admin-ajax.php', $(this).serialize(), function(data) {
          $('#response').html(data);
          $(this).removeClass('wait');
        });

        return false;
      });
    });
  </script><?php
}


function contact_short_code()
{
  ob_start();
  get_contact_form();
  $contents = ob_get_contents();
  ob_end_clean();
  return $contents;
}


add_shortcode('kontakt', 'contact_short_code');