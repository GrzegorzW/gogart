<?php

declare(strict_types = 1);

namespace Gogart\Http\Request\ParamConverter;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UuidParamConverter implements ParamConverterInterface
{
    /**
     * @param Request $request
     * @param ParamConverter $configuration
     *
     * @return bool
     *
     * @throws BadRequestHttpException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $paramName = $configuration->getName();
        $paramValue = $request->get($paramName);

        if (Uuid::isValid($paramValue) === false) {
            throw new BadRequestHttpException('Invalid format. UUID required');
        }

        $uuid = Uuid::fromString($paramValue);

        $request->attributes->set($paramName, $uuid);

        return true;
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() !== null && $configuration->getClass() === UuidInterface::class;
    }
}
