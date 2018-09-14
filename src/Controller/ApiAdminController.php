<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\GetIsAllowedServicesConfig;
use RcmDynamicNavigation\Api\GetRenderServicesConfig;
use Zend\Diactoros\Response\JsonResponse;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminController implements MiddlewareInterface
{
    protected $isAllowedAdmin;
    protected $getRenderServicesConfig;
    protected $getIsAllowedServicesConfig;

    /**
     * @param IsAllowedAdmin             $isAllowedAdmin
     * @param GetIsAllowedServicesConfig $getIsAllowedServicesConfig
     * @param GetRenderServicesConfig    $getRenderServicesConfig
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        GetIsAllowedServicesConfig $getIsAllowedServicesConfig,
        GetRenderServicesConfig $getRenderServicesConfig
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;

        $this->getIsAllowedServicesConfig = $getIsAllowedServicesConfig;
        $this->getRenderServicesConfig = $getRenderServicesConfig;
    }

    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface|null $delegate
     *
     * @return mixed
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate = null
    ) {
        if (!$this->isAllowedAdmin->__invoke($request, [])) {
            new JsonResponse(
                null,
                401
            );
        }

        return new JsonResponse(
            [
                'isAllowedServices' => $this->getIsAllowedServicesConfig->__invoke(),
                'renderServices' => $this->getRenderServicesConfig->__invoke(),
            ]
        );
    }
}
