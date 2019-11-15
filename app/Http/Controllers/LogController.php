<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\CreateRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Repositories\EventRepository;
use App\Repositories\LogRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Services\EventService;
use App\Services\ObjectService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    private $log_rep;

    public function __construct(LogRepository $log_rep)
    {
        $this->log_rep = $log_rep;
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
}
