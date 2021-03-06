<?php
/**
 * This file is part of the arhitector/transcoder library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Dmitry Arhitector <dmitry.arhitector@yandex.ru>
 *
 * @license   http://opensource.org/licenses/MIT MIT
 * @copyright Copyright (c) 2017 Dmitry Arhitector <dmitry.arhitector@yandex.ru>
 */
namespace Arhitector\Transcoder\Format;

use Arhitector\Transcoder\Codec;

/**
 * The Flac audio format.
 *
 * @package Arhitector\Transcoder\Format
 */
class Flac extends AudioFormat
{
	
	/**
	 * Format constructor.
	 *
	 * @param Codec|string $codec
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($codec = 'flac')
	{
		parent::__construct($codec, null);
		
		$this->setExtensions(['flac']);
		$this->setAvailableVideoCodecs(['flac', 'flaccl']);
	}
	
}
