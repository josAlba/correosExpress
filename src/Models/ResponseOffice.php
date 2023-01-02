<?php

namespace Jos\CorreosExpres\Models;

use JMS\Serializer\Annotation;

final class ResponseOffice implements ResponseInterface
{
    /**
     * @var string
     * @Annotation\Type("string")
     * @Annotation\SerializedName("uuid")
     */
    private string $uuid;
}