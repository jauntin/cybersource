<?php

namespace Jauntin\CyberSource\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jauntin\CyberSource\Api\Internal\Configuration;
use Jauntin\CyberSource\Api\Internal\MicroformSessionResponseAdapter;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Throwable;

/**
 * @final
 */
class MicroformSessionService
{
    public function __construct(
        private MicroformSessionResponseAdapter $microformSessionResponseAdapter,
        private RequestHeaders $requestHeaders,
        private Configuration $configuration,
    ) {}

    public function microformSession(MicroformSessionRequest $microformSessionRequest): MicroformSessionResponse|ErrorResponse
    {
        $logKey = 'cybersource.microformSession: ';
        try {
            Log::info($logKey.'Begin request');
            $resourcePath = '/microform/v2/sessions';
            $body = json_encode($microformSessionRequest, JSON_THROW_ON_ERROR);
            $request = Http::withHeaders($this->requestHeaders->generate($resourcePath, RequestHeaders::METHOD_POST, $body));
            $request->withBody($body);
            $response = $request->post('https://'.$this->configuration->host.$resourcePath);
            if ($response->failed()) {
                Log::warning($logKey.'Request failed.', ['response' => ['status' => $response->status(), 'body' => $response->body()]]);
                $response->throw();
            }
            Log::info($logKey.'Request successful');

            return $this->microformSessionResponseAdapter->fromResponse($response->body());
        } catch (Throwable $e) {
            Log::error($logKey.'Request failed', ['error' => ['message' => $e->getMessage()]]);

            return App::make(ErrorResponse::class)->fromThrowable($e);
        }
    }
}
