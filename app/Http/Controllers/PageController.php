<?php

namespace App\Http\Controllers;
use App\Repositories\PageRepository;
use App\Models\Page;
use App\Repositories\ElementRepository;
use App\Services\ImageService;


class PageController extends Controller
{

    private $pageRepository;
    private $elementRepository;
    private $pages;

    public function __construct(PageRepository $pageRepository, Page $pages, ElementRepository $elementRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->elementRepository = $elementRepository;
        $this->pages = $pages;

    }

    public function index()
    {

        $pages = $this->pageRepository->getAll();
        $types =  Page::getTypes(true);


        return view('pages.index', compact('pages', 'types'));
    }

    public function edit(int $idPage, int $idTab = 1)
    {
        $page = Page::find($idPage);
        $elements = $this->elementRepository->getAllByPage($idPage);
        $images = ImageService::getMainImages();
        $tab = $idTab;

        return view('pages.edit', compact('page', 'elements', 'tab', 'images'));

    }


}
