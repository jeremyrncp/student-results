<?php

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class CreateStudentFormHandler implements FormHandlerInterface
{
    private $entityManager;

    /**
     * CreateStudentFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($form->getData());
            return true;
        }

        return false;
    }
}
