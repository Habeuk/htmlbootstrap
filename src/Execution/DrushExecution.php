<?php
namespace Stephane888\HtmlBootstrap\Execution;

use Stephane888\HtmlBootstrap\Traits\Portions;

class DrushExecution {
	use Portions;

	static function generateimage(int $fid, string $styleImage = 'thumbnail') {
		$img = self::getImageUrlByFid($fid, $styleImage);
		if (isset($img['img_url'])) {
			return $img['img_url'];
		}
		else {
			return 'error de generation :' . json_encode($img);
		}
	}
}