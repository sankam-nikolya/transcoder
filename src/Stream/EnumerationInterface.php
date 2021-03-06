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
namespace Arhitector\Transcoder\Stream;

/**
 * Interface EnumerationInterface.
 *
 * @package Arhitector\Transcoder\Stream
 */
interface EnumerationInterface
{
	
	/**
	 * @var int Type of audio stream.
	 */
	const T_AUDIO = 1;
	
	/**
	 * @var int Type of frame stream.
	 */
	const T_FRAME = 2;
	
	/**
	 * @var int Type of video stream.
	 */
	const T_VIDEO = 4;
	
	/**
	 * @var int Type of subtitle stream.
	 */
	const T_SUBTITLE = 8;
	
}
