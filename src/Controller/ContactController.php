<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller
 *
 * @Route(path="/contact")
 */
class ContactController{

    private $contactRepository;

    public function __construct(ContactRepository $contactRepository) {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/list", name="contact_list", methods={"GET"})
    */
    public function list(){
        $contacts = $this->contactRepository->findAll();
        $data = array();
        foreach($contacts as $row){
            $data[] = array(
                "id"=> $row->getId(),
                "name"=> $row->getName(),
                "email"=> $row->getEmail(),
                "phone"=> $row->getPhone(),
                "website"=> $row->getWebsite(),
                "address"=> $row->getAddress(),
                "created_at"=> $row->getCreatedAt(),
                "updated_at"=> $row->getUpdatedAt(),
                "deleted_at"=> $row->getDeletedAt()
            );
        }

        return new JsonResponse(array(
            "status"=> true,
            "message"=> "Data has been fetched !!",
            "data"=> $data,
        ), Response::HTTP_OK);
    }

    /**
     * @Route("/create", name="contact_create", methods={"POST"})
    */
    public function create(Request $request) : JsonResponse{

        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $website = $data['website'];
        $address = $data['address'];

        if (empty($name) || empty($email)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $data = $this->contactRepository->saveContact($name, $email, $phone, $website, $address);

        return new JsonResponse(array(
            "status"=> true,
            "message"=> "Data has been created !!",
            "data"=> $data,
        ), Response::HTTP_OK);

    }

    /**
     * @Route("/detail/{id}", name="contact_detail", methods={"GET"})
     */
    public function detail($id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        if(is_null($contact)){
            return new JsonResponse(array(
                "status"=> false,
                "message"=> "Data not found !!",
                "data"=>  null,
            ), Response::HTTP_OK);
        }

        $data = [
            "id"=> $contact->getId(),
            "name"=> $contact->getName(),
            "email"=> $contact->getEmail(),
            "phone"=> $contact->getPhone(),
            "website"=> $contact->getWebsite(),
            "address"=> $contact->getAddress(),
            "created_at"=> $contact->getCreatedAt(),
            "updated_at"=> $contact->getUpdatedAt(),
            "deleted_at"=> $contact->getDeletedAt()
        ];
        return new JsonResponse(array(
            "status"=> true,
            "message"=> "Data has been created !!",
            "data"=> $data,
        ), Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="contact_update", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);
        if(is_null($contact)){
            return new JsonResponse(array(
                "status"=> false,
                "message"=> "Data not found !!",
                "data"=>  null,
            ), Response::HTTP_OK);
        }

        $data = json_decode($request->getContent(), true);
        $dataUpdated = $this->contactRepository->updateContact($contact, $data);

        return new JsonResponse(array(
            "status"=> true,
            "message"=> "Data has been updated !!",
            "data"=> $dataUpdated,
        ), Response::HTTP_OK);
        
    }

     /**
     * @Route("/delete/{id}", name="contact_delete", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);
        if(is_null($contact)){
            return new JsonResponse(array(
                "status"=> false,
                "message"=> "Data not found !!",
                "data"=>  null,
            ), Response::HTTP_OK);
        }

        $this->contactRepository->removeContact($contact);
        return new JsonResponse(array(
            "status"=> true,
            "message"=> "Data has been deleted !!",
            "data"=> null,
        ), Response::HTTP_OK);
    }

}