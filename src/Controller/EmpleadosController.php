<?php

namespace App\Controller;

use App\Entity\Salaries;
use App\Form\FormularieType;
use App\Repository\SalariesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmpleadosController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(SalariesRepository $repo): Response
    {   
        $salaries=$repo->findAll();
        return $this->render('empleados/index.html.twig', [
            'salaries'=> $salaries
        ]);
    }
    #[Route('/salaries/edit/{id}', name: 'edit')]
    #[Route('/salaries/add', name: 'salaries_add')]
    public function add(Request $globals, EntityManagerInterface $manager, Salaries $salaries= null): Response
    {   
        if($salaries == null)
        {
            $salaries = new Salaries;
        }
        $form = $this->createForm(FormularieType::class, $salaries);
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($salaries);
            $manager->flush();
            return $this->redirectToRoute('accueil');
        }


        return $this->renderForm('empleados/form.html.twig',
        [
            'formSalaries' => $form,
            "editMode" => $salaries->getId() !== null
        ]);
    }
    #[Route("/salaries/delete/{id}", name:"delete")]
    public function delete($id, EntityManagerInterface $manager, SalariesRepository $repo)
    {
        $salaries= $repo->find($id);

        $manager->remove($salaries);
        $manager->flush();
        return $this->redirectToRoute('accueil');
    }


}
