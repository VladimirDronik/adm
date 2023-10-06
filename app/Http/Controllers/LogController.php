<?php

namespace App\Http\Controllers;

use App\Models\Logging;
use App\Repositories\LogRepository;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct(
        private LogRepository $log_rep
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
        $logs = $this->log_rep->getByFilter($filter);
        $types = $this->log_rep->getTypes();

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
