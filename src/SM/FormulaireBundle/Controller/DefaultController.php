<?php

namespace SM\FormulaireBundle\Controller;

use SM\FormulaireBundle\Form\MemberType;
use SM\FormulaireBundle\Entity\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
const limit = 5.0;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $member=new Membre();
            $form = $this->createForm(MemberType::class, $member);
            $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $member = $form->getData();
            $validator = $this->get('validator');
            $errors = $validator->validate($member);
            if(count($errors) == 0) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($member);
                $entityManager->flush();
                $this->addFlash(
                    'notice',
                    'Les informations enregistrées avec succés'
                );
            }
        }

        return $this->render('SMFormulaireBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/list/{id}", name="list_element")
     */
    public function listAction($id = null)
    {
        $repository = $this->getDoctrine()->getRepository(Membre::class);
        $members = $repository->findAll();
        $paginator  = $this->get('knp_paginator');
        $blogPosts = $paginator->paginate(
            $members,
            $id /*page number*/,
            limit /*limit per page*/
        );

        $totalRows = count($members);
        $pages = round($totalRows / 5,0, PHP_ROUND_HALF_UP);

        return $this->render('SMFormulaireBundle:Default:list.html.twig',array('members' => $blogPosts, 'pages' => $pages));
    }

    /**
     * @Route("/supp/{id}", name="supp_element")
     * 
     */
    public function suppAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $member = $entityManager->getRepository(Membre::class)->find($id);
        $entityManager->remove($member);
        $entityManager->flush();

        return $this->redirectToRoute('list_element');
    }

    /**
     * @Route("/edit/{id}", name="edit_element")
     */
    public function editAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $member = $entityManager->getRepository(Membre::class)->find($id);

        if (!$member) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $member = $form->getData();
            $validator = $this->get('validator');
            $errors = $validator->validate($member);

            if (count($errors)==0){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($member);
                $entityManager->flush();

                $this->addFlash(
                    'notice',
                    'Les informations enregistrées avec succés'
                );
            }
        }

        return $this->render('SMFormulaireBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
