<?php

namespace Fastre\PhpFtpAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Fastre\PhpFtpAdminBundle\Form\AccountType;
use Fastre\PhpFtpAdminBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller for accounts creation and deletion
 *
 * @author Julien Fastré <julien@fastre.info>
 */
class AccountsController  extends Controller {
    
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        
        $accounts = $em->getRepository('FastrePhpFtpAdminBundle:Account')
                ->findAll();

        
        return $this->render('FastrePhpFtpAdminBundle:Accounts:list.html.twig', 
                array(
                    'accounts' => $accounts
                )
                );

    }
    
    public function newAction() {
        $form = $this->createForm(new AccountType('new'), new Account());
        
        return $this->render('FastrePhpFtpAdminBundle:Accounts:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'action_path' => $this->generateUrl('account_create')
                ));
    }
    
    public function createAction(Request $request) {
        $account = new Account();
        $form = $this->createForm(new AccountType('new'), $account);
        
        $form->bindRequest($request);
        
        
        
        if ($request->getMethod() === 'POST') {
            
            $account = $form->getData();
            
            $errors = $this->get('validator')
                    ->validate($account, array('Default', 'registration'));
            
            
            
            if ($errors->count() == 0) {

                $em = $this->getDoctrine()->getManager();

                $em->persist($account);

                $em->flush();
                
                //TODO: i18n
                $this->get('session')
                        ->getFlashBag()
                        ->add('notice', 'Compte '.$account->getUsername().' créé');                                         
            } else {
                
                foreach($errors as $error) {
                    $this->get('session')
                            ->getFlashBag()
                            ->add('warning', $error->getMessage());
                }
                
                return $this->render('FastrePhpFtpAdminBundle:Accounts:form.html.twig',
                    array(
                        'form' => $form->createView(),
                        'action_path' => $this->generateUrl('account_create')
                    ));
            }
        }
        
        return $this->redirect($this->generateUrl('account_list'));
        
    }
    
    public function viewAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $account = $em->getRepository('FastrePhpFtpAdminBundle:Account')->find($id);
        
        if ($account === null) {
            throw $this->createNotFoundException("Compte non trouvé");
        }
        
        $form = $this->createForm(new AccountType(), $account);
        
        if ($request->getMethod() === 'POST') {
            
            $form->bind($request);
  
            $data = $form->getData();
            
            
            
            $errors = $this->get('validator')->validate($data, array('Default'));
            
            //throw new \Exception($errors->count());
            
            if ($errors->count() == 0) {
                $em->flush();
                
                //TODO: i18n
                $this->get('session')
                        ->getFlashBag()
                        ->add('notice', 'Compte '.$account->getUsername().' modifié');
                
                return $this->redirect($this->generateUrl('account_list'));
                
            } else {
                
                
                foreach ($errors as $error) {
                    $this->get('session')
                        ->getFlashBag()
                        ->add('warning', $error->getMessage()); 
                }
                
                return $this->render('FastrePhpFtpAdminBundle:Accounts:form.html.twig',
                    array(
                        'form' => $form->createView(),
                        'action_path' => $this->generateUrl('account_view', array('id' => $id))
                        )
                    );
            }

        }
        

        return $this->render('FastrePhpFtpAdminBundle:Accounts:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'action_path' => $this->generateUrl('account_view', array('id' => $id))
                ));
        
    }
    
}

