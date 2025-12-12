<?php

namespace App\Core\Infrastructure\Security;

use App\Core\Contracts\Cache\CacheServiceInterface;
use App\Core\Contracts\Security\OtpServiceInterface;
use App\Core\Infrastructure\Cache\RedisCacheService;
use App\Modules\Authentication\Domain\ValueObjects\OtpCode;
use App\Modules\Authentication\Domain\Exceptions\OtpExpiredException;
use App\Modules\Authentication\Domain\Exceptions\TooManyAttemptsException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class OtpService implements OtpServiceInterface
{

    public function __construct(
        private CacheServiceInterface $cacheService,
    ){ }



    public function generate(string $key, int $ttl = 300): OtpCode
    {
        $otp = new OtpCode(random_int(100000, 999999));

        $this->cacheService->set(
            $key,
            $otp,
            $ttl
        );
       
        return $otp;
    }

    public function validate(string $key, string $code): bool
    {
        $stored = $this->cacheService->get($key);

        if ($stored === null) {
            throw new OtpExpiredException();
        }

        // Extract the actual code string from the value object
        $storedCode = $stored->value();
        
        // Use timing-safe comparison
        if (!hash_equals($storedCode, $code)) {
  
            // Track failed attempts (implement rate limiting)
            $this->incrementFailedAttempts($key);
            
            return false;
        }

        // One-time OTP → consume it
        $this->cacheService->delete($key);
        return true;
    }

    private function incrementFailedAttempts(string $key): void
    {
        $attemptsKey = "otp_attempts:{$key}";
        $attempts = (int) $this->cacheService->get($attemptsKey, 0);
        
        if ($attempts >= 5) { // Max 5 attempts
            $this->cacheService->delete($key); // Invalidate OTP
            throw new TooManyAttemptsException();
        }
        
        $this->cacheService->set($attemptsKey, $attempts + 1, 900); // 15 min TTL
    }

    public function delete(string $key): void
    {
        $this->cacheService->delete($key);
    }
}
