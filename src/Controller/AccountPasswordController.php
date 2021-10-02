<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $notification = null; // Initialisation de $notification.

        $user = $this->getUser();
        $form =$this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request); // Permet "d'écouter la requête"

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData(); // on récupère l'ancien pwd renseigné dans le champs mot de passe actuel

            // Si les deux mots de passe correspondent alors récupère le nouveau mot de passe
            if ($hasher->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user,$new_pwd);

                $user->setPassword($password); // fonction setPassword() définie dans l'entity User.php
                // Appeler doctrine
                // $this->entityManager->persist($user); Pas obligatoire dans le cadre d'une mise à jour
                $this->entityManager->flush(); // insère le nouveau password dans la base de données
                $notification = "Votre mot de passe a bien été mis à jour.";
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon.";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification'=> $notification
        ]);
    }
}
