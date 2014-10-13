<?php

namespace Doctrine\Intl\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Translatable extends Annotation
{
    /** @var boolean */
    public $fallback;
}