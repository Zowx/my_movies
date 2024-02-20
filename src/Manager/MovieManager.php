<?php

namespace App\Manager;

class Movie
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function new($form)
    {
        $data = $form->getData();
        dd($data);
    }
}