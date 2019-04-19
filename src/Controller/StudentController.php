<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Student;
use App\Enum\FlashTypeEnum;
use App\Form\AssessmentType;
use App\Form\Handler\CreateStudentFormHandler;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{

    /**
     * @Route("/student/{student}", name="student_card", requirements={"student"="\d+"}))
     *
     *
     * @param Student $student
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function view(Student $student, EntityManagerInterface $entityManager, Request $request): Response
    {
        $assessment = new Assessment();
        $assessment->setStudent($student);

        $assessmentType = $this->createForm(AssessmentType::class, $assessment);
        $assessmentType->handleRequest($request);

        if ($assessmentType->isSubmitted() && $assessmentType->isValid()) {
            $student->addAssessment($assessment);
            $entityManager->persist($student);
            $entityManager->flush();

            $this->addFlash(FlashTypeEnum::SUCCESS,
                sprintf('Assessment added to %s %s', $student->getFirstName(), $student->getLastName())
            );
        }

        return $this->render('student/view.html.twig', [
            'student' => $student,
            'form' => $assessmentType->createView()
        ]);
    }

    /**
     * @Route("/student", name="student")
     *
     * @param StudentRepository $studentRepository
     * @return Response
     */
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll()
        ]);
    }

    /**
     * @Route("/student/add", name="student_add")
     *
     * @param CreateStudentFormHandler $studentFormHandler
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function add(CreateStudentFormHandler $studentFormHandler, EntityManagerInterface $entityManager, Request $request): Response
    {
        $studentType = $this->createForm(StudentType::class);
        $studentType->handleRequest($request);

        if ($studentFormHandler->handle($studentType)) {
            $this->addFlash(FlashTypeEnum::SUCCESS, "Your student was successfull created");
            $entityManager->flush();
            return $this->redirectToRoute('student');
        } else  {
            return $this->render('student/add.html.twig', [
                'form' => $studentType->createView()
            ]);
        }
    }


    /**
     * @Route("/student/{student}/edit", name="student_edit", requirements={"student"="\d+"})
     *
     * @param Student $student
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function edit(Student $student, EntityManagerInterface $entityManager, Request $request): Response
    {
        $studentType = $this->createForm(StudentType::class, $student);
        $studentType->handleRequest($request);

        if ($studentType->isSubmitted() && $studentType->isValid()) {
            $this->addFlash(FlashTypeEnum::SUCCESS,
                sprintf('Student %s %s was updated', $student->getFirstName(), $student->getLastName())
            );
            $entityManager->flush();
            return $this->redirectToRoute("student");
        } else {
            return $this->render('student/edit.html.twig', [
                'form' => $studentType->createView()
            ]);
        }
    }

    /**
     * @Route("/student/{student}/delete", name="student_delete", requirements={"student"="\d+"})
     *
     * @param Student $student
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function delete(Student $student, EntityManagerInterface $entityManager, Request $request): Response
    {
        $entityManager->remove($student);
        $this->addFlash(FlashTypeEnum::SUCCESS,
            sprintf('Student %s %s deleted', $student->getFirstName(), $student->getLastName())
        );
        $entityManager->flush();
        return $this->redirectToRoute("student");
    }
}
