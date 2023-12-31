<?php

namespace Ketcau\Log\Processor;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TokenProcessor
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;


    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    public function __invoke(array $records)
    {
        $records['extra']['user_id'] = 'N/A';

        if (null !== $token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();
            $records['extra']['user_id'] = is_object($user)
                ? $user->getId()
                : $user;
        }

        return $records;
    }
}