<?php

namespace EcommerceAPI\ModelIntlBundle\Model;

interface TranslationInterface
{
    /**
     * @return string
     */
    function getLocale();

    /**
     * @param string $locale
     */
    function setLocale($locale);

    /**
     * @return TranslatableInterface
     */
    function getTranslatable();

    /**
     * @param TranslatableInterface $translatable
     */
    function setTranslatable(TranslatableInterface $translatable);
} 