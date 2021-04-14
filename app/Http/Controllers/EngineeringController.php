<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 9:46
 */

namespace App\Http\Controllers;
use App\Repositories\ObjectRepository;
use App\Models\Boiler;

class EngineeringController extends Controller
{

    private $objectRepository;

    public function __construct(ObjectRepository $objectRepository)
    {

        $this->objectRepository = $objectRepository;

    }

    public function index()
    {
        $equipments = $this->objectRepository->getAllEngineering();
        return view('engineering.index', compact('equipments'));
    }



}