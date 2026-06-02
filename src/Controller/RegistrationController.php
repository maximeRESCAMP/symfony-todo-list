<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatableInterface;

final class RegistrationController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em,private TranslatableInterface $translatable)
    {
    }
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success',$this->translatable->trans('registration.flash_sucess'));
            return $this->redirectToRoute(route: 'app_task_index', status: 301);
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form,
        ]);
    }
}
