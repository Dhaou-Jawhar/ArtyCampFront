<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository,SluggerInterface $slugger,UserPasswordEncoderInterface $encoder, MailerInterface $mailer,ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$managerRegistry->getManager();
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $photo = $form->get('image')->getData();


            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
               try {
                    $photo->move(
                        $this->getParameter('personne_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the image name
                // instead of its contents
                $user->setImage($newFilename);
            }

/*
            $message='Welcome to ArtyCamp Application';
            $mailMessage = $user->getPrenom().' '.$user->getNom().' '.$message;
            $mailerService->sendEmail($mailMessage);
            $userRepository->save($user, true);*/

            $html= $this->renderView('registration/email.html.twig');
            $email = (new Email())
                ->from('articamp4se5@gmail.com')
                ->to($user->getMail())
                ->subject('Time for Symfony Mailer!')
                ->html($html);

            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')
            //->html($content);

            $mailer->send($email);

            // ...



            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,UserPasswordHasherInterface $userPasswordHasher, User $user, UserRepository $userRepository,ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$managerRegistry->getManager();
            $userRepository->save($user, true);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_hello', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('hello', name: 'app_hello')]
    public function hello()
    {
        return $this->render('user/hello.html.twig');
    }
    #[Route('/admin/activate/{id}', name: 'user_activate')]
    function Activate(Request $request,UserRepository $repository,ManagerRegistry $managerRegistry,$id)
    {
        $user = $repository->find($id);
        if(true === $user->isEnabled()) {
            $user->setEnabled(false);
        } else {
            $user->setEnabled(true);
        }
        $em=$managerRegistry->getManager();
        //$em->persist($classroom);
        $em->flush();
        return $this->redirectToRoute('app_user_index');
    }
}
