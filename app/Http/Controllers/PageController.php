<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\ImageService;
use App\Repositories\PageRepository;
use App\Repositories\ElementRepository;

class PageController extends Controller
{
    public function __construct(
        private PageRepository $pageRepository,
        private Page $pages,
        private ElementRepository $elementRepository
    ) {
    }

    public function index()
    {
        $pages = $this->pageRepository->getAll();
        $types = Page::getTypes(true);

        return view('pages.index', compact('pages', 'types'));
    }

    public function edit(int $idPage, int $idTab = 1)
    {
        $page = Page::find($idPage);
        $elements = $this->elementRepository->getAllByPage($idPage);
        $images = ImageService::getMainImages();
        $tab = $idTab;

        return view('pages.edit', compact(
            'page', 'elements', 'tab', 'images'
        ));
    }
}
