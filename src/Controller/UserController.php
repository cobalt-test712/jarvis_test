<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


use App\Entity\User;
use App\Service\SaveUser;
use App\Service\ConverterUser;

/**
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    /**
     *  Creation d'un utilisateur
     *
     * @Route("/create", name="create_user", methods="POST")
     *
     * @param Request $request 
     * @param SerializerInterface $serializer service des serialisation d'objet
     * @param SaveUser $save     
     *
     * @return JsonResponse
     */
    public function createUser(
        Request $request, 
        SerializerInterface $serializer,
        SaveUser $save)
    {   
        if(empty($request->getContent()))
            throw new NotFoundHttpException('',null,Response::HTTP_NOT_FOUND);
        $user = $serializer->deserialize($request->getContent(),User::class,'json');
        $retour = $save->save($user);
        return $this->json($retour[0],$retour["status"]);
    }
    
    /**
     * Liste  des utilisateurs 
     *
     * @Route("/", name="liste_users", methods="GET")

     * @return JsonResponse
     */
    public function listerUser(): JsonResponse
    {   
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(User::class)->findAll();
        if(empty($data)) 
            throw new NotFoundHttpException('',null,Response::HTTP_NOT_FOUND);
        return  $this->json($data); 
    }

    /**
     * Affichage d'un utilisateur
     *
     * @Route("/{id}", name="show_user", methods="GET")
     *
     * @param User $user.
     *
     * @return JsonResponse
     */
    public function showUser(User $user): JsonResponse
    {    
        return $this->json($user);
    }
     
    /**
     * suppression d'un utilisateur
     *
     * @Route("/delete/{id}", name="delete_user", methods="DELETE")
     *
     * @param User $user.
     *
     * @return JsonResponse
     */
    public function deleteUser(User $user): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new JsonResponse(
            ["message"=>"Un utilisateur a été supprimé " ],
            Response::HTTP_CREATED
        ); 
    }


    /**
     * Mise à jour nom/prenom de l'utilisateur x à partir de données envoyées
     *
     * @Route("/update/{id}", name="update_user", methods="PUT")
     *
     * @param Request $request      
     * @param User $user
     * @param SerializerInterface $serializer service des serialisation d'objet
     * @param UserConverter $converter
     * @param SaveUser $save
     *
     * @return JsonResponse
     */
    public function updateUser(
        Request $request,
        User $user,
        SerializerInterface $serializer,
        ConverterUser $converter,
        SaveUser $save
    ): JsonResponse
    {   
        if(empty($request->getContent()))
            throw new NotFoundHttpException('',null,Response::HTTP_NOT_FOUND);
        $data = $serializer->deserialize($request->getContent(),User::class,'json');
        $convert = $converter->convert($data,$user);
        if (! empty($convert)) {
             $retour = $save->save($convert,true);
             return $this->json($retour[0],$retour["status"]);
        }
        return $this->json(['message' => 'rien a modifier',]);
    }
}
