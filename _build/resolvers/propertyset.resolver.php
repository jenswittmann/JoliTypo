<?php
/**
* Resolver to connect Property Sets to Elements for JoliTypo extra
*
* Copyright 2019 by Jens Wittmann <kontakt@jens-wittmann.de>
* Created on 01-02-2019
*
 * JoliTypo is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * JoliTypo is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * JoliTypo; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
* @package jolitypo
* @subpackage build
*/

/* @var $object xPDOObject */
/* @var $propertySetObj modPropertySet */
/* @var $elementObj modElement */
/* @var $elementPropertySet modElementPropertySet */

/* @var array $options */
if (!function_exists('getNameAlias')) {
    function getNameAlias($elementType)
    {
        switch ($elementType) {
            case 'modTemplate':
                $nameAlias = 'templatename';
                break;
            case 'modCategory':
                $nameAlias = 'category';
                break;
            case 'modResource':
                $nameAlias = 'pagetitle';
                break;
            default:
                $nameAlias = 'name';
                break;
        }
        return $nameAlias;

    }
}

if (!function_exists('checkFields')) {
    function checkFields($required, $objectFields) {
        global $modx;
        $fields = explode(',', $required);
        foreach ($fields as $field) {
            if (!isset($objectFields[$field])) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[PropertySet Resolver] Missing field: ' . $field);
                return false;
            }
        }
        return true;
    }
}
if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
        $intersects = array (
                0 =>  array (
                  'element' => 'jolitypo',
                  'element_class' => 'modSnippet',
                  'property_set' => 'jolitypo',
                ),
            );

        if (is_array($intersects)) {
            foreach ($intersects as $k => $fields) {
                /* make sure we have all fields */
                if (!checkFields('element,element_class,property_set', $fields)) {
                    continue;
                }
                $elementObj = $modx->getObject($fields['element_class'],
                    array(getNameAlias($fields['element_class']) => $fields['element']));

                $propertySetObj = $modx->getObject('modPropertySet', array('name' => $fields['property_set']));

                if (!$elementObj || !$propertySetObj) {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find Element and/or Property Set ' .
                        $fields['element'] . ' - ' . $fields['property_set']);
                    continue;
                }
                $fields['element'] = $elementObj->get('id');
                $fields['property_set'] = $propertySetObj->get('id');

                $tvt = $modx->getObject('modElementPropertySet', $fields);
                if (!$tvt) {
                    $tvt = $modx->newObject('modElementPropertySet');
                }
                if ($tvt) {
                    foreach($fields as $key => $value) {
                        $tvt->set($key, $value);
                    }
                    if (!$tvt->save()) {
                        $modx->log(xPDO::LOG_LEVEL_ERROR, 'Unknown error creating elementPropertySet intersect for ' .
                            $fields['element'] . ' - ' . $fields['property_set']);
                    }

                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Unknown error creating elementPropertySet intersect for ' .
                        $fields['element'] . ' - ' . $fields['property_set']);
                }
            }
        }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}

return true;