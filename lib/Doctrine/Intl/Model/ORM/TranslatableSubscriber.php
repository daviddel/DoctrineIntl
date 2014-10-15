<?php

namespace Doctrine\Intl\Model\ORM;

use Doctrine\Intl\Model\TranslatableInterface,
    Doctrine\Intl\Model\TranslationInterface;

use Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Mapping\ClassMetadata,
    Doctrine\ORM\Mapping\ClassMetadataInfo,
    Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\ORM\Events;

class TranslatableSubscriber implements EventSubscriber
{
    private $translatableFetchMode;
    private $translationFetchMode;

    public function __construct($translatableFetchMode, $translationFetchMode)
    {
        $this->translatableFetchMode = $this->convertFetchString($translatableFetchMode);
        $this->translationFetchMode = $this->convertFetchString($translationFetchMode);
    }

    /**
     * Adds mapping to the translatable and translations.
     *
     * @param LoadClassMetadataEventArgs $eventArgs The event arguments
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (null === $classMetadata->getReflectionClass()) {
            return;
        }

        if ($this->isTranslatable($classMetadata)) {
            $this->mapTranslatable($classMetadata);
        }

        if ($this->isTranslation($classMetadata)) {
            $this->mapTranslation($classMetadata);
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    private function mapTranslatable(ClassMetadata $classMetadata)
    {
        if (!$classMetadata->hasAssociation('translations')) {
            $classMetadata->mapOneToMany(array(
                'fieldName'     => 'translations',
                'mappedBy'      => 'translatable',
                'indexBy'       => 'locale',
                'cascade'       => array('all'),
                'fetch'         => $this->translatableFetchMode,
                'targetEntity'  => $this->getClassName($classMetadata),
                'orphanRemoval' => true
            ));
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    private function mapTranslation(ClassMetadata $classMetadata)
    {
        if (!$classMetadata->hasAssociation('translatable')) {
            $classMetadata->mapManyToOne(array(
                'fieldName'     => 'translatable',
                'inversedBy'    => 'translations',
                'fetch'         => $this->translationFetchMode,
                'joinColumns'   => array(array(
                    'name'                 => 'translatable_id',
                    'referencedColumnName' => 'id',
                    'onDelete'             => 'CASCADE'
                )),
                'targetEntity' => $this->getClassName($classMetadata)
            ));
        }

        if (!$classMetadata->hasField('locale')) {
            $classMetadata->mapField(array(
                'fieldName'     => 'locale',
                'type'          => 'string',
                'length'        => 10
            ));
        }

        $name = $classMetadata->getTableName().'_unique_translation';
        if (!$this->hasUniqueTranslationConstraint($classMetadata, $name)) {
            $classMetadata->setPrimaryTable(array(
                'uniqueConstraints' => array(array(
                    'name'    => $name,
                    'columns' => array('translatable_id', 'locale')
                )),
            ));
        }
    }

    /**
     * Convert string FETCH mode to required string
     *
     * @param $fetchMode
     * @return int
     */
    private function convertFetchString($fetchMode)
    {
        if (is_int($fetchMode)) {
            return $fetchMode;
        }

        switch ($fetchMode) {
            case "LAZY":
                return ClassMetadataInfo::FETCH_LAZY;
            case "EAGER":
                return ClassMetadataInfo::FETCH_EAGER;
            case "EXTRA_LAZY":
                return ClassMetadataInfo::FETCH_EXTRA_LAZY;
            default:
                return ClassMetadataInfo::FETCH_LAZY;
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     * @return string
     */
    private function getClassName(ClassMetadata $classMetadata)
    {
        $namespace = $classMetadata->getReflectionClass()->getNamespaceName();

        $annotation = 'Doctrine\\Intl\\Mapping\\Annotation\\TranslationEntity';
        if ($isTranslatable = $this->isTranslatable($classMetadata)) {
            $annotation = 'Doctrine\\Intl\\Mapping\\Annotation\\TranslatableEntity';
        }

        $reader = new AnnotationReader();
        $annotationClass = $reader->getClassAnnotation(
            $classMetadata->getReflectionClass(),
            $annotation);

        $class = $isTranslatable ? $annotationClass->translationClass : $annotationClass->translatableClass;
        if (!class_exists($class)) {
            $class = $namespace . '\\' . $class;
        }

        return $class;
    }

    private function hasUniqueTranslationConstraint(ClassMetadata $classMetadata, $name)
    {
        if (!isset($classMetadata->table['uniqueConstraints'])) {
            return false;
        }

        $constraints = array_filter($classMetadata->table['uniqueConstraints'], function($constraint) use ($name) {
            return (isset($constraint['name']) && $name === $constraint['name']);
        });

        return 0 !== count($constraints);
    }

    /**
     * Checks if entity is translatable
     *
     * @param ClassMetadata $classMetadata
     * @return boolean
     */
    private function isTranslatable(ClassMetadata $classMetadata)
    {
        return $classMetadata->newInstance() instanceof TranslatableInterface;
    }

    /**
     * Checks if entity is a translation
     *
     * @param  ClassMetadata $classMetadata
     * @return boolean
     */
    private function isTranslation(ClassMetadata $classMetadata)
    {
        return $classMetadata->newInstance() instanceof TranslationInterface;
    }

    /**
     * Returns hash of events, that this subscriber is bound to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata
        );
    }
}