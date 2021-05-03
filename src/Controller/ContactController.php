<?php

namespace App\Controller;

use App\Entity\contact;
use App\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(request $request, \Swift_Mailer $mailer): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(Form\ContactType::class);
        $form->add("send", SubmitType::class, [
            'attr' => ['class' => 'btn btn-info'],
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $contact =$form->getData();
            $message = (new \Swift_Message('Nouveau contact'))
                ->setFrom("podtastyy@gmail.com")
                ->setTo('podtastyy@gmail.com')
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',compact('contact')
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('message','the message has been sent');
            return $this->redirectToRoute('Home');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),'user'=>$user
        ]);
    }
}