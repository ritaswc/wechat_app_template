<?php

namespace Hejiang\Storage;

class UploadedFile extends \yii\web\UploadedFile
{
    /**
     * Driver interface
     *
     * @var \Hejiang\Drivers\BaseDriver
     */
    protected $driver;

    /**
     * Storage base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Override `saveAs` method to call specified driver
     *
     * @param string $file
     * @param boolean $deleteTempFile
     * @return string|false A URL to access this file
     */
    public function saveAs($file, $deleteTempFile = true)
    {
        $result = false;
        if ($this->error == UPLOAD_ERR_OK && is_uploaded_file($this->tempName)) {
            $result = $this->driver->saveFile($this->tempName, $this->getFullPath($file));
        }
        if ($result && $deleteTempFile) {
            $this->deleteTempFile();
        }
        return $result;
    }

    /**
     * Save the uploaded file with original file extension
     *
     * @param string $baseName
     * @param boolean $deleteTempFile
     * @return string|false A URL to access this file
     */
    public function saveWithOriginalExtension($baseName, $deleteTempFile = true)
    {
        return $this->saveAs($baseName . '.' . $this->getExtension(), $deleteTempFile);
    }

    /**
     * Save the uploaded file as a unique hash name
     *
     * @return string|false A URL to access this file
     */
    public function saveAsUniqueHash()
    {
        $uniqueName = sha1_file($this->tempName);
        if ($uniqueName == false) {
            return false;
        }
        return $this->saveWithOriginalExtension($uniqueName);
    }

    /**
     * Concat `self::$basePath` and file name
     *
     * @param string $file
     * @return string
     */
    protected function getFullPath($file)
    {
        return rtrim($this->basePath, '/')
                . '/'
                . ltrim($file, '/');
    }

    /**
     * Delete uploaded temp file
     *
     * @return bool
     */
    public function deleteTempFile()
    {
        return unlink($this->tempName);
    }

    public static function getInstanceByStorage($name, $driver, $basePath)
    {
        $instance = parent::getInstanceByName($name);
        if ($instance === null) {
            return null;
        }
        $instance->driver = $driver;
        $instance->basePath = $basePath;
        return $instance;
    }
}
