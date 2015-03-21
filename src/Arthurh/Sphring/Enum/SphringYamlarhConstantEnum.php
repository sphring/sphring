<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/03/2015
 */

namespace Arthurh\Sphring\Enum;


use MyCLabs\Enum\Enum;

class SphringYamlarhConstantEnum extends Enum
{
    const ROOTPROJECT = 'ROOTPROJECT';
    const CONTEXTROOT = 'CONTEXTROOT';
    const SERVER = 'SERVER';
    const POST = 'POST';
    const GET = 'GET';
    const PROPERTY_FILE_NODE = 'properties-file';
    const PARAMETERNAME = 'sph';
}
