<?php


namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Customer;
use OpenApi\Attributes as OA;


/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AbstractController
{
    #[Route('/customers/{idCustomer}/contacts', name: 'api_contacts_item_post', requirements: ['idCustomer' => '\d+'], methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Contact created successfully'
    )]
    #[OA\Response(
        response: 400,
        description: 'A violation exception is raised by validation'
    )]
    #[OA\Parameter(
        name: 'idCustomer',
        description: 'The customer id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\RequestBody(required: 'true', content:
        [new OA\MediaType(mediaType: 'application/json',
            schema: new OA\Schema(properties: [
                        new OA\Property( property: "firstName", description: "the first name of the contact", type: "string"),
                        new OA\Property( property: "lastName", description: "the last name of the contact", type: "string"),
                        new OA\Property( property: "street", description: "the street where the contact lives", type: "string"),
                        new OA\Property( property: "zip", description: "the zip code", type: "integer"),
                        new OA\Property( property: "city", description: "the city", type: "string"),
                        new OA\Property( property: "country", description: "the country", type: "string"),
                        new OA\Property( property: "phoneNumber", description: "the number phone of the contact", type: "string"),
                        new OA\Property( property: "birthday", description: "the birthday of the contact", type: "date"),
                        new OA\Property( property: "email", description: "the email of the contact", type: "string"),
                        new OA\Property( property: "picture", description: "the picture of the contact in base64 format", type: "string"),
                    ],
                    example:
                    '{
                        "addedAt": "2022-09-10 19:53:34",
                        "firstName": "Jack",
                        "lastName": "Taureau",
                        "street": "Kaiseralle 39D",
                        "zip": "76139",
                        "city": "Berlin",
                        "country": "Germany",
                        "phoneNumber": "+06778273293",
                        "birthday": "1901-09-01",
                        "email": "email@mail.com",
                        "picture": "4RiDRXhpZgAATU0AKgA"
                    }'
            ))
        ])
    ]
    public function postItem(
                         int $idCustomer,
                         Contact $contact,
                         EntityManagerInterface $entityManager,
                         SerializerInterface $serializer,
                         UrlGeneratorInterface $urlGenerator,
                         ): JsonResponse
    {
        $contact->setContactOf($entityManager->getRepository(Customer::class)->findOneBy(['id' => $idCustomer]));
        $entityManager->getRepository(Contact::class)->add($contact, true);
        return new JsonResponse(
            $serializer->serialize([], "json", ["groups" => "get"]),
            Response::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_contacts_item_get", ["idCustomer" => $idCustomer, 'id' => $contact->getId()])],
            true
        );
    }

    #[Route('/customers/{idCustomer}/contacts/{id}', name: 'api_contacts_item_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Parameter(
        name: 'idCustomer',
        description: 'The customer id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'The contact id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Response(
        response: 200,
        description: 'Contact returned successfully',
    )]
    #[OA\Response(
        response: 404,
        description: 'Contact not found',
    )]
    public function getItem(SerializerInterface $serializer, Contact $contact = null) :JsonResponse
    {
        if ($contact === null) {
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
                [],
            );
        }
        return new JsonResponse(
            $serializer->serialize($contact, "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[OA\Parameter(
        name: 'idCustomer',
        description: 'The customer id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'The contact id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Response(
        response: 204,
        description: 'Contact was deleted',
    )]
    #[OA\Response(
        response: 404,
        description: 'Contact not found',
    )]
    #[Route('/customers/{idCustomer}/contacts/{id}', name: 'api_contacts_item_delete', methods: ['DELETE'])]
    public function deleteItem(EntityManagerInterface $entityManager, Contact $contact = null) :JsonResponse
    {
        if ($contact === null) {
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
                []);
        }
        $entityManager->getRepository(Contact::class)->remove($contact, true);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }

    #[Route('/customers/{idCustomer}/contacts/{name}', name: 'api_contacts_collection_get', methods: ['GET'])]
    #[Route('/customers/{idCustomer}/contacts/{id}', name: 'api_contacts_item_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Parameter(
        name: 'idCustomer',
        description: 'The customer id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'The contact firstname or lastname or part of them ',
        in: 'path',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Contacts returned successfully',
    )]
    #[OA\Response(
        response: 404,
        description: 'No contact found',
    )]
    public function getCollection(int $idCustomer,
                                  string $name,
                                  EntityManagerInterface $entityManager,
                                  SerializerInterface $serializer) :JsonResponse
    {
        $result = $entityManager->getRepository(Contact::class)->findByName($idCustomer, $name);
        if (count($result) === 0) {
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
                [],
            );
        }
        return new JsonResponse(
            $serializer->serialize( $result, "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/customers/{idCustomer}/contacts/{id}', name: 'api_contacts_item_put', requirements: ['idCustomer' => '\d+', 'id' => '\d+'],  methods: ['PUT'])]
    #[OA\Response(
        response: 204,
        description: 'Contact updated successfully'
    )]
    #[OA\Response(
        response: 404,
        description: 'Contact not found'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized action'
    )]
    #[OA\Parameter(
        name: 'idCustomer',
        description: 'The customer id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'The contact id',
        in: 'path',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\RequestBody(required: 'true', content:
        [new OA\MediaType(mediaType: 'application/json',
            schema: new OA\Schema(properties: [
                new OA\Property( property: "id", description: "the id of the contact", type: "integer"),
                new OA\Property( property: "firstName", description: "the first name of the contact", type: "string"),
                new OA\Property( property: "lastName", description: "the last name of the contact", type: "string"),
                new OA\Property( property: "street", description: "the street where the contact lives", type: "string"),
                new OA\Property( property: "zip", description: "the zip code", type: "integer"),
                new OA\Property( property: "city", description: "the city", type: "string"),
                new OA\Property( property: "country", description: "the country", type: "string"),
                new OA\Property( property: "phoneNumber", description: "the number phone of the contact", type: "string"),
                new OA\Property( property: "birthday", description: "the birthday of the contact", type: "date"),
                new OA\Property( property: "email", description: "the email of the contact", type: "string"),
                new OA\Property( property: "picture", description: "the picture of the contact in base64 format", type: "string"),
                new OA\Property( property: "contactOf", description: "the id of the customer", type: "integer"),
            ],
                example:
                    '{
                        "id": 1,
                        "addedAt": "2022-09-10 19:53:34",
                        "firstName": "Jack",
                        "lastName": "Taureau",
                        "street": "Kaiseralle 39D",
                        "zip": 76139,
                        "city": "Berlin",
                        "country": "Germany",
                        "phoneNumber": "+06778273293",
                        "birthday": "1901-09-01",
                        "email": "email@mail.com",
                        "picture": "4RiDRXhpZgAATU0AKgA",
                        "contactOf": 1
                    }'
            ))
        ])
    ]
    public function putItem(int $idCustomer, EntityManagerInterface $entityManager, Contact $contact = null) :JsonResponse
    {
        if ($contact === null) {
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
                []
            );
        }

        if ($contact->getContactOf()->getId() !== $idCustomer) {
            return new JsonResponse(
                [],
                Response::HTTP_UNAUTHORIZED,
                [],
            );
        }

        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}