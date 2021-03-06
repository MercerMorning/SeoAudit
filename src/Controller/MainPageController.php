<?php

namespace App\Controller;

use App\Form\SiteSeoAnalyzeFormType;
use App\Services\Analyzer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    /**
     * @Route("/analyze", methods={"GET","POST"}, name="main_page")
     */
    public function index(Request $request): Response
    {

        $form = $this->createForm(SiteSeoAnalyzeFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $analyzer = new Analyzer();
            dd($analyzer->analyzeUrl('https://www.php.net/manual/ru/migration71.new-features.php'));
        }
        return $this->render('analyze/index.html.twig', [
            'seo_analyze_form' => $form->createView(),
        ]);
    }
}
