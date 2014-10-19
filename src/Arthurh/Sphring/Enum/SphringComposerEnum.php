<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 17/10/2014
 */

namespace Arthurh\Sphring\Enum;

use MyCLabs\Enum\Enum;

class SphringComposerEnum extends Enum
{
    const EXTRA_SPHRING_ISPLUGIN_COMPOSER_KEY = "isPlugin";
    const TYPE_COMPOSER_KEY = "type";
    const EXTRA_COMPOSER_KEY = "extra";
    const EXTRA_SPHRING_COMPOSER_KEY = "sphring";
    const EXTRA_SPHRING_EXTEND_COMPOSER_KEY = "extend";
}
