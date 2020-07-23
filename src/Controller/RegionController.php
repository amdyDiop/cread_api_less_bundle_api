<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/regions")
 */
class RegionController extends AbstractController
{
    /**
     * @Route("/", name="liste_region", methods={"GET"})
     */
    public function listeRegions(RegionRepository $regionRepository, SerializerInterface $serializer)
    {
        $regionsObject =$regionRepository->findAll();
        $regionsJson =$serializer->serialize(
            $regionsObject,
            "json",
            [
                "groups"=>["region:read"]
            ]
        );

        return new JsonResponse($regionsJson,Response::HTTP_OK,[],true);    }

    /**
     * @Route("/new", name="region_new", methods={"POST"})
     */
    public function addRegion(Request $request, SerializerInterface $serializer,ValidatorInterface $validation)
    {
        $region = $request->getContent();
        $regionJson=$serializer->deserialize($region,Region::class,"json" );

        $errors = $validation->validate($regionJson);
        if (count($errors) > 0) {
            $errorsString =$serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($regionJson);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route("/{id}", name="region_show", methods={"GET"})
     */
    public function show(Region $region, SerializerInterface $serializer): JsonResponse
    {

        $regionObjet = $serializer->serialize($region,"json", ["groups"=>["region:read"]]);
        return new JsonResponse($regionObjet,Response::HTTP_OK,[true]);
    }

    /**
     * @Route("/{id}/edit", name="region_edit", methods={"POST"})
     */
    public function edit(Request $request, Region $region): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/edit.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="region_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Region $region): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('region_index');
    }
}
