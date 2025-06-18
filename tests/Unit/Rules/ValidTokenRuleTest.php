<?php

namespace Tests\Unit\Rules;

use Illuminate\Support\Carbon;
use Jauntin\CyberSource\Rules\ValidTokenRule;
use Jauntin\CyberSource\Testing\MocksPaymentServices;
use PHPUnit\Framework\TestCase;

class ValidTokenRuleTest extends TestCase
{
    use MocksPaymentServices;

    private function getRule(): ValidTokenRule
    {
        return new ValidTokenRule;
    }

    public function test_valid_token_passes()
    {
        Carbon::setTestNow('2025-06-18 12:00:00');
        $rule = $this->getRule();
        // A real test token retrieved from the CyberSource API on 2025-06-18 Afternoon
        $token = 'eyJraWQiOiIwN2h6aUlBbVBkSzFrSkRoWWFTUXMwVmowdjVUTFQ1TCIsImFsZyI6IlJTMjU2In0.eyJpc3MiOiJGbGV4LzA4IiwiZXhwIjoxNzUwMjYxNTgyLCJ0eXBlIjoibWYtMi4xLjAiLCJpYXQiOjE3NTAyNjA2ODIsImp0aSI6IjFDMEEyNjJDSDlHQUozNzJaVVlTVVlLMDBGOVY5M0RBRkhJWlpQQjg5VUhRQVc1VElFQ0s2ODUyREY0RUVGRjYiLCJjb250ZW50Ijp7InBheW1lbnRJbmZvcm1hdGlvbiI6eyJjYXJkIjp7ImV4cGlyYXRpb25ZZWFyIjp7InZhbHVlIjoiMjAzMyJ9LCJudW1iZXIiOnsiZGV0ZWN0ZWRDYXJkVHlwZXMiOlsiMDAxIl0sIm1hc2tlZFZhbHVlIjoiWFhYWFhYWFhYWFhYNDI0MiIsImJpbiI6IjQyNDI0MiJ9LCJzZWN1cml0eUNvZGUiOnt9LCJleHBpcmF0aW9uTW9udGgiOnsidmFsdWUiOiIxMSJ9fX19fQ.UPu_uVY0ecoMiCsfW_zT9tc0CU3OWDRgcLsj6PabtXR-QE75WC3cVPxx8WPx2tvAAMKHHd9HBzmv7zAomqdXDpZhT8hnT16x9tG2OLsQl-1Rpe3WrxIydcyuLSHfGqcHQc5F4mWy5x3C3JuoYQR8uuhmvIBfn29VB6kcdVBI5x_5HcQEnTGRnd2Ax6c54PME_T9u6QjEOK6VgXFyRGOqKWKkzKYekzt8k9S1Mxds9SPrJm7tEtWCxncErZLnnAT0ng-j2lVEFEiHcD1if1dh9KkTETMREMKX3fR0RlMCOPGm8JKIpks_BcOcCF8Dhy2aJXFpPEIB5FtZVqBlExzUwA';
        $result = true;
        $rule->validate('token', $token, function () use (&$result) {
            $result = false;
        });
        $this->assertTrue($result, 'Valid token should pass validation.');
    }

    public function test_expired_token_fails()
    {
        $rule = $this->getRule();
        $payload = base64_encode(json_encode(['exp' => Carbon::now()->timestamp - 10]));
        $token = 'header.'.$payload.'.signature';
        $failMessage = null;
        $rule->validate('token', $token, function ($message) use (&$failMessage) {
            $failMessage = $message;
        });
        $this->assertEquals('The :attribute token is expired.', $failMessage, 'Expired token should fail with correct message.');
    }

    public function test_malformed_token_fails()
    {
        $rule = $this->getRule();
        $token = 'not.a.valid.token';
        $failMessage = null;
        $rule->validate('token', $token, function ($message) use (&$failMessage) {
            $failMessage = $message;
        });
        $this->assertEquals('The :attribute is not a valid token.', $failMessage, 'Malformed token should fail with correct message.');
    }

    public function test_token_with_empty_parts_fails()
    {
        $rule = $this->getRule();
        $token = 'header..signature';
        $failMessage = null;
        $rule->validate('token', $token, function ($message) use (&$failMessage) {
            $failMessage = $message;
        });
        $this->assertEquals('The :attribute is not a valid token.', $failMessage, 'Token with empty parts should fail with correct message.');
    }

    public function test_empty_token_passes()
    {
        $rule = $this->getRule();
        $result = true;
        $rule->validate('token', '', function () use (&$result) {
            $result = false;
        });
        $this->assertTrue($result, 'Empty token should pass validation.');
    }

    public function test_fake_valid_token_passes()
    {
        $rule = $this->getRule();
        $token = $this->getFakeValidToken();
        $result = true;
        $rule->validate('token', $token, function () use (&$result) {
            $result = false;
        });
        $this->assertTrue($result, 'Confirming that the fake valid token passes validation.');
    }
}
