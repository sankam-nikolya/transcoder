## Tools to transcoding/encoding audio or video, inspect and convert media formats.

## Установка

```bash
$ composer require arhitector/transcoder dev-master
```

## 1. Быстрый старт

Необходимо определить, с каким типом файлов предстоит работать.

`Arhitector\Transcoder\Audio` используется для работы с аудио-файлами.

`Arhitector\Transcoder\Video` используется для работы с видео-файлами.

`Arhitector\Transcoder\Frame` используется для работы с изображениями.

`Arhitector\Transcoder\Subtitle` используется для работы с субтитрами.

Конструктор в общем виде выглядит так

```php
public <...>::__construct(string $filePath, ServiceFactoryInterface $service = null)
```

`$filePath` - определяет путь до исходного файла. Вы не можете использовать удаленный источник или символические ссылки.

`$service` - не обязательный параметр, экземпляр сервиса. Если не передан, то будет использоваться `ServiceFactory`.

### 1.1. Примеры

Простые примеры

```php
use Arhitector\Transcoder\Audio;
use Arhitector\Transcoder\Video;
use Arhitector\Transcoder\Frame;
use Arhitector\Transcoder\Subtitle;

// аудио
$audio = new Audio('sample.mp3');

// видео
$video = new Video('sample.avi');

// изображения
$frame = new Frame('sample.jpg');

// субтитры
$subtitle = new Subtitle('sample.srt');
```

Вы можете использовать свою сервис-фабрику или изменить некоторые опции.

```php
$service = new \Arhitector\Transcoder\Service\ServiceFactory([
	'ffprobe.path'   => 'E:\devtools\bin\ffprobe.exe',
	'ffmpeg.path'    => 'E:\devtools\bin\ffmpeg.exe'
]);

// используем это
$video = new Video('sample.avi', $service);
```

## 1.2. Что можно настроить?

`ServiceFactory` поддерживает следующие опции:

- `ffmpeg.path` - путь до исполняемого файла `ffmpeg`

- `ffmpeg.threads` - FFMpeg-опция `threads`. По умолчанию `0`.

- `ffprobe.path` - путь до исполняемого файла `ffprobe`

- `timeout` - задаёт таймаут выполнения команды кодирования.

- `use_queue` - задача кодирования будет отправляться в очередь. Значение должно быть объектом,
 реализующим `SimpleQueue\QueueAdapterInterface`.

Вы можете использовать свою реализацию сервис-фабрики. Для этого необходимо реализовать в вашем объекте
 интерфейс `Arhitector\Transcoder\Service\ServiceFactoryInterface`.

## 2. Поддержка очередей

Вместо прямого транскодирования вы можете отправлять задачи в очередь, например, на сервер очередей. Такой функционал
 доступен прямо из коробки. Вы можете использовать опцию `ServiceFactoryInterface::OPTION_USE_QUEUE` при создании сервис-фабрики.

**Пример**

```php
$adapter = new SimpleQueue\Adapter\MemoryQueueAdapter();
$queue = new SimpleQueue\Queue($queue);

$service = new Arhitector\Transcoder\Service\ServiceFactory([
    Arhitector\Transcoder\Service\ServiceFactory::OPTION_USE_QUEUE => $queue
]);

$audio = new Arhitector\Transcoder\Audio('sample.mp3', $service);

// задача будет отправлена в очерель `$queue`
$audio->save($audio->getFormat(), 'new-sample.mp3');

var_dump($queue->pull()); // запросить задачу из очереди
```

### Извлечение информации из видео файла, аудио файла и т.д.

```php
use Arhitector\Transcoder\Video;
use Arhitector\Transcoder\Audio;

$video = new Video('sample.avi');

var_dump($video->getWidth(), $video->getHeight());

$audio = new Audio(__DIR__.'/audio.mp3', $factory);

var_dump($audio->getAudioChannels());
var_dump($audio->getFormat()->getTags());
```

### Извлечение звука из видео файла с последующим сохранением в формате MP3

Этот простой пример показывает лишь принцип, таким же способом можно сохранить субтитры или обложку из Mp3-файла и т.д.

```php
use Arhitector\Transcoder\Video;
use Arhitector\Transcoder\Stream\AudioStreamInterface;
use Arhitector\Transcoder\Format\Mp3;

$video = new Video('sample.mp4');

foreach ($video->getStreams() as $stream)
{
	// тут выбираем только аудио канал
	if ($stream instanceof AudioStreamInterface)
	{
		$stream->save(new Mp3(), __DIR__.'/only-audio.mp3');
		
		break; // видео может иметь несколько аудио потоков
	}
}
```

### Преобразование из одного формата в любой другой

```php
use Arhitector\Transcoder\Audio;
use Arhitector\Transcoder\Format\Mp3;

$audio = new Audio('audio-file.wav');
$audio->save(new Mp3(), 'audio-file.mp3');

use Arhitector\Transcoder\Video;
use Arhitector\Transcoder\Format\VideoFormat;

$video = new Video('video-file.avi');
$video->save(new VideoFormat('aac', 'h264'), 'video-file.mp4');
```

### Добавление/Изменение мета-информации

```php
use Arhitector\Transcoder\Audio;

$audio = new Audio('file.mp3');

$format = $audio->getFormat();
$format['artist'] = 'Новый артист';

$auiod->save($format, 'new-file.mp3');
```

### Как добавить/изменить обложку MP3-файла?

```php
use Arhitector\Transcoder\Audio;
use Arhitector\Transcoder\Frame;

$audio = new Audio(__DIR__.'/sample.mp3');
$streams = $audio->getStreams();

$new_cover = (new Frame(__DIR__.'/sample.jpg'))
    ->getStreams()
    ->getFirst();

// индекс `0` - аудио-дорожка, `1` - обложка.
$streams[1] = $new_cover;

$audio->save($audio->getFormat(), 'sample-with-new-cover.mp3');
```

## ООП-обёртки над форматами

Такие обёртки (например, Mp3 или Jpeg и т.д.) созданы для удобства. 

### Изображения

- Png, Jpeg, Ppm, Bmp, Gif

## Фильтры

### Аудио фильтры

- Фильтр **Volume**

Фильтр изменяет громкость аудио потока.

```php
use \Arhitector\Jumper\Filter\Volume;
```

Пример показывает как уменьшить громкость аудио.

```php
$filter = new Volume(0.5);
$filter = new Volume(1/2);
$filter = new Volume('6.0206dB');
```

Increase input audio power by 6 decibels using fixed-point precision.

```php
$filter = new Volume('6dB', Volume::PRECISION_FIXED);
```

- Фильтр **Fade**

Фильтр накладывает эффект затухания звука.

```php
use \Arhitector\Jumper\Filter\Fade;
```

## Опции форматов

*FormatInterface* определяет 

`duration`

`extensions`

`metadata`

*FrameFormatInterface* дополняет список *FormatInterface*

`video_codec`

`width`

`height`

`available_video_codecs`

*AudioFormatInterface* дополняет список *FrameFormatInterface*

`channels`

`audio_codec`

`audio_bitrate`

`frequency`

`available_audio_codecs`

*VideoFormatInterface* дополняет список *AudioFormatInterface*

`video_bitrate`

`passes`

`frame_rate`

