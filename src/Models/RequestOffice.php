<?php

namespace Jos\CorreosExpres\Models;

use Exception;

final class RequestOffice implements RequestInterface
{
    private const POST_FIELD_POSTAL_CODE = "cod_postal";
    private const POST_FIELD_CITY = "poblacion";

    public function __construct(private readonly string $postalCode, private readonly string $city)
    {
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getPostFields(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getContentLength(): int
    {
        return strlen($this->getPostFields());
    }

    public function toArray(): array
    {
        return [
            self::POST_FIELD_POSTAL_CODE => trim($this->postalCode),
            self::POST_FIELD_CITY => trim($this->city),
        ];
    }
}