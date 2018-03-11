<?php

namespace RRoek\EntityManagerBundle\Util;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Class ConstraintViolationUtil.
 *
 * @codeCoverageIgnore
 */
class ConstraintViolationUtil
{
    /**
     * @param string $message
     *
     * @return null
     */
    protected static function _createValidatorException(string $message)
    {
        throw new ValidatorException($message);
    }

    /**
     * Converts the violation into a string for debugging purposes.
     *
     * @param array $constraintViolationMessagesList
     *
     * @return string The violation as string
     */
    protected static function _constraintViolationListToString(array $constraintViolationMessagesList)
    {
        return implode('. ', $constraintViolationMessagesList);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     *
     * @return null
     */
    public static function throwConstraintViolationListException($constraintViolationList)
    {
        $constraintViolationMessagesList = [];
        foreach ($constraintViolationList as $error) {
            $constraintViolationMessagesList[] = $error->getMessage();
        }

        return self::_createValidatorException(
            self::_constraintViolationListToString($constraintViolationMessagesList)
        );
    }
}
