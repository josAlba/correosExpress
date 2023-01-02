<?php

namespace Jos\CorreosExpres\Models;

use DateTime;
use RuntimeException;

final class RequestPickup implements RequestInterface
{
    public const ISO_CODE_PT = 'PT';
    public const ISO_CODE_EMPTY = '';
    public const ISO_CODES = [
        self::ISO_CODE_PT,
        self::ISO_CODE_EMPTY,
    ];

    public function __construct(
        private readonly string $referenceShipping,
        private readonly string $fromAddress,
        private readonly string $fromCity,
        private readonly string $fromPostalCode,
        private readonly string $formName,
        private readonly string $fromPhone,
        private readonly string $fromMail,
        private readonly string $observations,
        private readonly string $toName,
        private readonly string $toAddress,
        private readonly string $toCity,
        private readonly string $toPostalCode,
        private readonly string $toPhone,
        private readonly int $weight,
        private readonly int $lumps,
        private readonly DateTime $date,
        private readonly DateTime $beforeHour,
        private readonly DateTime $afterHour,
        private readonly string $toIsoCode = ''
    ) {
        if (!in_array($this->toIsoCode, self::ISO_CODES)) {
            throw new RuntimeException('Iso code is not valid.');
        }
    }

    public function getXml(string $applicantCode, string $clientCode): string
    {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:es="es.chx.ws.recogidas">
				<soapenv:Header/>
				<soapenv:Body>
					<es:grabarRecogida>
						<es:solicitante>'.$applicantCode.'</es:solicitante>
						<es:password></es:password>
						<es:canalEntrada></es:canalEntrada>
						<es:refRecogida>'.$this->referenceShipping.'</es:refRecogida>
						<es:fechaRecogida>'.$this->date->format('d-m-Y').'</es:fechaRecogida>
						<es:horaDesde1>'.$this->beforeHour->format('H:i').'</es:horaDesde1>
						<es:horaHasta1>'.$this->afterHour->format('H:i').'</es:horaHasta1>
						<es:horaDesde2></es:horaDesde2>
						<es:horaHasta2></es:horaHasta2>
						<es:clienteRecogida>'.$clientCode.'</es:clienteRecogida>
						<es:codRemit></es:codRemit>
						<es:NomRemit>'.$this->formName.'</es:NomRemit>
						<es:nifRemit></es:nifRemit>
						<es:dirRecog>'.$this->fromAddress.'</es:dirRecog>
						<es:poblRecog>'.$this->fromCity.'</es:poblRecog>
						<es:cpRecog>'.$this->fromPostalCode.'</es:cpRecog>
						<es:contRecog>'.$this->formName.'</es:contRecog>
						<es:tlfnoRecog>'.$this->fromPhone.'</es:tlfnoRecog>
						<es:emailRecog>'.$this->fromMail.'</es:emailRecog>
						<es:observ>'.$this->observations.'</es:observ>
						<es:tipoServ></es:tipoServ>
						<es:codDest></es:codDest>
						<es:nomDest>'.$this->toName.'</es:nomDest>
						<es:nifDest></es:nifDest>
						<es:dirDest>'.$this->toAddress.'</es:dirDest>
						<es:pobDest>'.$this->toCity.'</es:pobDest>
						<es:cpiDest></es:cpiDest>
						<es:paisDest>'.$this->toIsoCode.'</es:paisDest>
						<es:cpDest>'.$this->toPostalCode.'</es:cpDest>
						<es:contactoDest>'.$this->toName.'</es:contactoDest>
						<es:tlfnoDest>'.$this->toPhone.'</es:tlfnoDest>
						<es:emailDest></es:emailDest>
						<es:nEnvio></es:nEnvio>
						<es:refEnvio></es:refEnvio>
						<es:producto></es:producto>
						<es:kilos>'.$this->weight.'</es:kilos>
						<es:bultos>'.$this->lumps.'</es:bultos>
						<es:volumen></es:volumen>
						<es:tipoPortes>P</es:tipoPortes>
						<es:valDeclMerc></es:valDeclMerc>
						<es:infTec></es:infTec>
						<es:nSerie></es:nSerie>
						<es:modelo></es:modelo>
					    </es:grabarRecogida>
                    </soapenv:Body>
                </soapenv:Envelope>';
    }

    public function getContentLength(string $applicantCode,string $clientCode): int
    {
        return strlen($this->getXml($applicantCode,$clientCode));
    }
}