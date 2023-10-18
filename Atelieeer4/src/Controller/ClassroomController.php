<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ClassroomType;
use Symfony\Component\HttpFoundation\Request;


class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(ClassroomRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('Classroom/list.html.twig', [
            'response' => $result
        ]);
    }

   
    #[Route('/Add', name: 'Add')]
    public function Add(Request $request): Response
{
    $classroom = new Classroom();
    $form = $this->createForm(ClassroomType::class, $classroom);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($classroom);
        $em->flush();

        return $this->redirectToRoute('list');
    }

    return $this->render('classroom/Add.html.twig', [
        'form' => $form->createView(),
        'button_label' => 'Ajouter', 
    ]);
}
#[Route('/edit/{id}', name: 'edit')]
public function edit(ClassroomRepository $repository, $id, Request $request)
{
    $classroom = $repository->find($id);

    if (!$classroom) {
        throw $this->createNotFoundException('Classe non trouvée');
    }

    $form = $this->createForm(ClassroomType::class, $classroom);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute("list");
    }

    return $this->render('classroom/edit.html.twig', [
        'form' => $form->createView(),
        'button_label' => 'Modifier',
    ]);
}




#[Route('/delete/{id}', name: 'delete')]
public function delete($id, ClassroomRepository $repository)
{
    
    $classroom = $repository->find($id);
    if (!$classroom) {
   
    }

   
    $em = $this->getDoctrine()->getManager();
    $em->remove($classroom);
    $em->flush();
    return $this->redirectToRoute("list");
}

}