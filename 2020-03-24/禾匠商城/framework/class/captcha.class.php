<?php

defined('IN_IA') or exit('Access Denied');

class Captcha {
	public $maxAngle = 15;
	public $maxOffset = 5;
	public $phrase = '';

	public function build($width, $height) {
		$image = @imagecreatetruecolor($width, $height);
		if (empty($image)) {
			return error('1', 'Not supplied GD');
		}
		$bg = imagecolorallocate($image, $this->rand(200, 255), $this->rand(200, 255), $this->rand(200, 255));
		imagefill($image, 0, 0, $bg);

		$square = $width * $height * 3;
		$effects = $this->rand($square / 2000, $square / 1000);
		for ($e = 0; $e < $effects; ++$e) {
			$this->drawLine($image, $width, $height);
		}
		$this->phrase = $this->phrase();
		$color = $this->writePhrase($image, $this->phrase, $this->font(), $width, $height);

		$square = $width * $height;
		$effects = $this->rand($square / 3000, $square / 2000);
		if (0 !== $this->maxFrontLines) {
			for ($e = 0; $e < $effects; ++$e) {
				$this->drawLine($image, $width, $height, $color);
			}
		}

		$image = $this->distort($image, $width, $height, $bg);
		$this->image = $image;

		return $this;
	}

	public function output($quality = 90) {
		header('content-type: image/png');
		imagepng($this->image);
		imagedestroy($this->image);
	}

	protected function phrase() {
		return random(4, true);
	}

	protected function rand($min, $max) {
		mt_srand((float) microtime() * 1000000);

		return mt_rand($min, $max);
	}

	protected function drawLine($image, $width, $height, $tcol = null) {
		if (null === $tcol) {
			$tcol = imagecolorallocate($image, $this->rand(100, 255), $this->rand(100, 255), $this->rand(100, 255));
		}

		if ($this->rand(0, 1)) {
			$Xa = $this->rand(0, $width / 2);
			$Ya = $this->rand(0, $height);
			$Xb = $this->rand($width / 2, $width);
			$Yb = $this->rand(0, $height);
		} else {
			$Xa = $this->rand(0, $width);
			$Ya = $this->rand(0, $height / 2);
			$Xb = $this->rand(0, $width);
			$Yb = $this->rand($height / 2, $height);
		}
		imagesetthickness($image, $this->rand(1, 3));
		imageline($image, $Xa, $Ya, $Xb, $Yb, $tcol);
	}

	protected function writePhrase($image, $phrase, $font, $width, $height) {
		$size = $width / strlen($phrase) - $this->rand(0, 3) - 1;
		$box = imagettfbbox($size, 0, $font, $phrase);
		$textWidth = $box[2] - $box[0];
		$textHeight = $box[1] - $box[7];
		$x = ($width - $textWidth) / 2;
		$y = ($height - $textHeight) / 2 + $size;

		$textColor = array($this->rand(0, 150), $this->rand(0, 150), $this->rand(0, 150));
		$col = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);

		$length = strlen($phrase);
		for ($i = 0; $i < $length; ++$i) {
			$box = imagettfbbox($size, 0, $font, $phrase[$i]);
			$w = $box[2] - $box[0];
			$angle = $this->rand(-$this->maxAngle, $this->maxAngle);
			$offset = $this->rand(-$this->maxOffset, $this->maxOffset);
			imagettftext($image, $size, $angle, $x, $y + $offset, $col, $font, $phrase[$i]);
			$x += $w;
		}

		return $col;
	}

	public function distort($image, $width, $height, $bg) {
		$contents = imagecreatetruecolor($width, $height);
		$X = $this->rand(0, $width);
		$Y = $this->rand(0, $height);
		$phase = $this->rand(0, 10);
		$scale = 1.1 + $this->rand(0, 10000) / 30000;
		for ($x = 0; $x < $width; ++$x) {
			for ($y = 0; $y < $height; ++$y) {
				$Vx = $x - $X;
				$Vy = $y - $Y;
				$Vn = sqrt($Vx * $Vx + $Vy * $Vy);

				if (0 != $Vn) {
					$Vn2 = $Vn + 4 * sin($Vn / 30);
					$nX = $X + ($Vx * $Vn2 / $Vn);
					$nY = $Y + ($Vy * $Vn2 / $Vn);
				} else {
					$nX = $X;
					$nY = $Y;
				}
				$nY = $nY + $scale * sin($phase + $nX * 0.2);

				$p = $this->interpolate(
					$nX - floor($nX),
					$nY - floor($nY),
					$this->getCol($image, floor($nX), floor($nY), $bg),
					$this->getCol($image, ceil($nX), floor($nY), $bg),
					$this->getCol($image, floor($nX), ceil($nY), $bg),
					$this->getCol($image, ceil($nX), ceil($nY), $bg)
				);

				if (0 == $p) {
					$p = $bg;
				}

				imagesetpixel($contents, $x, $y, $p);
			}
		}

		return $contents;
	}

	protected function interpolate($x, $y, $nw, $ne, $sw, $se) {
		list($r0, $g0, $b0) = $this->getRGB($nw);
		list($r1, $g1, $b1) = $this->getRGB($ne);
		list($r2, $g2, $b2) = $this->getRGB($sw);
		list($r3, $g3, $b3) = $this->getRGB($se);

		$cx = 1.0 - $x;
		$cy = 1.0 - $y;

		$m0 = $cx * $r0 + $x * $r1;
		$m1 = $cx * $r2 + $x * $r3;
		$r = (int) ($cy * $m0 + $y * $m1);

		$m0 = $cx * $g0 + $x * $g1;
		$m1 = $cx * $g2 + $x * $g3;
		$g = (int) ($cy * $m0 + $y * $m1);

		$m0 = $cx * $b0 + $x * $b1;
		$m1 = $cx * $b2 + $x * $b3;
		$b = (int) ($cy * $m0 + $y * $m1);

		return ($r << 16) | ($g << 8) | $b;
	}

	protected function getRGB($col) {
		return array(
				(int) ($col >> 16) & 0xff,
				(int) ($col >> 8) & 0xff,
				(int) ($col) & 0xff,
		);
	}

	protected function getCol($image, $x, $y, $background) {
		$L = imagesx($image);
		$H = imagesy($image);
		if ($x < 0 || $x >= $L || $y < 0 || $y >= $H) {
			return $background;
		}

		return imagecolorat($image, $x, $y);
	}

	protected function font() {
		return IA_ROOT . '/web/resource/fonts/captcha.ttf';
	}
}
