<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class UserManager
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }
}