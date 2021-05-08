<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/11/10
 * Time: 10:22
 */

namespace app\utils;

class FileHelper
{
    /**
     * Write a string to a file
     * @param string $filename <p>
     * Path to the file where to write the data.
     * </p>
     * @param mixed $data <p>
     * The data to write. Can be either a string, an
     * array or a stream resource.
     * </p>
     * <p>
     * If data is a stream resource, the
     * remaining buffer of that stream will be copied to the specified file.
     * This is similar with using stream_copy_to_stream.
     * </p>
     * <p>
     * You can also specify the data parameter as a single
     * dimension array. This is equivalent to
     * file_put_contents($filename, implode('', $array)).
     * </p>
     * @return int|bool The function returns the number of bytes that were written to the file, or
     * false on failure.
     * @since 5.0
     */
    public static function filePutContents($filename, $data, $flags = 0, $context = null)
    {
        $dir = dirname($filename);
        self::mkDir($dir);
        return file_put_contents($filename, $data, $flags, $context);
    }

    /**
     * 创建目录（递归）
     * @return bool true on success or false on failure.
     */
    public static function mkDir($dir, $mode = 0777)
    {
        return is_dir($dir) or self::mkDir(dirname($dir), $mode) and mkdir($dir, $mode);
    }
}
