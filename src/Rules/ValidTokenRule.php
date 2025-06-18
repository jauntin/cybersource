<?php

namespace Jauntin\CyberSource\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Throwable;

class ValidTokenRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        try {
            [$header, $payload, $signature] = explode('.', $value);
            if (empty($header) || empty($payload) || empty($signature)) {
                throw new InvalidArgumentException('Invalid token format.');
            }

            $payload = json_decode(json: base64_decode($payload), flags: JSON_THROW_ON_ERROR);
            if ($payload->exp < ((int) Carbon::now()->timestamp) + 5) {
                $fail('The :attribute token is expired.');
            }
        } catch (Throwable $e) {
            $fail('The :attribute is not a valid token.');
        }
    }
}
