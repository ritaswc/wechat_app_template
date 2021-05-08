<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
class UploadedFile extends SplFileInfo {
	
	private static $errors = array(
		UPLOAD_ERR_OK,
		UPLOAD_ERR_INI_SIZE,
		UPLOAD_ERR_FORM_SIZE,
		UPLOAD_ERR_PARTIAL,
		UPLOAD_ERR_NO_FILE,
		UPLOAD_ERR_NO_TMP_DIR,
		UPLOAD_ERR_CANT_WRITE,
		UPLOAD_ERR_EXTENSION,
	);

	
	private $clientFilename;

	
	private $clientMediaType;

	
	private $error;

	
	private $file;

	
	private $moved = false;

	
	private $size;

	public function __construct(
		$streamOrFile,
		$size,
		$errorStatus,
		$clientFilename = null,
		$clientMediaType = null
	) {
		$this->setError($errorStatus);
		$this->setSize($size);
		$this->setClientFilename($clientFilename);
		$this->setClientMediaType($clientMediaType);
		parent::__construct($streamOrFile);
		if ($this->isOk()) {
			$this->setStreamOrFile($streamOrFile);
		}
	}

	
	private function setStreamOrFile($streamOrFile) {
		if (is_string($streamOrFile)) {
			$this->file = $streamOrFile;
		} else {
			throw new InvalidArgumentException(
				'Invalid stream or file provided for UploadedFile'
			);
		}
	}

	
	private function setError($error) {
		if (false === is_int($error)) {
			throw new InvalidArgumentException(
				'Upload file error status must be an integer'
			);
		}

		if (false === in_array($error, self::$errors)) {
			throw new InvalidArgumentException(
				'Invalid error status for UploadedFile'
			);
		}

		$this->error = $error;
	}

	
	private function setSize($size) {
		if (false === is_int($size)) {
			throw new InvalidArgumentException(
				'Upload file size must be an integer'
			);
		}

		$this->size = $size;
	}

	
	private function isStringOrNull($param) {
		return in_array(gettype($param), array('string', 'NULL'));
	}

	
	private function isStringNotEmpty($param) {
		return is_string($param) && false === empty($param);
	}

	
	private function setClientFilename($clientFilename) {
		if (false === $this->isStringOrNull($clientFilename)) {
			throw new InvalidArgumentException(
				'Upload file client filename must be a string or null'
			);
		}

		$this->clientFilename = $clientFilename;
	}

	
	private function setClientMediaType($clientMediaType) {
		if (false === $this->isStringOrNull($clientMediaType)) {
			throw new InvalidArgumentException(
				'Upload file client media type must be a string or null'
			);
		}

		$this->clientMediaType = $clientMediaType;
	}

	
	public function isOk() {
		return UPLOAD_ERR_OK === $this->error;
	}

	
	public function isMoved() {
		return $this->moved;
	}

	
	private function validateActive() {
		if (false === $this->isOk()) {
			throw new RuntimeException('Cannot retrieve stream due to upload error');
		}

		if ($this->isMoved()) {
			throw new RuntimeException('Cannot retrieve stream after it has already been moved');
		}
	}

	public function moveTo($targetPath) {
		$this->validateActive();
		if (false === $this->isStringNotEmpty($targetPath)) {
			throw new InvalidArgumentException(
				'Invalid path provided for move operation; must be a non-empty string'
			);
		}

		if ($this->file) {
			$this->moved = 'cli' == php_sapi_name()
				? rename($this->file, $targetPath)
				: move_uploaded_file($this->file, $targetPath);
		}

		if (false === $this->moved) {
			throw new RuntimeException(
				sprintf('Uploaded file could not be moved to %s', $targetPath)
			);
		}
	}

	
	public function getSize() {
		return $this->size;
	}

	
	public function getError() {
		return $this->error;
	}

	
	public function getClientFilename() {
		return $this->clientFilename;
	}

	
	public function getClientMediaType() {
		return $this->clientMediaType;
	}

	
	public function isImage() {
		return $this->isOk() && in_array($this->clientMediaType, array());
	}

	
	public function clientExtension() {
		return pathinfo($this->getClientFilename(), PATHINFO_EXTENSION);
	}

	
	public function allowExt($ext) {
		return $this->clientExtension() === $ext;
	}

	
	public function getContent() {
		return file_get_contents($this->file);
	}

	public static function createFromGlobal() {
		$files = array();
		foreach ($_FILES as $key => $file) {
			$createFiles = static::create($file);
			$files[$key] = $createFiles;
		}

		return $files;
	}

	
	private static function create($file) {
		if (is_array($file['tmp_name'])) {
			return static::createArrayFile($file);
		}

		return static::createUploadedFile($file);
	}

	
	public static function createArrayFile($files) {
		$data = array();
		foreach (array_keys($files['tmp_name']) as $key) {
			$file = array(
				'tmp_name' => $files['tmp_name'][$key],
				'size' => $files['size'][$key],
				'error' => $files['error'][$key],
				'name' => $files['name'][$key],
				'type' => $files['type'][$key],
			);
			$data[$key] = self::createUploadedFile($file);
		}

		return $data;
	}

	private static function createUploadedFile($value) {
		$upfile = new static(
			$value['tmp_name'],
			$value['size'],
			$value['error'],
			$value['name'],
			$value['type']
		);

		return $upfile;
	}
}
