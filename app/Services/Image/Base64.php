<?php declare(strict_types=1);

namespace App\Services\Image;

use App\Exceptions\UnexpectedValueException;
use App\Services\Image\Manager\Manager;

class Base64
{
    /**
     * @var string
     */
    protected string $absolute;

    /**
     * @var array
     */
    protected array $info;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param string $image
     * @param string $file
     * @param string $path
     *
     * @return self
     */
    public function __construct(protected string $image, protected string $file, protected string $path)
    {
        $this->absolute();
    }

    /**
     * @return void
     */
    protected function absolute(): void
    {
        $this->absolute = $this->path.'/'.ltrim($this->file, '/');
    }

    /**
     * @return string
     */
    public function handle(): string
    {
        $this->checkBase64();
        $this->parse();
        $this->info();
        $this->checkImage();
        $this->save();
        $this->transform();

        return $this->file;
    }

    /**
     * @return void
     */
    protected function checkBase64(): void
    {
        if (preg_match('#^data:image/([a-z]+);base64,([\w=+/]+)$#', $this->image) === 0) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function parse(): void
    {
        $image = base64_decode((explode(',', $this->image, 2) + ['', ''])[1], true);

        if (empty($image)) {
            $this->fail();
        }

        $this->image = $image;
    }

    /**
     * @return void
     */
    protected function info(): void
    {
        try {
            $this->info = getimagesizefromstring($this->image);
        } catch (Throwable $e) {
            $this->fail();
        }

        if (empty($this->info[0]) || empty($this->info[1]) || empty($this->info[2])) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function checkImage(): void
    {
        $this->checkImageExtension();
        $this->checkImageContents();
    }

    /**
     * @return void
     */
    protected function checkImageExtension(): void
    {
        $extension = str_replace(['image/', 'jpeg'], ['', 'jpg'], $this->info['mime']);

        if ($extension !== 'jpg') {
            $this->fail(__('image-base64.validate.only-jpg'));
        }
    }

    /**
     * @return void
     */
    protected function checkImageContents(): void
    {
        ob_start();

        imagejpeg(imagecreatefromstring($this->image));

        $length = ob_get_length();

        ob_end_clean();

        if ($length === 0) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        helper()->mkdir($this->absolute, true);

        file_put_contents($this->absolute, $this->image, LOCK_EX);

        unset($this->image);
    }

    /**
     * @return void
     */
    protected function transform(): void
    {
        Manager::file($this->absolute)
            ->square()
            ->resize(1000, 1000)
            ->save($this->absolute);
    }

    /**
     * @param string $message = ''
     *
     * @return void
     */
    protected function fail(string $message = ''): void
    {
        throw new UnexpectedValueException($message ?: __('image.base64.validate.invalid'));
    }
}
