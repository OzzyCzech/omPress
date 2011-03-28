<?php
/* --------------------------------------------------------------------------
 * Unpack ZIP file
 * -------------------------------------------------------------------------- */

$zip = new ZipArchive();

if (file_exists('install.zip'))
{
  if ($zip->open('install.zip') === TRUE)
  {
    $zip->extractTo(dirname(__FILE__));
    $zip->close();
    echo 'unpack ok';
  }
  else
  {
    echo 'unpack failed';
  }
}