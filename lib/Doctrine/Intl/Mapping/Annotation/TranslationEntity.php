<?php

namespace Doctrine\Intl\Mapping\Annotation;

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
    public $class;
}