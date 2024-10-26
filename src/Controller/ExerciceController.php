<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciceController extends AbstractController
{
    #[Route('/exercice/{name}', name: 'app_exercice')]
    public function index($name): Response
    {

        return $this->render('exercice/index.html.twig', [
            'n' => $name,
        ]);
    }

    #[Route('/list', name: 'ListUser')]
    public function ListUser() {
        $user= array(
            array('name'=>'John Doe','email'=>'john@gmail.com' ,'age'=>25,'image'=>''),
            array('name'=>'Amir','email'=>'Amir@gmail.com' ,'age'=>28,'image'=>''),
            array('name'=>'Nour','email'=>'Nour@gmail.com' ,'age'=>22,'image'=>''),
        
        );
        return  $this->render('exercice/user.html.twig' ,
         ['user' => $user]);
    }

    #[Route('/detail/{name}', name: 'd')]
    public function detail($name){
        return $this->render('exercice/detail.html.twig',[
            'n' => $name,
        ]);
    }
}
