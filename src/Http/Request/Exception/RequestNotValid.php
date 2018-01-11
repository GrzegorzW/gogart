<?php

declare(strict_types = 1);

namespace Gogart\Http\Request\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class RequestNotValid extends \InvalidArgumentException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $validationErrors;

    /**
     * @param ConstraintViolationListInterface $validationErrors
     */
    public function __construct(ConstraintViolationListInterface $validationErrors)
    {
        $this->validationErrors = $validationErrors;

        parent::__construct();
    }

    /**
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function getValidationErrors(): array
    {
        $result = [];

        /** @var ConstraintViolation $error */
        foreach ($this->validationErrors as $error) {
            $field = $error->getPropertyPath();

            if (!isset($result[$field])) {
                $result[$field] = [];
            }

            $code = $error->getCode();
            $constraint = $error->getConstraint();

            $message = $constraint && $code ? mb_strtolower($constraint::getErrorName($code)) : $error->getMessage();

            $result[$field][] = $message;
        }

        return $result;
    }
}
