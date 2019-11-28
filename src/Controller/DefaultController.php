<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [

        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about (Request $request, \Swift_Mailer $mailer)
    {


        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        $this->addFlash('info', 'Some useful info');

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();


            $message = (new \Swift_Message('You Got Mail!'))
                ->setFrom($contactFormData['email'])
                ->setTo('r.vandegraaf45@gmail.com')
                ->setBody(
                    $contactFormData['message'],
                    'text/plain');


            $mailer->send($message);

            $this->addFlash('success', 'It sent!');

            return $this->redirectToRoute('about');
        }

        return $this->render('default/about.html.twig', [
            'our_form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/fillsurvey/{id}", name="fillsurvey")
     */
     public function fillsurvey($id)
     {

         if (isset($_POST['volgende'])){
             $id = $id + 1;
         }

         if (isset($_POST['vorige'])){
             $id = $id - 1;
         }

         $yeehaw = $this->getDoctrine()->getRepository(Question::class)->find(['id' => $id]);


         return $this->render('default/fillsurvey.html.twig', [
            'question' => $yeehaw,
             'id' => $id
         ]);
     }
}
