<?php

namespace AppBundle\Controller;


use AppBundle\Entity\AddressBook;
use AppBundle\Form\AddressBookType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressBookController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request)
    {
        $addressBook = new AddressBook();
        $form        = $this->createForm(AddressBookType::class, $addressBook);

        return $this->render('address_book/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/insert/contact", name="insert.contact")
     */
    public function insert(Request $request, FileUploader $fileUploader)
    {
        $address = new AddressBook();
        $form = $this->createForm(AddressBookType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $fileName = $fileUploader->upload($picture);
                $address->setPicture($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Contact successfully inserted!');
            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('errors', 'Something went wrong!');
        return $this->redirectToRoute('homepage');
    }
}
