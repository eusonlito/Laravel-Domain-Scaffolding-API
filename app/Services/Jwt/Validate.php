<?php declare(strict_types=1);

namespace App\Services\Jwt;

use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;

class Validate
{
    /**
     * @param ?string $token
     *
     * @return ?string
     */
    public static function get(?string $token): ?string
    {
        if (empty($token) || empty($jti = static::jti($token))) {
            return null;
        }

        return base64_decode($jti);
    }

    /**
     * @param string $token
     *
     * @return ?string
     */
    protected static function jti(string $token): ?string
    {
        try {
            return static::parser()->parse($token)->claims()->get('jti');
        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $e) {
            return null;
        }
    }

    /**
     * @return \Lcobucci\JWT\Token\Parser
     */
    protected static function parser(): Parser
    {
        return new Parser(new JoseEncoder());
    }
}
