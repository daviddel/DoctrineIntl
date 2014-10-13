<?php

namespace Doctrine\Intl\Model;

class Translation implements TranslationInterface
{
    /**
     * @var TranslatableInterface
     */
    protected $translatable;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return TranslationInterface
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return TranslatableInterface
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * @param TranslatableInterface $translatable
     * @return TranslationInterface
     */
    public function setTranslatable(TranslatableInterface $translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }
} 