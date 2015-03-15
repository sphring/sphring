<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */

namespace Arthurh\Sphring\Enum;

use MyCLabs\Enum\Enum;

/**
 * Enum for all eventName
 * Class SphringEventEnum
 * @package Arthurh\Sphring\Enum
 */
class SphringEventEnum extends Enum
{
    const PROPERTY_INJECTION = 'property.injection.';
    const ANNOTATION_CLASS = 'annotation.class.';
    const ANNOTATION_METHOD = 'annotation.method.';

    const SPHRING_BEFORE_LOAD = 'sphring.load.before';
    const SPHRING_START_LOAD = 'sphring.load.start';

    const SPHRING_START_LOAD_CONTEXT = 'sphring.load.start.context';
    const SPHRING_FINISHED_LOAD_CONTEXT = 'sphring.load.after.context';

    const SPHRING_FINISHED_LOAD = 'sphring.load.after';

    const SPHRING_CLEAR = 'sphring.clear';

    const ANNOTATION_CLASS_CALL_INSTANTIATE = 'annotation.class.call.instantiate.';
    const ANNOTATION_METHOD_CALL_BEFORE = 'annotation.method.call.before.';
    const ANNOTATION_METHOD_CALL_AFTER = 'annotation.method.call.after.';
}
