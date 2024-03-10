<?php

namespace App\Controller;

use App\Form\PersonalInfoType;
use App\Form\UserChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/user', name: 'app_user')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $formPersonalInfo = $this->createForm(PersonalInfoType::class, $user);
        $formPassword = $this->createForm(UserChangePasswordType::class, $user);

        $formPersonalInfo->handleRequest($request);
        $formPassword->handleRequest($request);

        if ($formPersonalInfo->isSubmitted() && $formPersonalInfo->isValid()) {
            // Save the updated personal info
            $this->entityManager->flush();
            // Redirect to the profile page
            return $this->redirectToRoute('app_user');
        }

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            // Hash the new password
            $newPassword = $formPassword->get('password')->get('first')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            // Save the updated password
            $this->entityManager->flush();
            // Redirect to the profile page
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/index.html.twig', [
            'form_personal_info' => $formPersonalInfo->createView(),
            'form_password' => $formPassword->createView(),
        ]);
    }
}