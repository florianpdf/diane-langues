<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\DomainRepository;
use App\Repository\ReferenceRepository;
use App\Repository\ServiceRepository;
use App\Repository\WebsiteConfigRepository;
use App\Repository\PortFolioRepository;
use App\Repository\AboutRepository;
use App\Entity\About;


class HomePageController extends AbstractController
{
    /**
     * @Route("/{_locale}", 
     *      name="homepage", 
     *      requirements={"_locale":"%app_locales%"},
     *      defaults={"_locale":"%default_locale%"}
     * )
     */
    public function renderClientHomePage(
        DomainRepository $domainRepository, 
        ReferenceRepository $referenceRepository,
        ServiceRepository $serviceRepository,
        WebsiteConfigRepository $websiteConfigRepository,
        PortFolioRepository $portFolioRepository,
        AboutRepository $aboutReposiroty
        )
    {
        $domains = $domainRepository->findAll();
        $references = $referenceRepository->findAll();
        $services = $serviceRepository->findAll();
        $websiteConfig = $websiteConfigRepository->findOneBy([]);
        $abouts = $aboutReposiroty->findAll();
        $portFolios = $portFolioRepository->findAll();

        $appLocales = array();
        foreach (explode('|', $this->getParameter('app_locales')) as $locale) {
            $appLocales[$locale] = $locale;
        }

        return $this->render('client/index.html.twig', array(
            'domains' => $domains,
            'references' => $references,
            'services' => $services,
            'websiteConfig' => $websiteConfig,
            'portFolios' => $portFolios,
            'abouts' => $abouts,
            'appLocales' => $appLocales
        ));
    }

    /**
     * @Route("/email/{_locale}", 
     *      name="email", 
     *      requirements={"_locale":"%app_locales%"},
     *      defaults={"_locale":"%default_locale%"}
     * )
     */
    public function renderEmail(){
        return $this->render('email/index.html.twig');
    }
}