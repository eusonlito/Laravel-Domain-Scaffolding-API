<?php declare(strict_types=1);

namespace App\Services\Image;

class AvatarRandom
{
    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param string $gender
     * @param string $file
     * @param string $path
     *
     * @return self
     */
    public function __construct(protected string $gender, protected string $file, protected string $path)
    {
    }

    /**
     * @return string
     */
    public function handle(): string
    {
        $this->save();

        return $this->file;
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        Base64::new($this->base64(), $this->file, $this->path)->handle();
    }

    /**
     * @return string
     */
    protected function base64(): string
    {
        return 'data:image/jpeg;base64,'.$this->base64Contents();
    }

    /**
     * @return string
     */
    protected function base64Contents(): string
    {
        return base64_encode(file_get_contents('https://i.pravatar.cc/1000'));
    }
}
