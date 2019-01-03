# JoliTypo Extra for MODX Revolution

This snippet uses JoliTypo for Microtypography fix as output modifier. Install it via MODX Package Management: https://modx.com/extras/package/jolitypo

# Usage

Use it like any other MODX Output Modifier:

``[[*content:jolitypo]]``

You can pass some options too. For example only replace Quotes:

``[[*content:jolitypo=`SmartQuotes`]]``

Documentation for JolyTypo is available at <https://github.com/jolicode/JoliTypo#available-fixers>.

By default all fixers are active, but you can limit it in the Snippet Settings. You can set the default language as System Setting (default is de_DE). If you have a multilanguage site, set it for each context.

**Author:** Jens Wittmann <https://jens-wittmann.de>
