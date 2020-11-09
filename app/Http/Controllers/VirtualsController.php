<?php

namespace App\Http\Controllers;

use App\Repositories\VirtualRepository;
use Illuminate\Http\Request;

class VirtualsController extends Controller
{

    private $virt_rep;

    public function __construct(VirtualRepository $virtualRepository) {

        $this->virt_rep = $virtualRepository;
    }

    public function index()
    {
        $virtuals = $this->virt_rep->getAll();

        return view('virtuals.index', compact('virtuals'));
    }
}
