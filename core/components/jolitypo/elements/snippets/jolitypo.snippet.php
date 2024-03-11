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
$corePath = $modx->getOption(
    "jolitypo.core_path",
    null,
    $modx->getOption("core_path", null, MODX_CORE_PATH) . "components/jolitypo/"
);
$snippetOptions = $modx->getOption("options", $scriptProperties);
$joliOptions = empty($snippetOptions)
    ? $modx->getOption("jolitypo.standardFixers")
    : $snippetOptions;
$lang = $modx->getOption("jolitypo.locale");
$customReplaces = $modx->getOption("jolitypo.customReplaces");

# get JoliTypo
require_once $corePath . "vendor/autoload.php";

use JoliTypo\Fixer;

# init JoliTypo
$fixer = new Fixer(explode(" ", $joliOptions));

# set language
$fixer->setLocale($lang);

# get custom replaces
if (!empty($customReplaces)) {
    foreach (explode(PHP_EOL, $customReplaces) as $customReplace) {
        list($search, $replace) = explode("==", $customReplace);
        $input = str_replace($search, $replace, $input);
    }
}

# run JoliTypo and return
$input = $fixer->fix($input);

return $input;