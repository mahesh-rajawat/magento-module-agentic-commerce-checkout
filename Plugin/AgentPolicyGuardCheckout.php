<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Webapi\Controller\Rest;
use MSR\AgenticUcp\Plugin\Webapi\AgentPolicyGuard;

/**
 * Extends the base UCP policy guard capability map with checkout routes.
 * Uses a before plugin as a hook point for third-party modules to add
 * capabilities via DI plugin chain.
 */
class AgentPolicyGuardCheckout
{
    /**
     * Before plugin on AgentPolicyGuard::beforeDispatch().
     * The base guard's CAPABILITY_MAP already covers all checkout routes.
     * This plugin is a hook point for third-party modules to add capabilities.
     *
     * @param AgentPolicyGuard $subject
     * @param Rest $restController
     * @param RequestInterface $request
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(
        AgentPolicyGuard $subject,
        Rest $restController,
        RequestInterface $request
    ): array {
        return [$restController, $request];
    }
}
