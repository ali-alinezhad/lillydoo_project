<?php

namespace AppBundle\Controller;


use AppBundle\Entity\AddressBook;
use AppBundle\Form\AddressBookType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressBookController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param Request      $request
     * @param FileUploader $fileUploader
     *
     * @return RedirectResponse|Response|null
     */
    public function index(Request $request, FileUploader $fileUploader)
    {
        $addressBook = new AddressBook();
        $form        = $this->createForm(AddressBookType::class, $addressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UploadedFile $picture */
                $picture = $form->get('picture')->getData();

                if ($picture) {
                    $fileName = $fileUploader->upload($picture);
                    $addressBook->setPicture($fileName);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($addressBook);
                $entityManager->flush();

                $this->addFlash('success', 'Contact successfully inserted!');
            }
            else {
                $this->addFlash('errors', 'Something Went wrong!');
            }
            return $this->redirectToRoute('homepage');
        }

        $contacts = $this->getDoctrine()
            ->getRepository(AddressBook::class)
            ->findAll();

        return $this->render('address_book/index.html.twig', [
            'form'     => $form->createView(),
            'contacts' => $contacts,
        ]);
    }


    /**
     * @Route("/edit/{contactId}/contact/", name="edit.contact")
     *
     * @param Request      $request
     * @param FileUploader $fileUploader
     * @param int          $contactId
     *
     * @return RedirectResponse|Response|null
     */
    public function update(Request $request, FileUploader $fileUploader, int $contactId)
    {
        $contact = $this->getDoctrine()
            ->getRepository(AddressBook::class)
            ->find($contactId);

        if (empty($contact)) {
            $this->addFlash('errors', 'Record does not found!');
            return $this->redirectToRoute('homepage');
        }

        $form    = $this->createForm(AddressBookType::class, $contact);
        $oldFile = $contact->getPicture();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            if ($picture) {
                if ($oldFile) {
                    $fileUploader->remove($oldFile);
                }

                $fileName = $fileUploader->upload($picture);
                $contact->setPicture($fileName);
            }
            else {
                $contact->setPicture($oldFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Record successfully updated!');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('address_book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete/{contactId}/contact", name="delete.contact")
     *
     * @param FileUploader $fileUploader
     * @param int          $contactId
     *
     * @return RedirectResponse
     */
    public function delete(FileUploader $fileUploader, int $contactId): RedirectResponse
    {
        $contact = $this->getDoctrine()
            ->getRepository(AddressBook::class)
            ->find($contactId);

        if (!empty($contact)) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
            $picture = $contact->getPicture();

            if (!empty($picture)) {
                $fileUploader->remove($picture);
            }
            $this->addFlash('success', 'Record successfully removed!');

            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('errors', 'Something went wrong!');

        return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/show/contact/{contactId}/details", name="show.contact.details")
     *
     * @param Request $request
     * @param int     $contactId
     *
     * @return Response|null
     */
    public function showDetails(Request $request, int $contactId): ?Response
    {
        $contact = $this->getDoctrine()
            ->getRepository(AddressBook::class)
            ->find($contactId);

        return $this->render('address_book/details.html.twig', [
            'contact' => $contact
        ]);
    }
}
