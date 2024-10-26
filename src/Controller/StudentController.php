<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/st', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(StudentRepository $repo): Response //injecter le student reppsitorry
    {
        $result= $repo->findAll(); //  la récuperation des données 
        return $this->render('student/list.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add (ClassroomRepository $repo, ManagerRegistry $mr,Request $req) {
    
        $s=new Student();   //  instance de l'objet
      
        $form=$this->createForm(StudentType::class,$s); //  création du formulaire
        $form->handleRequest($req); //  récupération des données du formulaire
        if ($form->isSubmitted()) {
            $em=$mr->getManager(); //  récupération de l'entity manager
            $em->persist($s); //  persister l'objet
            $em->flush(); //  sauvegarder les données
        }  
     

        return $this->render('student/add.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(ManagerRegistry $mr ,StudentRepository $repo ,$id )
    {
       $s=$repo->find($id);
       if($s==null) {
           return new Response ('Student not found');
       }

       $em=$mr->getManager(); 
       $em->remove($s); 
       $em->flush(); 
        
       return $this->redirectToRoute('list'); //  redirection vers la liste

    }

    #[Route('/update/{id}', name: 'update')]
    public function update (ManagerRegistry $mr,StudentRepository $repo, $id) {

        $s=$repo->find($id);
        $s->setName('update'); 
        $s->setAge(27); 

        $em=$mr->getManager(); 
        $em->persist($s);
        $em->flush();

        return $this->redirectToRoute('list'); //  redirection vers la liste
    }

    #[Route('/dql', name: 'dql')]
    public function dql(EntityManagerInterface $em , Request $request, StudentRepository $repo): Response
    {
        $result=$repo->findAll();

      if ($request->isMethod('POST')){
        $value=$request->get('nom');
        $result=$repo->fetchStudentsByName($value);
      }
   
        return $this->render('student/dql.html.twig', [
            'students' => $result,
        ]);
    }
    // calculer le nombres des etudiant qui ont un age > 25
    #[Route('/dql2', name: 'dql2')]
    public function dql2(EntityManagerInterface $em): Response
    {
      $req  =$em->createQuery('select count(s) from App\Entity\Student s where s.age>25 ');
      $result=$req->getResult();
     dd($result);
    }

    // afficher seulement les noms des etudniats 
    #[Route('/dql3', name: 'dql2')]
    public function dql3(EntityManagerInterface $em): Response
    {
      $req  =$em->createQuery('select s.name from App\Entity\Student s  ');
      $result=$req->getResult();
     dd($result);
    }
  // afficher seulement les noms des etudniats 

  #[Route('/dqlJoin', name: 'dqlJoin')]
  public function dqlJoin(EntityManagerInterface $em, StudentRepository $repo): Response
  {

    $result=$repo->fetchStudentAffected();
   dd($result);
  }

  #[Route('/qb', name: 'qb')]
  public function qb( StudentRepository $repo): Response
  {

    $result=$repo->fetchqb();
   dd($result);
  }

}
