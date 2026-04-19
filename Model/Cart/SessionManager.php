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

    /**
     * @var string|null
     */
    private ?string $agentDid = null;

    /**
     * @param CacheInterface $cache
     * @param RequestInterface $request
     */
    public function __construct(
        private readonly CacheInterface  $cache,
        private readonly RequestInterface $request,
    ) {
    }

    /**
     * Get the masked cart ID for the current agent.
     *
     * @return string|null
     */
    public function getMaskedCartId(): ?string
    {
        $key    = $this->getCacheKey();
        $result = $this->cache->load($key);
        return $result ?: null;
    }

    /**
     * Store the masked cart ID for the current agent.
     *
     * @param string $maskedId
     * @return void
     */
    public function setMaskedCartId(string $maskedId): void
    {
        $this->cache->save($maskedId, $this->getCacheKey(), ['UCP_CART'], self::CART_TTL);
    }

    /**
     * Clear the stored cart ID for the current agent.
     *
     * @return void
     */
    public function clearCart(): void
    {
        $this->cache->remove($this->getCacheKey());
    }

    /**
     * Build the cache key for the current agent DID.
     *
     * @return string
     */
    private function getCacheKey(): string
    {
        return self::CACHE_PREFIX . hash('sha256', $this->getAgentDid());
    }

    /**
     * Extract the agent DID from the Bearer token sub claim.
     *
     * @return string
     */
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
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
                $this->agentDid = $payload['sub'] ?? 'anonymous';
                return $this->agentDid;
            }
        }

        $this->agentDid = 'anonymous';
        return $this->agentDid;
    }
}
