<?php

namespace Hejiang\Storage\Drivers;

use Hejiang\Storage\Exceptions\StorageException;

class Local extends BaseDriver
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function put($localFile, $saveTo)
    {
        $saveTo = \Yii::$app->basePath . '/' . $saveTo;
        $saveDir = dirname($saveTo);
        try {
            $res = false;
            if (!is_dir($saveDir) && !mkdir($saveDir, 0777, true)) {
                return false;
            }
            if (!copy($localFile, $saveTo)) {
                return false;
            }
        } catch (\Exception $ex) {
            throw new StorageException($ex->getMessage());
        }
        $accessUrl = \Yii::$app->request->hostInfo . '/' . static::getRelativePath(realpath($_SERVER['DOCUMENT_ROOT']), $saveTo);
        return $accessUrl;
    }

    public static function getRelativePath($from, $to)
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to = is_dir($to) ? rtrim($to, '\/') . '/' : $to;
        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);

        $from = explode('/', $from);
        $to = explode('/', $to);
        $relPath = $to;

        foreach ($from as $depth => $dir) {
            // find first non-matching dir
            if (strcasecmp($dir, $to[$depth]) === 0) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    // $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }
}
