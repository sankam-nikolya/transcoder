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
namespace Arhitector\Transcoder\Service;

use Arhitector\Transcoder\Format\FormatInterface;
use Arhitector\Transcoder\TranscodeInterface;
use SimpleQueue\Job;
use SimpleQueue\QueueAdapterInterface as QueueInterface;

/**
 * Class EncoderQueue.
 *
 * @package Arhitector\Jumper\Service
 */
class EncoderQueue extends Encoder
{
	
	/**
	 * @var QueueInterface
	 */
	protected $queue;
	
	/**
	 * Encoder constructor.
	 *
	 * @param QueueInterface $queue
	 * @param array          $options
	 *
	 * @throws \Arhitector\Transcoder\Exception\ExecutableNotFoundException
	 */
	public function __construct(QueueInterface $queue, array $options = [])
	{
		parent::__construct($options);
		
		$this->queue = $queue;
	}
	
	/**
	 * Constructs and returns the iterator with instances of 'Process'.
	 *
	 * @param TranscodeInterface $media  it may be a stream or media wrapper.
	 * @param FormatInterface    $format new format.
	 * @param array              $options
	 *
	 * @return \Iterator|\Symfony\Component\Process\Process[] returns the instances of 'Process'.
	 * @throws \RuntimeException
	 */
	public function transcoding(TranscodeInterface $media, FormatInterface $format, array $options = [])
	{
		foreach (parent::transcoding($media, $format, $options) as $process)
		{
			$this->queue->push(new Job(['transcoding', 'command_line' => $process->getCommandLine()], 'transcoding'));
			
			// fix: because Symfony uses private properties without setters methods :-(((
			$property = new \ReflectionProperty($process, 'status');
			$property->setAccessible(true);
			$property->setValue($process, $process::STATUS_TERMINATED);
			
			yield $process;
		}
	}
	
}
