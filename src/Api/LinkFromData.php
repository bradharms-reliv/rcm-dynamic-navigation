<?php

namespace RcmDynamicNavigation\Api;

use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class LinkFromData
{
    const LOGIN_CLASS = 'rcmDynamicNavigationLogin';
    const LOGOUT_CLASS = 'rcmDynamicNavigationLogout';

    /**
     * @param array $linkData
     *
     * @return NavLink
     */
    public static function invoke(
        array $linkData
    ): NavLink
    {
        $linkData = self::buildBcData($linkData);

        $subLinksData = Options::get(
            $linkData,
            'links',
            []
        );

        $id = Options::get(
            $linkData,
            'id',
            'BC:' . GetGuidV4::invoke() // @BC for missing Ids
        );

        return new NavLink(
            $id,
            Options::getRequired(
                $linkData,
                'display'
            ),
            Options::getRequired(
                $linkData,
                'href'
            ),
            Options::get(
                $linkData,
                'target',
                ''
            ),
            LinksFromData::invoke($subLinksData),
            Options::get(
                $linkData,
                'class',
                ''
            ),
            Options::get(
                $linkData,
                'isAllowedService',
                'default'
            ),
            Options::get(
                $linkData,
                'renderService',
                'default'
            ),
            Options::get(
                $linkData,
                'options',
                []
            )
        );
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcData(array $linkData)
    {
        $linkData = self::buildBcIsAllowedServiceOptions($linkData);
        $linkData = self::buildBcLogOutServiceOptions($linkData);
        $linkData = self::buildBcCleanClasses($linkData);

        return self::buildBcLogInServiceOptions($linkData);
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcCleanClasses(array $linkData)
    {
        if (!empty($linkData['class'])) {
            $linkData['class'] = str_replace('ui-sortable-handle', '', $linkData['class']);
        }

        return $linkData;
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcIsAllowedServiceOptions(array $linkData)
    {
        if (!empty($linkData['permissions'])) {
            $linkData['isAllowedService'] = 'show-if-has-access-role';
            $linkData['isAllowedServiceOptions'] = [
                'permissions' => $linkData['permissions'],
            ];

            unset($linkData['permissions']);
        }

        return $linkData;
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcLogOutServiceOptions(array $linkData)
    {
        $class = '';

        if (!empty($linkData['class'])) {
            $class = $linkData['class'];
        }

        if (strpos($class, self::LOGOUT_CLASS) !== false) {
            //$linkData['renderService'] = 'default';
            $linkData['isAllowedService'] = 'show-if-logged-in';
            $linkData['class'] = str_replace(self::LOGOUT_CLASS, '', $linkData['class']);
        }

        return $linkData;
    }

    /**
     * @param array $linkData
     *
     * @return array
     */
    protected static function buildBcLogInServiceOptions(array $linkData)
    {
        $class = '';

        if (!empty($linkData['class'])) {
            $class = $linkData['class'];
        }

        if (strpos($class, self::LOGIN_CLASS) !== false) {
            //$linkData['renderService'] = 'default';
            $linkData['isAllowedService'] = 'show-if-not-logged-in';
            $linkData['class'] = str_replace(self::LOGIN_CLASS, '', $linkData['class']);
        }

        return $linkData;
    }
}
