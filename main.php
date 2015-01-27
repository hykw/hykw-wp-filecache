<?php
  /**
   * @package HYKW file cache plugin
   * @version 1.0
   */
  /*
    Plugin Name: HYKW file cache plugin
    Plugin URI: https://github.com/hykw/hykw-wp-filecache
    Description: ファイルキャッシュプラグイン
    Author: Hitoshi Hayakawa
  */

class hykwFileCache
{
  private $cachedir;
  private $obs_min;

  /**
   * __construct コンストラクタ
   * 
   例）
   <pre>
     $obj = new hykwFileCache('/tmp/cache', 1440);
   <pre>
   * 
   * @param string $cachedir キャッシュディレクトリ
   * @param integer $cache_obs_min キャッシュの寿命（分単位）
   */
  function __construct($cachedir, $cache_obs_min = 1440)
  {
    $this->cachedir = $cachedir;
    $this->obs_min = $cache_obs_min;

    # ディレクトリが無かったら作っとく
    if (!file_exists($cachedir))
      mkdir($cachedir, 0755);
  }

  /**
   * readData キャッシュからデータを取得する
   * 
   * @param string $filename キャッシュファイル名
   * @return string 取得したデータ（見つからない場合、あるいはexpireしてる場合はFALSE）
   */
  function readData($filename)
  {
    $cache_dirFile = sprintf('%s/%s', $this->cachedir, $filename);
    if (!file_exists($cache_dirFile))
      return FALSE;

    $ctime = @filectime($cache_dirFile);
    if ( ($ctime == FALSE) || (($ctime + $this->obs_min*60) < time()) ) {
      # キャッシュは無効
      @unlink($cache_dirFile);
      return FALSE;
    }

    return file_get_contents($cache_dirFile);
  }
  

  /**
   * writeData キャッシュにデータを書き込む
   * 
   * @param string $filename キャッシュファイル名
   * @param string $content 書き込むデータ
   */
  function writeData($filename, $content)
  {
    $cache_dirFile = sprintf('%s/%s', $this->cachedir, $filename);
    $fp = fopen($cache_dirFile, 'w');
    fputs($fp, $content);
    fclose($fp);
  }

}
