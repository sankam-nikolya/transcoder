<?php
/**
 * This file is part of the arhitector/jumper library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Dmitry Arhitector <dmitry.arhitector@yandex.ru>
 *
 * @license   http://opensource.org/licenses/MIT MIT
 * @copyright Copyright (c) 2017 Dmitry Arhitector <dmitry.arhitector@yandex.ru>
 */
namespace Arhitector\Jumper\Format;

use Arhitector\Jumper\Codec;

/**
 * Class Png.
 *
 * @package Arhitector\Jumper\Format
 */
class Png extends FrameFormat
{
	
	/**
	 * Png constructor.
	 *
	 * @param Codec|string $codec
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($codec = 'png')
	{
		$this->setExtensions(['png']);
		$this->setAvailableFrameCodecs(['apng', 'png']);
		
		parent::__construct($codec);
	}
	
}