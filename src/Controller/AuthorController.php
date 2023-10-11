<?php

namespace App\Controller;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/show/{name}', name: 'app_auth')]
    
    public function showauthor ($name)
    {   
        return $this->render('author/show.html.twig',[
        'name' => $name]);
    }
    #[Route('/list', name: 'app_auth')]
    public function list()
    {
        $authors = array(
            array('id' => 1, 'picture' => 'images/victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );

            return $this->render('author/list.html.twig', [
                'name' => $authors]);
    }
    #[Route('/showall', name: 'app_autho')]
    public function showall(AuthorRepository $repo){
        $list=$repo->findall();
        return $this->render('author/showall.html.twig',['Authors' => $list]);
    }
    #[Route('/addstatic', name: 'addstaticc')]
    public function addstatic(EntityManagerInterface $repo) {
        $auth=new Author();
        $auth->setUsername("esgty");
        $auth->setEmail("example@gmail.com");
        $repo->persist($auth);
        $repo->flush();
        return $this->redirectToRoute('app_autho');
    }
    #[Route('/add', name: 'add')]
    public function add(Request $request)
    {
        $auth=new Author();
        $form=$this->CreateForm(AuthorType::class,$auth);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($auth);
            $em->flush();
            return $this->render('author/add.html.twig',['f' => $form->createView()]);
        }
        return $this->render('author/add.html.twig',['f' => $form->createView()]);
    }
    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(AuthorRepository $repository, $id, Request $request)
    {
        $author = $repository->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); 
            return $this->redirectToRoute('app_autho');
        }

        return $this->render('author/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, AuthorRepository $repository)
    {
        $author = $repository->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('app_autho');
    }

    #[Route('/showa/{id}', name: 'app_authert')]
    
    public function showa ($id,AuthorRepository $repo)
    {   $n=$repo->find($id);
        return $this->render('author/show.html.twig',[
        'name' => $n]);
    }
}
