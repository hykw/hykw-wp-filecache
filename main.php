<?php
  /**
   * @package HYKW file cache plugin
   * @version 0.1
   */
  /*
    Plugin Name: HYKW file cache plugin
    Plugin URI: https://github.com/hykw/hykw-wp-filecache
    Description: ファイルキャッシュプラグイン
    Author: hitoshi-hayakawa
    Version: 0.1
  */

class hykwFileCache
{
  private $cachedir;
  private $obs_min;

  # キャッシュディレクトリ, キャッシュの寿命（分単位）
  # e.g.
  #   ('/tmp/cache', 1440)
  function __construct($cachedir, $cache_obs_min = 1440)
  {
    $this->cachedir = $cachedir;
    $this->obs_min = $cache_obs_min;

    # ディレクトリが無かったら作っとく
    if (!file_exists($cachedir))
      mkdir($cachedir, 0755);
  }

  # not found/obsolete の場合、FALSEを返す
  function readData($filename)
  {
    $cache_dirFile = sprintf('%s/%s', $this->cachedir, $filename);
    if (!file_exists($cache_dirFile))
      return FALSE;

    $ctime = @filectime($cache_dirFile);
    if ( ($ctime == FALSE) || (($ctime + $this->obs_min*60) < time()) ) {
      # キャッシュは無効
      unlink($cache_dirFile);
      return FALSE;
    }

    return file_get_contents($cache_dirFile);
  }
  

  function writeData($filename, $content)
  {
    $cache_dirFile = sprintf('%s/%s', $this->cachedir, $filename);
    $fp = fopen($cache_dirFile, 'w');
    fputs($fp, $content);
    fclose($fp);
  }

}
