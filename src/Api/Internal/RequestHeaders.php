<?php

namespace Jauntin\CyberSource\Api\Internal;

/**
 * @final
 * @internal
 */
class RequestHeaders
{
    public const METHOD_GET = 'get';
    public const METHOD_POST = 'post';
    public function __construct(private Configuration $configuration)
    {
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function generate(string $resourcePath, string $method, ?string $payload = null): array
    {
        $date = now()->toRfc7231String();
        $host = $this->configuration->host;
        $headers = [
            // Having `Accept: application/json` present caused 404 in the payments endpoint
            // setting application/hal+json;charset=utf-8 could be an option, but it seems to not be necessary
            // 'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'v-c-merchant-id' => $this->configuration->merchantId,
            'Date' => $date,
        ];
        if ($payload) {
            $digest = $this->generateDigest($payload);
            $headers = array_merge(
                $headers,
                [
                    'signature' => $this->accessTokenHeader(
                        "host: " . $host . "\ndate: " . $date . "\nrequest-target: " . $method . " " . $resourcePath . "\ndigest: " . "SHA-256=" . $digest . "\nv-c-merchant-id: " . $this->configuration->merchantId,
                        'host date request-target digest v-c-merchant-id'
                    ),
                    'Digest' => 'SHA-256=' . $digest,
                ]
            );
        } else {
            $headers = array_merge(
                $headers,
                [
                    'signature' => $this->accessTokenHeader(
                        'host date request-target v-c-merchant-id',
                        "host: " . $host . "\ndate: " . $date . "\nrequest-target: " . $method . " " . $resourcePath . "\nv-c-merchant-id: " . $this->configuration->merchantId,
                    )
                ],
            );
        }
        return $headers;
    }

    private function generateDigest(string $payload): string
    {
        return base64_encode(hash("sha256", $payload, true));
    }

    private function accessTokenHeader(string $signatureString, string $headerString): string
    {
        return implode(", ", [
            'keyid="' . $this->configuration->apiKeyId . '"',
            'algorithm="HmacSHA256"',
            'headers="' . $headerString . '"',
            'signature="' . base64_encode(
                hash_hmac("sha256", $signatureString, base64_decode($this->configuration->secretKey), true)
            ) . '"'
        ]);
    }
}
