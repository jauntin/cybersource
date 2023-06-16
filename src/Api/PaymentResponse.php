<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Model\PtsV2PaymentsPost201Response;

/**
 * @final
 */
class PaymentResponse
{
    public string $id;
    public string $status;
    public ?string $orderReferenceNumber;
    public ?string $approvalCode;
    public ?string $reconciliationId;
    public ?string $amount;
    /**
     * @var mixed[]
     */
    public array $remoteResponse;

    public function fromResponse(PtsV2PaymentsPost201Response $response): self
    {
        $this->id = $response->getId();
        $this->status = $response->getStatus();
        $this->reconciliationId = $response->getReconciliationId();
        // The following are incorrectly typed as not null. (ignore by identifier will be available in phpstan 1.11)
        // @phpstan-ignore nullsafe.neverNull
        // @phpstan-ignore-next-line
        $this->orderReferenceNumber = $response->getClientReferenceInformation()?->getCode();
        // @phpstan-ignore nullsafe.neverNull
        // @phpstan-ignore-next-line
        $this->approvalCode = $response->getProcessorInformation()?->getApprovalCode();
        // @phpstan-ignore nullsafe.neverNull
        // @phpstan-ignore-next-line
        $this->amount = $response->getOrderInformation()?->getAmountDetails()?->getTotalAmount();

        $this->remoteResponse = json_decode($response->__toString(), true);

        return $this;
    }
}
