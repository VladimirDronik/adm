<?php

namespace App\Http\Controllers;

use App\Models\Logging;
use Illuminate\Http\Request;
use App\Repositories\LogRepository;

class LogController extends Controller
{
    public function __construct(
        private LogRepository $logRep
    ) {
    }

    private function getFilter(Request $r)
    {
        $filter['start'] = $r->input('start', '');
        $filter['end'] = $r->input('end', '');
        $filter['type'] = $r->input('type', '');

        return $filter;
    }

    public function index(Request $r)
    {
        $filter = $this->getFilter($r);
        $logs = $this->logRep->getByFilter($filter);
        $types = $this->logRep->getTypes();

        return view('logs.index', compact('logs', 'types', 'filter'));
    }

    public function settings(Logging $logging)
    {
        $settings = $logging->orderBy('point')->get();

        return view('logs.settings', ['settings' => $settings]);
    }

    public function update(Request $r)
    {

    }

    public function active()
    {

    }
}
