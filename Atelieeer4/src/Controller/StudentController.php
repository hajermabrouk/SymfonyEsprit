<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends AbstractController
{
    #[Route('/list-student', name: 'list_student')]
    public function listStudent(StudentRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('student/list-student.html.twig', [
            'response' => $result
        ]);
    }

    #[Route('/Add-student', name: 'Add_student')]
    public function AddStudent(Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('list_student');
        }

        return $this->render('student/add-student.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Ajouter',
        ]);
    }

    #[Route('/edit-student/{id}', name: 'edit_student')]
    public function editStudent(StudentRepository $repository, $id, Request $request)
    {
        $student = $repository->find($id);

        if (!$student) {
            throw $this->createNotFoundException('Étudiant non trouvé');
        }

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("list_student");
        }

        return $this->render('student/edit-student.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Modifier',
        ]);
    }

    #[Route('/delete-student/{id}', name: 'delete_student')]
    public function deleteStudent($id, StudentRepository $repository)
    {
        $student = $repository->find($id);

        if (!$student) {
            throw $this->createNotFoundException('Étudiant non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute("list_student");
    }
}