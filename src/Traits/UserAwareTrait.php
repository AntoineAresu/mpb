<?php

namespace App\Traits;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;

trait UserAwareTrait
{
    private Security $security;

    protected function getUser(): ?User
    {
        $user = $this->security->getUser();

        return $user instanceof User ? $user : null;
    }
}
