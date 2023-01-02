<?php

namespace Jos\CorreosExpres;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Jos\CorreosExpres\Models\RequestOffice;
use Jos\CorreosExpres\Models\RequestTracking;
use Jos\CorreosExpres\Models\ResponseOffice;
use Jos\CorreosExpres\Models\ResponseTracking;

final class CorreosExpres
{
    private const TYPE_JSON = 'json';
    private const TYPE_XML = 'xml';
    private Client $client;
    private Serializer $serializer;

    public function __construct(
        private readonly string $clientCode,
        private readonly string $applicantCode,
        private readonly string $user,
        private readonly string $password
    ) {
        $this->serializer = SerializerBuilder::create()->build();
    }

    private function newConnection(): void
    {
        $this->client = new Client();
    }

    /**
     * @param RequestOffice $requestOffice
     *
     * @return array<ResponseOffice>
     * @throws GuzzleException
     * @throws Exception
     */
    private function getOffices(RequestOffice $requestOffice): array
    {
        $response = $this->client->post(
            Endpoints::URI_OFFICES,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Content-Length' => $requestOffice->getContentLength(),
                    'Authorization' => 'Basic '.$this->encodedAuth(),
                ],
                'form_params' => $requestOffice->toArray(),
            ]
        );

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            'array<'.ResponseOffice::class.'>',
            self::TYPE_JSON
        );
    }

    /**
     * @param RequestTracking $requestTracking
     *
     * @return ResponseTracking
     * @throws GuzzleException
     */
    private function getTracking(RequestTracking $requestTracking): ResponseTracking
    {
        $response = $this->client->post(
            Endpoints::URI_OFFICES,
            [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'Accept' => 'text/xml',
                    'Cache-Control' => 'no-cache',
                    'Pragma' => 'no-cache',
                    'SOAPAction' => Endpoints::URI_TRACKING,
                    'Content-length' => $requestTracking->getContentLength($this->applicantCode),
                    'Authorization' => 'Basic '.$this->encodedAuth(),
                ],
                'body' => $requestTracking->getXml($this->applicantCode),
            ]
        );

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            ResponseTracking::class,
            self::TYPE_XML
        );
    }

    private function encodedAuth(): string
    {
        return base64_encode($this->user.':'.$this->password);
    }
}