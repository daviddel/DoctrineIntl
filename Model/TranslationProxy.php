<?php

namespace DavidDel\DoctrineIntlBundle\Model;

use Doctrine\Common\Annotations\AnnotationReader;

class TranslationProxy implements TranslationProxyInterface
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
     * @param TranslatableInterface $translatable
     * @param string|null $locale
     */
    public function __construct($translatable, $locale = null)
    {
        $this->translatable = $translatable;
        $this->locale = $locale;
    }

    /**
     * @return TranslationInterface
     */
    public function getTranslation()
    {
        $locale = null === $this->locale ? \Locale::getDefault() : $this->locale;
        $translations = $this->translatable->getTranslations();

        if (isset($translations[$locale])) {
            return $translations[$locale];
        }
        elseif (isset($translations[substr($locale, 0, strpos($locale, '_'))])) {
            return $translations[substr($locale, 0, strpos($locale, '_'))];
        }

        $class = get_class($this->translatable);

        if ($this->translatable instanceof \Doctrine\Common\Persistence\Proxy) {
            $class = \Doctrine\Common\Util\ClassUtils::getRealClass($class);
        }

        $translationClass = $this->getTranslationClassName($class);

        return $this->createTranslation($translationClass, $locale);
    }

    /**
     * @param string $class
     * @return string
     */
    private function getTranslationClassName($class)
    {
        $reflectionClass = new \ReflectionClass($class);
        $namespace = $reflectionClass->getNamespaceName();

        $reader = new AnnotationReader();
        $annotationClass = $reader->getClassAnnotation(
            $reflectionClass,
            'DavidDel\\DoctrineIntlBundle\\Mapping\\Annotation\\TranslatableEntity');

        $class = $annotationClass->translationClass;
        if (!class_exists($class)) {
            $class = $namespace . '\\' . $class;
        }

        return $class;
    }

    /**
     * @param string $class
     * @param string $locale
     * @return TranslationInterface
     */
    private function createTranslation($class, $locale)
    {
        /** @var TranslationInterface $translation */
        $translation = new $class();
        $translation->setTranslatable($this->translatable);
        $translation->setLocale($locale);
        $this->translatable->addTranslation($translation);

        return $translation;
    }

    public function __call($method, $arguments)
    {
        $translation = $this->getTranslation();

        if (!method_exists($translation, $method)) {
            throw new \RuntimeException(sprintf('Call to undefined method "%s" on entity "%s".', $method, get_class($translation)));
        }

        return call_user_func_array(array($translation, $method), $arguments);
    }
} 