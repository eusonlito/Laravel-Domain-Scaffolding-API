<?php declare(strict_types=1);

namespace App\Services\Jwt;

use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;

class Token
{
    /**
     * @param string $url
     * @param string|int $id
     * @param string $expires
     *
     * @return string
     */
    public static function get(string $url, string|int $id, string $expires): string
    {
        return static::builder()
            ->issuedBy($url)
            ->permittedFor($url)
            ->identifiedBy(static::identifiedBy($id))
            ->issuedAt(static::issuedAt())
            ->expiresAt(static::expiresAt($expires))
            ->getToken(...static::getToken())
            ->toString();
    }

    /**
     * @return \Lcobucci\JWT\Token\Builder
     */
    protected static function builder(): Builder
    {
        return new Builder(new JoseEncoder(), ChainedFormatter::default());
    }

    /**
     * @param string|int $id
     *
     * @return string
     */
    protected static function identifiedBy(string|int $id): string
    {
        return base64_encode(strval($id));
    }

    /**
     * @return \DateTimeImmutable
     */
    protected static function issuedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    /**
     * @param string $expires
     *
     * @return \DateTimeImmutable
     */
    protected static function expiresAt(string $expires): DateTimeImmutable
    {
        return new DateTimeImmutable($expires);
    }

    /**
     * @return array
     */
    protected static function getToken(): array
    {
        return [static::algorithm(), static::key()];
    }

    /**
     * @return \Lcobucci\JWT\Signer\Hmac\Sha256
     */
    protected static function algorithm(): Sha256
    {
        return new Sha256();
    }

    /**
     * @return \Lcobucci\JWT\Signer\Key\InMemory
     */
    protected static function key(): InMemory
    {
        return InMemory::plainText(random_bytes(32));
    }
}
