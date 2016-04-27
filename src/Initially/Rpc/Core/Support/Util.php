<?php
namespace Initially\Rpc\Core\Support;

use Initially\Rpc\Exception\InitiallyRpcException;

class Util
{

    /**
     * Record client config file info
     *
     * @param array $arr
     */
    public static function recordClientConfigFileInfo(array $arr)
    {
        $varDir = Constants::VAR_PATH;
        $cacheFilename = Constants::CACHE_CONFIG_INFO_FILENAME;
        self::createDirIfNotExists($varDir);
        $cacheFile = $varDir . "/" . $cacheFilename;
        file_put_contents($cacheFile, serialize($arr));
    }

    /**
     * Get client config file info
     *
     * @return mixed|null
     */
    public static function getClientConfigFileInfo()
    {
        $cacheFile = Constants::VAR_PATH . "/" . Constants::CACHE_CONFIG_INFO_FILENAME;
        if (!is_file($cacheFile)) {
            return null;
        }
        return unserialize(file_get_contents($cacheFile));
    }


    /**
     * Create directory if not exists
     *
     * @param string $dir
     * @throws InitiallyRpcException
     */
    public static function createDirIfNotExists($dir)
    {
        if (!is_dir($dir) && !@mkdir($dir, 0777, true)) {
            throw new InitiallyRpcException("create directory '{$dir}' failed");
        }
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