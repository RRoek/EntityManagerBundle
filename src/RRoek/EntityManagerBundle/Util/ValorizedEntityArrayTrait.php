<?php

namespace RRoek\EntityManagerBundle\Model\Util;

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
    protected function _setValue(array &$data, $property, $object)
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
    protected function _getValue(array $data, $property, $default = '')
    {
        return array_key_exists($property, $data) ? $data[$property] : $default;
    }
}
