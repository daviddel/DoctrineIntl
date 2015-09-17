<?php

namespace DavidDel\DoctrineIntlBundle\Model;

use Doctrine\Common\Collections\Collection;

interface TranslatableInterface
{
    /**
     * @return Collection
     */
    function getTranslations();

    /**
     * @param TranslationInterface $translation
     * @return mixed
     */
    function addTranslation(TranslationInterface $translation);

    /**
     * @param TranslationInterface $translation
     * @return mixed
     */
    function removeTranslation(TranslationInterface $translation);

    /**
     * @param string|null $locale
     * @return TranslationInterface
     */
    function getTranslation($locale = null);

    /**
     * @param string|null $locale
     * @return TranslationProxyInterface
     */
    function getTranslationProxy($locale = null);
} 