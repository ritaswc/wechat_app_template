<?php namespace Comodojo\Zip;

use \ZipArchive;
use \Comodojo\Exception\ZipException;

/**
 * comodojo/zip - ZipArchive toolbox
 *
 * This class provide methods to handle single zip archive
 *
 * @package     Comodojo Spare Parts
 * @author      Marco Giovinazzi <marco.giovinazzi@comodojo.org>
 * @license     MIT
 *
 * LICENSE:
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Zip {

    /**
     * Select files to skip
     *
     * @var string
     */
    private $skip_mode = "NONE";

    /**
     * Supported skip modes
     *
     * @var bool
     */
    private $supported_skip_modes = array("HIDDEN", "COMODOJO", "ALL", "NONE");

    /**
     * Mask for the extraction folder (if it should be created)
     *
     * @var int
     */
    private $mask = 0777;

    /**
     * ZipArchive internal pointer
     *
     * @var object
     */
    private $zip_archive = null;

    /**
     * zip file name
     *
     * @var string
     */
    private $zip_file = null;

    /**
     * zip file password (only for extract)
     *
     * @var string
     */
    private $password = null;

    /**
     * Current base path
     *
     * @var string
     */
    private $path = null;

    /**
     * Array of well known zip status codes
     *
     * @var array
     */
    private static $zip_status_codes = Array(
        ZipArchive::ER_OK           => 'No error',
        ZipArchive::ER_MULTIDISK    => 'Multi-disk zip archives not supported',
        ZipArchive::ER_RENAME       => 'Renaming temporary file failed',
        ZipArchive::ER_CLOSE        => 'Closing zip archive failed',
        ZipArchive::ER_SEEK         => 'Seek error',
        ZipArchive::ER_READ         => 'Read error',
        ZipArchive::ER_WRITE        => 'Write error',
        ZipArchive::ER_CRC          => 'CRC error',
        ZipArchive::ER_ZIPCLOSED    => 'Containing zip archive was closed',
        ZipArchive::ER_NOENT        => 'No such file',
        ZipArchive::ER_EXISTS       => 'File already exists',
        ZipArchive::ER_OPEN         => 'Can\'t open file',
        ZipArchive::ER_TMPOPEN      => 'Failure to create temporary file',
        ZipArchive::ER_ZLIB         => 'Zlib error',
        ZipArchive::ER_MEMORY       => 'Malloc failure',
        ZipArchive::ER_CHANGED      => 'Entry has been changed',
        ZipArchive::ER_COMPNOTSUPP  => 'Compression method not supported',
        ZipArchive::ER_EOF          => 'Premature EOF',
        ZipArchive::ER_INVAL        => 'Invalid argument',
        ZipArchive::ER_NOZIP        => 'Not a zip archive',
        ZipArchive::ER_INTERNAL     => 'Internal error',
        ZipArchive::ER_INCONS       => 'Zip archive inconsistent',
        ZipArchive::ER_REMOVE       => 'Can\'t remove file',
        ZipArchive::ER_DELETED      => 'Entry has been deleted'
    );

    /**
     * Class constructor
     *
     * @param   string  $zip_file   ZIP file name
     *
     * @throws  \Comodojo\Exception\ZipException
     */
    public function __construct($zip_file) {

        if ( empty($zip_file) ) throw new ZipException(self::getStatus(ZipArchive::ER_NOENT));

        $this->zip_file = $zip_file;

    }

    /**
     * Open a zip archive
     *
     * @param   string  $zip_file   ZIP file name
     *
     * @return  \Comodojo\Zip\Zip
     * @throws  \Comodojo\Exception\ZipException
     */
    public static function open($zip_file) {

        try {

            $zip = new Zip($zip_file);

            $zip->setArchive(self::openZipFile($zip_file));

        } catch (ZipException $ze) {

            throw $ze;

        }

        return $zip;

    }

    /**
     * Check a zip archive
     *
     * @param   string  $zip_file   ZIP file name
     *
     * @return  bool
     * @throws  \Comodojo\Exception\ZipException
     */
    public static function check($zip_file) {

        try {

            $zip = self::openZipFile($zip_file, ZipArchive::CHECKCONS);

            $zip->close();

        } catch (ZipException $ze) {

            throw $ze;

        }

        return true;

    }

    /**
     * Create a new zip archive
     *
     * @param   string  $zip_file   ZIP file name
     * @param   bool    $overwrite  overwrite existing file (if any)
     *
     * @return  \Comodojo\Zip\Zip
     * @throws  \Comodojo\Exception\ZipException
     */
    public static function create($zip_file, $overwrite = false) {

        $overwrite = filter_var($overwrite, FILTER_VALIDATE_BOOLEAN, array(
            "options" => array(
                "default" => false
            )
        ));

        try {

            $zip = new Zip($zip_file);

            if ( $overwrite ) $zip->setArchive(self::openZipFile($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE));

            else $zip->setArchive(self::openZipFile($zip_file, ZipArchive::CREATE));

        } catch (ZipException $ze) {

            throw $ze;

        }

        return $zip;

    }

    /**
     * Set files to skip
     *
     * @param   string  $mode   [HIDDEN, COMODOJO, ALL, NONE]
     *
     * @return  \Comodojo\Zip\Zip
     * @throws  \Comodojo\Exception\ZipException
     */
    final public function setSkipped($mode) {

        $mode = strtoupper($mode);

        if ( !in_array($mode, $this->supported_skip_modes) ) throw new ZipException("Unsupported skip mode");

        $this->skip_mode = $mode;

        return $this;

    }

    /**
     * Get current skip mode (HIDDEN, COMODOJO, ALL, NONE)
     *
     * @return  string
     */
    final public function getSkipped() {

        return $this->skip_mode;

    }

    /**
     * Set extraction password
     *
     * @param   string  $password
     *
     * @return  \Comodojo\Zip\Zip
     */
    final public function setPassword($password) {

        $this->password = $password;

        return $this;

    }

    /**
     * Get current extraction password
     *
     * @return  string
     */
    final public function getPassword() {

        return $this->password;

    }

    /**
     * Set current base path (just to add relative files to zip archive)
     *
     * @param   string  $path
     *
     * @return  \Comodojo\Zip\Zip
     * @throws  \Comodojo\Exception\ZipException
     */
    final public function setPath($path) {

        if ( !file_exists($path) ) throw new ZipException("Not existent path");

        $this->path = $path[strlen($path) - 1] == "/" ? $path : $path."/";

        return $this;

    }

    /**
     * Get current base path
     *
     * @return  string
     */
    final public function getPath() {

        return $this->path;

    }

    /**
     * Set extraction folder mask
     *
     * @param   int     $mask
     *
     * @return  \Comodojo\Zip\Zip
     */
    final public function setMask($mask) {

        $mask = filter_var($mask, FILTER_VALIDATE_INT, array(
            "options" => array(
                "max_range" => 0777,
                "default" => 0777
            ), 'flags' => FILTER_FLAG_ALLOW_OCTAL
        ));

        $this->mask = $mask;

        return $this;

    }

    /**
     * Get current extraction folder mask
     *
     * @return  int
     */
    final public function getMask() {

        return $this->mask;

    }

    /**
     * Set the current ZipArchive object
     *
     * @param   \ZipArchive     $zip
     *
     * @return  \Comodojo\Zip\Zip
     */
    final public function setArchive(ZipArchive $zip) {

        $this->zip_archive = $zip;

        return $this;

    }

    /**
     * Get current ZipArchive object
     *
     * @return  \ZipArchive
     */
    final public function getArchive() {

        return $this->zip_archive;

    }

    /**
     * Get current zip file
     *
     * @return  string
     */
    final public function getZipFile() {

        return $this->zip_file;

    }

    /**
     * Get a list of files in archive (array)
     *
     * @return  array
     * @throws  \Comodojo\Exception\ZipException
     */
    public function listFiles() {

        $list = Array();

        for ( $i = 0; $i < $this->zip_archive->numFiles; $i++ ) {

            $name = $this->zip_archive->getNameIndex($i);

            if ( $name === false ) throw new ZipException(self::getStatus($this->zip_archive->status));

            array_push($list, $name);

        }

        return $list;

    }

    /**
     * Extract files from zip archive
     *
     * @param   string  $destination    Destination path
     * @param   mixed   $files          (optional) a filename or an array of filenames
     *
     * @return  bool
     * @throws  \Comodojo\Exception\ZipException
     */
    public function extract($destination, $files = null) {

        if ( empty($destination) ) throw new ZipException('Invalid destination path');

        if ( !file_exists($destination) ) {

            $omask = umask(0);

            $action = mkdir($destination, $this->mask, true);

            umask($omask);

            if ( $action === false ) throw new ZipException("Error creating folder ".$destination);

        }

        if ( !is_writable($destination) ) throw new ZipException('Destination path not writable');

        if ( is_array($files) && @sizeof($files) != 0 ) {

            $file_matrix = $files;

        } else {

            $file_matrix = $this->getArchiveFiles();

        }

        if ( !empty($this->password) ) $this->zip_archive->setPassword($this->password);

        $extract = $this->zip_archive->extractTo($destination, $file_matrix);

        if ( $extract === false ) throw new ZipException(self::getStatus($this->zip_archive->status));

        return true;

    }

    /**
     * Add files to zip archive
     *
     * @param   mixed   $file_name_or_array     filename to add or an array of filenames
     * @param   bool    $flatten_root_folder    in case of directory, specify if root folder should be flatten or not
     *
     * @return  \Comodojo\Zip\Zip
     * @throws  \Comodojo\Exception\ZipException
     */
    public function add($file_name_or_array, $flatten_root_folder = false) {

        if ( empty($file_name_or_array) ) throw new ZipException(self::getStatus(ZipArchive::ER_NOENT));

        $flatten_root_folder = filter_var($flatten_root_folder, FILTER_VALIDATE_BOOLEAN, array(
            "options" => array(
                "default" => false
            )
        ));

        try {

            if ( is_array($file_name_or_array) ) {

                foreach ( $file_name_or_array as $file_name ) $this->addItem($file_name, $flatten_root_folder);

            } else $this->addItem($file_name_or_array, $flatten_root_folder);

        } catch (ZipException $ze) {

            throw $ze;

        }

        return $this;

    }

    /**
     * Delete files from zip archive
     *
     * @param   mixed   $file_name_or_array     filename to delete or an array of filenames
     *
     * @return  \Comodojo\Zip\Zip
     * @throws  \Comodojo\Exception\ZipException
     */
    public function delete($file_name_or_array) {

        if ( empty($file_name_or_array) ) throw new ZipException(self::getStatus(ZipArchive::ER_NOENT));

        try {

            if ( is_array($file_name_or_array) ) {

                foreach ( $file_name_or_array as $file_name ) $this->deleteItem($file_name);

            } else $this->deleteItem($file_name_or_array);

        } catch (ZipException $ze) {

            throw $ze;

        }

        return $this;

    }

    /**
     * Close the zip archive
     *
     * @return  bool
     * @throws  \Comodojo\Exception\ZipException
     */
    public function close() {

        if ( $this->zip_archive->close() === false ) throw new ZipException(self::getStatus($this->zip_archive->status));

        return true;

    }

    /**
     * Get a list of file contained in zip archive before extraction
     *
     * @return  array
     */
    private function getArchiveFiles() {

        $list = array();

        for ( $i = 0; $i < $this->zip_archive->numFiles; $i++ ) {

            $file = $this->zip_archive->statIndex($i);

            if ( $file === false ) continue;

            $name = str_replace('\\', '/', $file['name']);

            if ( $name[0] == "." AND in_array($this->skip_mode, array("HIDDEN", "ALL")) ) continue;

            if ( $name[0] == "." AND @$name[1] == "_" AND in_array($this->skip_mode, array("COMODOJO", "ALL")) ) continue;

            array_push($list, $name);

        }

        return $list;

    }

    /**
     * Add item to zip archive
     *
     * @param   string $file       File to add (realpath)
     * @param   bool   $flatroot   (optional) If true, source directory will be not included
     * @param   string $base       (optional) Base to record in zip file
     *
     * @throws  \Comodojo\Exception\ZipException
     */
    private function addItem($file, $flatroot = false, $base = null) {

        $file = is_null($this->path) ? $file : $this->path.$file;

        $real_file = str_replace('\\', '/', realpath($file));

        $real_name = basename($real_file);

        if ( !is_null($base) ) {

            if ( $real_name[0] == "." AND in_array($this->skip_mode, array("HIDDEN", "ALL")) ) return;

            if ( $real_name[0] == "." AND @$real_name[1] == "_" AND in_array($this->skip_mode, array("COMODOJO", "ALL")) ) return;

        }

        if ( is_dir($real_file) ) {

            if ( !$flatroot ) {

                $folder_target = is_null($base) ? $real_name : $base.$real_name;

                $new_folder = $this->zip_archive->addEmptyDir($folder_target);

                if ( $new_folder === false ) throw new ZipException(self::getStatus($this->zip_archive->status));

            } else {

                $folder_target = null;

            }

            foreach ( new \DirectoryIterator($real_file) as $path ) {

                if ( $path->isDot() ) continue;

                $file_real = $path->getPathname();

                $base = is_null($folder_target) ? null : ($folder_target."/");

                try {

                    $this->addItem($file_real, false, $base);

                } catch (ZipException $ze) {

                    throw $ze;

                }

            }

        }
        else if ( is_file($real_file) ) {

            $file_target = is_null($base) ? $real_name : $base.$real_name;

            $add_file = $this->zip_archive->addFile($real_file, $file_target);

            if ( $add_file === false ) throw new ZipException(self::getStatus($this->zip_archive->status));

        } else return;

    }

    /**
     * Delete item from zip archive
     *
     * @param   string $file   File to delete (zippath)
     *
     * @throws  \Comodojo\Exception\ZipException
     */
    private function deleteItem($file) {

        $deleted = $this->zip_archive->deleteName($file);

        if ( $deleted === false ) throw new ZipException(self::getStatus($this->zip_archive->status));

    }

    /**
     * Open a zip file
     *
     * @param   string $zip_file   ZIP status code
     * @param   int    $flags      ZIP status code
     *
     * @return  \ZipArchive
     * @throws  \Comodojo\Exception\ZipException
     */
    private static function openZipFile($zip_file, $flags = null) {

        $zip = new ZipArchive();

        $open = $zip->open($zip_file, $flags);

        if ( $open !== true ) throw new ZipException(self::getStatus($open));

        return $zip;

    }

    /**
     * Get status from zip status code
     *
     * @param   int $code   ZIP status code
     *
     * @return  string
     */
    private static function getStatus($code) {

        if ( array_key_exists($code, self::$zip_status_codes) ) return self::$zip_status_codes[$code];

        else return sprintf('Unknown status %s', $code);

    }

}
