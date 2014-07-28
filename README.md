hykw-wp-filecache
====================

# 動作
## 指定ファイル名のキャッシュを確認
- あれば単に返す
- 無い、あるいはタイムスタンプが指定間隔よりも古い場合は上書き作成(未指定は24時間）

    Usage:
      $objCache = new hykwFileCache($dir_cache_custom_href);

      $cacheFileName = sha1($content);
      $ret = $objCache->readData($cacheFileName);
      if ($ret != FALSE)
        return $ret;
                 ・
                 ・
                 ・
                 ・
      $objCache->writeData($cacheFileName, $content);

