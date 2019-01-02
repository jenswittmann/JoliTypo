<?php

/*
 * This file is part of JoliTypo - a project by JoliCode.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace JoliTypo;

interface LocaleAwareFixerInterface
{
    /**
     * @param string $locale
     */
    public function setLocale($locale);
}
