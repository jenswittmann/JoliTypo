<?php
/**
 * JoliTypo for MODX
 *
 * DESCRIPTION
 *
 * This snippet uses JoliTypo for Microtypography fix as output modifer. 
 * Example: [[*content:jolitypo=`Ellipsis Dimension Unit Dash SmartQuotes NoSpaceBeforeComma CurlyQuote Hyphen Trademark`]]
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

# vars
$corePath = $modx->getOption('jolitypo.core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/jolitypo/');
$snippetOptions = $modx->getOption('options', $scriptProperties);
$joliOptions = empty($snippetOptions) ? $modx->getOption('jolitypo.standardFixers', $scriptProperties) : $snippetOptions;
$lang = $modx->getOption('jolitypo.locale');

# include JoliTypo Class
require_once $corePath . 'vendor/autoload.php';	
use JoliTypo\Fixer;

# init JoliTypo
$fixer = new Fixer(
   explode(' ', $joliOptions)
);

# set language
$fixer->setLocale($lang);

# run JoliTypo and return
return $fixer->fix($input);