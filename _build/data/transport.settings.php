<?php
/**
 * systemSettings transport file for JoliTypo extra
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
/* @var xPDOObject[] $systemSettings */


$systemSettings = array();

$systemSettings[1] = $modx->newObject('modSystemSetting');
$systemSettings[1]->fromArray(array (
  'key' => 'jolitypo.locale',
  'value' => 'de_DE',
  'xtype' => 'textfield',
  'namespace' => 'jolitypo',
  'area' => '',
  'name' => 'locale',
  'description' => 'What language key for locale?',
), '', true, true);
return $systemSettings;
