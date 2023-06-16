<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Model\PtsV2PaymentsRefundPost201Response;

/**
 * @final
 */
class RefundResponse
{
    public string $id;
    public string $status;
    public string $orderReferenceNumber;
    public string $reconciliationId;
    public string $refundAmount;

    /**
     * @var mixed[]
     */
    public array $remoteResponse;

    public function fromResponse(PtsV2PaymentsRefundPost201Response $response): self
    {
        $this->id = $response->getId();
        $this->status = $response->getStatus();
        $this->orderReferenceNumber = $response->getClientReferenceInformation()->getCode();
        $this->reconciliationId = $response->getReconciliationId();
        $this->refundAmount = $response->getRefundAmountDetails()->getRefundAmount();

        $this->remoteResponse = json_decode($response->__toString(), true);

        return $this;
    }
}
