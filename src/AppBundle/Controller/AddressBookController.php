<?php

namespace AppBundle\Controller;


use AppBundle\Entity\AddressBook;
use AppBundle\Form\AddressBookType;
use AppBundle\Repository\AddressBookRepository;
use AppBundle\Service\FileUploader;
use AppBundle\Service\CommonHelper;
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
     * @param Request               $request
     * @param FileUploader          $fileUploader
     * @param AddressBookRepository $addressBookRepository
     * @param CommonHelper          $helper
     *
     * @return RedirectResponse|Response|null
     */
    public function index(
        Request               $request,
        FileUploader          $fileUploader,
        AddressBookRepository $addressBookRepository,
        CommonHelper          $helper
    )
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

        return $this->render('address_book/index.html.twig', [
            'maxId'    => $helper->getLastContactId(),
            'form'     => $form->createView(),
            'contacts' => $addressBookRepository->findAll(),
        ]);
    }


    /**
     * @Route("/edit/{contactId}/contact", name="edit.contact")
     *
     * @param Request               $request
     * @param FileUploader          $fileUploader
     * @param AddressBookRepository $addressBookRepository
     * @param int                   $contactId
     *
     * @return RedirectResponse|Response|null
     */
    public function update(
        Request               $request,
        FileUploader          $fileUploader,
        AddressBookRepository $addressBookRepository,
        int                   $contactId
    )
    {
        $contact = $addressBookRepository->find($contactId);

        if (empty($contact)) {
            $this->addFlash('errors', 'Record does not found!');
            return $this->redirectToRoute('homepage');
        }

        $form    = $this->createForm(AddressBookType::class, $contact);
        $oldFile = $contact->getPicture();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
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
            }
            else {
                $this->addFlash('errors', 'Something Went wrong!');
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('address_book/edit.html.twig', [
            'form'      => $form->createView(),
            'contactId' => $contactId
        ]);
    }


    /**
     * @Route("/delete/{contactId}/contact", name="delete.contact")
     *
     * @param FileUploader          $fileUploader
     * @param AddressBookRepository $addressBookRepository
     * @param int                   $contactId
     *
     * @return RedirectResponse
     */
    public function delete(
        FileUploader          $fileUploader,
        AddressBookRepository $addressBookRepository,
        int                   $contactId
    ): RedirectResponse
    {
        $contact = $addressBookRepository->find($contactId);

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
     * @param AddressBookRepository $addressBookRepository
     * @param int                   $contactId
     *
     * @return Response|null
     */
    public function showDetails(
        AddressBookRepository $addressBookRepository,
        int                   $contactId
    ): ?Response
    {
        $contact = $addressBookRepository->find($contactId);

        return $this->render('address_book/details.html.twig', [
            'contact' => $contact
        ]);
    }


    /**
     * @Route("/delete/{contactId}/image", name="delete.image")
     *
     * @param FileUploader          $fileUploader
     * @param AddressBookRepository $addressBookRepository
     * @param int                   $contactId
     *
     * @return RedirectResponse
     */
    public function deleteImage(
        FileUploader          $fileUploader,
        AddressBookRepository $addressBookRepository,
        int                   $contactId
    ): RedirectResponse
    {
        $contact = $addressBookRepository->find($contactId);

        if (!empty($contact)) {
            $picture = $contact->getPicture();

            if (!empty($picture)) {
                $fileUploader->remove($picture);
                $contact->setPicture('');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Record successfully removed!');

            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('errors', 'Something went wrong!');

        return $this->redirectToRoute('homepage');
    }
}
