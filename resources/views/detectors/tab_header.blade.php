@inject('detectorsService', 'App\Services\DetectorsService')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'termostats') active show @endif" href="{{ route('termostats.index') }}">
                            <span>
                                <img width="22" height="20" title="" src="{{ asset('ela/images/objects/termostat.png') }}">
                                Датчики температуры ({{ $detectorsService->getTermostatsCount() }})
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'hygrostats') active show @endif" href="{{ route('hygrostats.index') }}">
                            <span>
                                <img width="18" height="20" title="" src="{{ asset('ela/images/objects/hygrometer.png') }}">
                                Датчики влажности ({{ $detectorsService->getHygrostatCount() }})
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'lightstats') active show @endif" href="{{ route('lightstats.index') }}">
                            <span>
                                <img width="18" height="20" title="" src="{{ asset('ela/images/objects/lightstat.png') }}">
                                Датчики освещенности ({{ $detectorsService->getLightstatsCount() }})
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'motionsensors') active show @endif" href="{{ route('motionsensors.index') }}">
                            <span>
                                <img width="18" height="20" title="" src="{{ asset('ela/images/objects/motionsensor.png') }}">
                                Датчики движения ({{ $detectorsService->getMotionsensorsCount() }})
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'usensors') active show @endif" href="{{ route('usensors.index') }}">
                            <span>
                                <i class="ti-home"></i> Универсальные датчики ({{ $detectorsService->getUsensorsCount() }})
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'drycontacts') active show @endif" href="{{ route('drycontacts.index') }}">
                            <span>
                                <img width="18" height="20" title="" src="{{ asset('ela/images/objects/drycontact.png') }}">
                                Сухие контакты ({{ $detectorsService->getDrycontactsCount() }})
                            </span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link @if($active == '') active show @endif" href="#">
                            <span>
                                <img width="18" height="20" title="" src="{{ asset('ela/images/objects/pass_sensor.png') }}">
                                Датчики прохода (0)
                            </span>
                        </a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link @if($active == 'carbmonoxide') active show @endif" href="{{ route('carbmonoxide.index') }}">
                            <span>
                                <img width="28" height="20" title="" src="{{ asset('ela/images/objects/carbsens.png') }}">
                                Датчики угарного газа ({{ $detectorsService->getCarbMonoxideCount() }})
                            </span>
                        </a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link @if($active == '') active show @endif" href="{{ route('manometr.index') }}">
                            <span>
                                <img width="20" height="20" title="" src="{{ asset('ela/images/objects/manometr.png') }}">
                                Манометры ({{ $detectorsService->getManometrCount() }})
                            </span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'pressurestats') active show @endif" href="{{ route('pressurestats.index') }}">
                            <span>
                                Датчики давления ({{ $detectorsService->getPressurestatsCount() }})
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($active == 'carbdioxides') active show @endif" href="{{ route('carbdioxides.index') }}">
                            <span>
                                Датчики углекислого газа ({{ $detectorsService->getCarbdioxidesCount() }})
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>