<?php

namespace Ruwork\ApiClientTools\RequestFactory;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface RequestFactoryInterface
{
    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function createRequest(array $options);
}
