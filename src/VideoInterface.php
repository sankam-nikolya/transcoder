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
namespace Arhitector\Transcoder;

/**
 * Interface VideoInterface.
 *
 * @package Arhitector\Transcoder
 */
interface VideoInterface extends FrameInterface, AudioInterface
{
	
	/**
	 * Gets the bitrate value.
	 *
	 * @return int
	 */
	public function getKiloBitrate();
	
	/**
	 * Get frame rate value.
	 *
	 * @return float
	 */
	public function getFrameRate();
	
}
