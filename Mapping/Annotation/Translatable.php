<?php

namespace EcommerceAPI\ModelIntlBundle\Mapping\Annotation;

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