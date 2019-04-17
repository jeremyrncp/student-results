<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/students", name="student")
     */
    public function index(StudentRepository $studentRepository)
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
            'students' => $studentRepository->findAll()
        ]);
    }
}
