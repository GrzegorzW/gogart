<?php

declare(strict_types = 1);

namespace Gogart\Http\Request\ParamConverter;

use Gogart\Http\Request\Exception\RequestNotValid;
use JMS\Serializer\Exception\Exception as JMSSerializerException;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestParamConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     *
     * @return bool
     *
     * @throws RequestNotValid
     * @throws BadRequestHttpException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $object = $this->deserialize($request, $configuration);

        if ($object !== true) {
            $this->validate($object);

            $request->attributes->set($configuration->getName(), $object);
        }

        return true;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     *
     * @return array|bool||object
     *
     * @throws BadRequestHttpException
     */
    private function deserialize(Request $request, ParamConverter $configuration)
    {
        try {
            $object = $this->serializer->deserialize(
                json_encode(array_merge($request->request->all(), $request->query->all()), JSON_HEX_QUOT),
                $configuration->getClass(),
                'json'
            );
        } catch (JMSSerializerException $e) {
            if ($configuration->isOptional() === true) {
                return true;
            }

            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $object;
    }

    /**
     * @param $object
     *
     * @throws RequestNotValid
     */
    private function validate($object): void
    {
        $validationErrors = $this->validator->validate($object);

        if ($validationErrors->count()) {
            throw new RequestNotValid($validationErrors);
        }
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() !== null;
    }
}
