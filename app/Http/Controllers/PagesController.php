<?php

namespace App\Http\Controllers;
use App\Repositories\PagesRepository;
use App\Models\Pages;


class PagesController extends Controller
{

    private $pageRepository;
    private $pages;

    public function __construct(PagesRepository $pagesRepository, Pages $pages)
    {
        $this->pageRepository = $pagesRepository;
        $this->pages = $pages;

    }

    public function index()
    {

        $pages = $this->pageRepository->getAll();
        $types =  Pages::getTypes(true);


        return view('pages.index', compact('pages', 'countElements', 'types'));
    }


}
