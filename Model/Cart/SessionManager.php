<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model\Cart;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Stores a masked cart ID keyed by agent DID extracted from the Bearer token.
 * Each agent DID gets its own persistent cart across requests.
 */
class SessionManager
{
    private const CACHE_PREFIX = 'ucp_cart_';
    private const CART_TTL     = 86400; // 24 hours

    private ?string $agentDid = null;

    public function __construct(
        private readonly CacheInterface  $cache,
        private readonly RequestInterface $request,
    ) {}

    public function getMaskedCartId(): ?string
    {
        $key    = $this->getCacheKey();
        $result = $this->cache->load($key);
        return $result ?: null;
    }

    public function setMaskedCartId(string $maskedId): void
    {
        $this->cache->save($maskedId, $this->getCacheKey(), ['UCP_CART'], self::CART_TTL);
    }

    public function clearCart(): void
    {
        $this->cache->remove($this->getCacheKey());
    }

    private function getCacheKey(): string
    {
        return self::CACHE_PREFIX . md5($this->getAgentDid());
    }

    private function getAgentDid(): string
    {
        if ($this->agentDid !== null) {
            return $this->agentDid;
        }

        // Extract DID from Bearer token sub claim
        $authHeader = $this->request->getHeader('Authorization') ?? '';
        if (str_starts_with($authHeader, 'Bearer ')) {
            $jwt     = substr($authHeader, 7);
            $parts   = explode('.', $jwt);
            if (count($parts) === 3) {
                $payload = json_decode(
                    base64_decode(strtr($parts[1], '-_', '+/')),
                    true
                );
                $this->agentDid = $payload['sub'] ?? 'anonymous';
                return $this->agentDid;
            }
        }

        $this->agentDid = 'anonymous';
        return $this->agentDid;
    }
}
