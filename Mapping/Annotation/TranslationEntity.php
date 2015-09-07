<?php

namespace DavidDel\IntlBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class TranslationEntity extends Annotation
{
    /**
     * @var string
     * @Required
     */
    public $translatableClass;
}