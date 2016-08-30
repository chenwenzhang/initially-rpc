<?php
namespace Initially\Rpc\Core\Support;

use Initially\Rpc\Exception\InitiallyRpcException;

class Util
{

    /**
     * 存在的目录是否可写
     *
     * @param $dir
     * @return bool
     */
    public static function existsDirWritable($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $testFile = $dir . "/write_file_" . time() . ".txt";
        if (!@file_put_contents($testFile, "WRITABLE TEST")) {
            return false;
        } else {
            @unlink($testFile);
        }

        return true;
    }
    
    /**
     * Create directory if not exists
     *
     * @param string $dir
     * @return bool
     */
    public static function createDirIfNotExists($dir)
    {
        if (!is_dir($dir) && !@mkdir($dir, 0777, true)) {
            return false;
        }

        return true;
    }

    /**
     * Check file is modify
     *
     * @param string $file
     * @param int $time
     * @return bool
     */
    public static function isFileModify($file, $time)
    {
        $fileModifyTime = filemtime($file);
        return $time !== $fileModifyTime;
    }

}