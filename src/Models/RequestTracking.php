<?php

namespace Jos\CorreosExpres\Models;

use Exception;

final class RequestTracking implements RequestInterface
{
    public function __construct(private readonly string $referenceShipping)
    {
    }

    public function getXml(string $applicantCode): string
    {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mes="messages.seguimientoEnvio.ws.chx.es">
           <soapenv:Header/>
           <soapenv:Body>
             <mes:seguimientoEnvio>
             <mes:solicitante>'.$applicantCode.'</mes:solicitante>
             <mes:dato>'.$this->referenceShipping.'</mes:dato>
             <!--Optional:-->
             <mes:password></mes:password>
              </mes:seguimientoEnvio>
           </soapenv:Body>
        </soapenv:Envelope>';
    }

    public function getContentLength(string $applicantCode): int
    {
        return strlen($this->getXml($applicantCode));
    }
}