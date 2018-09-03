<?php

namespace RRoek\EntityManagerBundle\Util;

/**
 * Trait ValorizedEntityArrayTrait.
 */
trait ValorizedEntityArrayTrait
{
    /**
     * Set property of given data array.
     * If the key not exists default value will be returned.
     *
     * @param array  $data
     * @param string $property
     * @param mixed  $object
     *
     * @return mixed
     */
    protected function setValue(array &$data, $property, $object)
    {
        $data[$property] = $object;

        return $data;
    }

    /**
     * Returns property of given data array.
     * If the key not exists default value will be returned.
     *
     * @param array  $data
     * @param string $property
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function getValue(array $data, $property, $default = '')
    {
        return array_key_exists($property, $data) ? $data[$property] : $default;
    }
}
