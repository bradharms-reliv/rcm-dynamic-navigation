<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\LinksFromData;
use RcmDynamicNavigation\Api\Render\RenderLinks;
use Zend\Diactoros\Response\JsonResponse;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinksController implements MiddlewareInterface
{
    protected $isAllowedAdmin;
    protected $renderLinks;

    /**
     * @param IsAllowedAdmin $isAllowedAdmin
     * @param RenderLinks    $renderLinks
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        RenderLinks $renderLinks
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;

        $this->renderLinks = $renderLinks;
    }

    /**
     * process
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

        $id = $request->getAttribute('id');

        $data = $request->getParsedBody();

        $links = [];

        if (!empty($data['links'])) {
            $links = $data['links'];
        }

        $links = LinksFromData::invoke($links);

        $html = $this->renderLinks->__invoke(
            $request,
            $links,
            [
                \RcmDynamicNavigation\Api\Render\RenderLinks::OPTION_ID => 'RcmDynamicNavigation_' . $id,
            ]
        );

        return new JsonResponse(
            [
                'html' => $html
            ]
        );
    }
}
