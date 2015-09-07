<?php

namespace DavidDel\IntlBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class TranslatableEntity extends Annotation
{
    /**
     * @var string
     * @Required
     */
    public $translationClass;
}