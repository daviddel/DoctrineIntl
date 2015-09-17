<?php

namespace DavidDel\DoctrineIntlBundle\Model;

interface TranslationProxyInterface
{
    /**
     * @return TranslationInterface
     */
    function getTranslation();
} 