<?php

namespace DavidDel\DoctrineIntlBundle\Mapping\Annotation;

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