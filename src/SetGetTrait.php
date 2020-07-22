<?php

declare(strict_types=1);

namespace Umirode\SetGetTrait;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Laminas\Code\Reflection\DocBlockReflection;
use ReflectionException;
use ReflectionProperty;

/**
 * Trait SetGetTrait
 * @package Umirode\SetGetTrait
 */
trait SetGetTrait
{
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed|null
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SetGetError
     */
    public function __call(string $name, array $arguments = [])
    {
        return $this->handleSetGetMethods($name, $arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed|null
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SetGetError
     */
    private function handleSetGetMethods(string $name, array $arguments = [])
    {
        $action = $this->getActionByMethod($name);
        $property = $this->getPropertyByMethodAndAction($name, $action);

        $reflectionProperty = $this->getReflectionProperty($property);
        $propertyAnnotation = $this->getPropertyAnnotation($reflectionProperty);

        if ($action === 'set') {
            $this->setPropertyValue($arguments[0], $property, $reflectionProperty, $propertyAnnotation);
            return null;
        }

        if ($action === 'get' && (!$propertyAnnotation || ($propertyAnnotation && $propertyAnnotation->get))) {
            return $this->getPropertyValue($property, $reflectionProperty, $propertyAnnotation);
        }

        throw new SetGetError('Call to undefined method ' . get_class($this) . '::' . $name . '()');
    }

    /**
     * @param $value
     * @param string $property
     * @param ReflectionProperty $reflectionProperty
     * @param Property $propertyAnnotation
     * @throws SetGetError
     */
    private function setPropertyValue(
        $value,
        string $property,
        ReflectionProperty $reflectionProperty,
        Property $propertyAnnotation
    ): void {
        if (!$propertyAnnotation->set) {
            throw new SetGetError('Permission error, property "' . $property . '" is read only');
        }

        if (!$this->isValidValueForProperty($reflectionProperty, $value)) {
            throw new SetGetError(
                'Type error, value of type "' . gettype($value) . '" is not valid for property "' . $property . '"'
            );
        }

        $this->$property = $value;
    }

    /**
     * @param string $property
     * @param ReflectionProperty $reflectionProperty
     * @param Property $propertyAnnotation
     * @return mixed
     * @throws SetGetError
     */
    private function getPropertyValue(
        string $property,
        ReflectionProperty $reflectionProperty,
        Property $propertyAnnotation
    ) {
        if (!$propertyAnnotation->get) {
            throw new SetGetError('Permission error, property "' . $property . '" is write only');
        }

        if (!$this->isValidValueForProperty($reflectionProperty, $this->$property)) {
            throw new SetGetError(
                'Type error, value of type "' . gettype($this->$property) . '" is not valid for method "set' . ucfirst(
                    $property
                ) . '"'
            );
        }

        return $this->$property;
    }

    /**
     * @param string $method
     * @return string
     * @throws SetGetError
     */
    private function getActionByMethod(string $method): string
    {
        $actions = ['get', 'set'];

        foreach ($actions as $action) {
            if (preg_match('/' . $action . '[A-Za-z]\w+/', $method)) {
                return $action;
            }
        }

        throw new SetGetError('Call to undefined method "' . $method . '"');
    }

    /**
     * @param string $method
     * @param string $action
     * @return string
     * @throws SetGetError
     */
    private function getPropertyByMethodAndAction(string $method, string $action): string
    {
        $values = explode($action, $method);
        if (count($values) !== 2) {
            throw new SetGetError('Invalid method name "' . $method . '"');
        }

        $property = lcfirst($values[1]);
        if (!property_exists($this, $property)) {
            throw new SetGetError('Property "' . $property . '" not exists');
        }

        return $property;
    }

    /**
     * @param ReflectionProperty $property
     * @param mixed $value
     * @return bool
     */
    private function isValidValueForProperty(ReflectionProperty $property, $value): bool
    {
        $types = $this->getPropertyTypes($property);
        if (empty($types)) {
            return true;
        }

        return in_array(strtolower(gettype($value)), $types, true);
    }

    /**
     * @param ReflectionProperty $property
     * @return array
     */
    private function getPropertyTypes(ReflectionProperty $property): array
    {
        if (!$property->getDocComment()) {
            return [];
        }

        $docComment = new DocBlockReflection($property->getDocComment());
        return $docComment->getTag('var')->getTypes();
    }

    /**
     * @param string $property
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    private function getReflectionProperty(string $property): ReflectionProperty
    {
        return (new \ReflectionClass($this))->getProperty($property);
    }

    /**
     * @param ReflectionProperty $property
     * @return Property
     * @throws AnnotationException
     */
    private function getPropertyAnnotation(ReflectionProperty $property): Property
    {
        return (new AnnotationReader())->getPropertyAnnotation($property, Property::class) ?? new Property();
    }
}
