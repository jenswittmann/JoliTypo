# JoliTypo for <img src="https://raw.githubusercontent.com/modxcms/revolution/2.x/manager/templates/default/images/modx-icon-color.svg" width="20"> MODX

Use JoliTypo for Microtypography as Output Filter in MODX.

Author: [Jens Wittmann](https://jens-wittmann.de)  
Official JoliTypo Documentation: https://github.com/jolicode/JoliTypo

## System Settings

| key | value | default |
| --------------- | --------------- | --------------- |
| jolitypo.customReplaces | Add custom replaces before JoliTypo runs. Usefull for custom replaces like `z. B.` to `z.B.`. One replace per line. See example below. |  |
| jolitypo.locale | Local Key to use for Microtypography. See [Documentation](https://github.com/jolicode/JoliTypo#fixer-recommendations-by-locale). | de_DE |
| jolitypo.standardFixers | Set Fixers for JoliTypo. See [Documentation](https://github.com/jolicode/JoliTypo#available-fixers) | Ellipsis Dimension Dash SmartQuotes NoSpaceBeforeComma Trademark |

#### Example for jolitypo.customReplaces

```
e. V.==e.V.
z. B.==z.B.
®==&reg;
&reg;==<sup>&reg;</sup>
&sect; ==&sect;&nbsp;
§ ==&sect;&nbsp;
```

## ⚠️ Attention

Don't use the Fixer [CurlyQuote (Smart Quote)](https://github.com/jolicode/JoliTypo#curlyquote-smart-quote), because the `-letter can break the MODX Placeholder Syntax.
