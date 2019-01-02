<?php
/**
 * propertySets transport file for JoliTypo extra
 *
 * Copyright 2019 by Jens Wittmann <kontakt@jens-wittmann.de>
 * Created on 01-02-2019
 *
 * @package jolitypo
 * @subpackage build
 */

if (! function_exists('stripPhpTags')) {
    function stripPhpTags($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<' . '?' . 'php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }
}
/* @var $modx modX */
/* @var $sources array */
/* @var xPDOObject[] $propertySets */


$propertySets = array();

$propertySets[1] = $modx->newObject('modPropertySet');
$propertySets[1]->fromArray(array (
  'id' => 1,
  'name' => 'jolitypo',
  'description' => '',
  'properties' => NULL,
), '', true, true);
return $propertySets;
