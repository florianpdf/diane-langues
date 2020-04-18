<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\DomainRepository;
use App\Repository\ReferenceRepository;
use App\Repository\ServiceRepository;
use App\Repository\ContactRepository;
use App\Repository\PortFolioRepository;
use App\Repository\AboutRepository;

class HomePageController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function renderClientHomePage(
        DomainRepository $domainRepository, 
        ReferenceRepository $referenceRepository,
        ServiceRepository $serviceRepository,
        ContactRepository $contactRepository,
        PortFolioRepository $portFolioRepository,
        AboutRepository $aboutReposiroty
        )
    {
        $domains = $domainRepository->findAll();
        $references = $referenceRepository->findAll();
        $services = $serviceRepository->findAll();
        $contact = $contactRepository->findAll();
        $portFolios = $portFolioRepository->findAll();
        $abouts = $aboutReposiroty->findAll();

        return $this->render('client/index.html.twig', array(
            'domains' => $domains,
            'references' => $references,
            'services' => $services,
            'contact' => $contact[0],
            'portFolios' => $portFolios,
            'abouts' => $abouts
        ));
    }
}