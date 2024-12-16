<?php

namespace App\Http\Controllers;

use App\DataTables\Appointment\AppointmentDataTable;
use App\Repositories\Contact\ContactRepository;
use App\Repositories\HumanResource\StaffRepository;
use App\Repositories\Interfaces\Contact\ContactRepositoryInterface;
use App\Repositories\Interfaces\HumanResource\StaffRepositoryInterface;
use App\Repositories\Interfaces\LawCase\LawCaseRepositoryInterface;
use App\Repositories\Interfaces\People\ClientRepositoryInterface;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use App\Repositories\LawCase\LawCaseRepository;
use App\Repositories\People\ClientRepository;
use App\Repositories\Task\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

    public const HOME = '/admin/home';

    public function index()
    {
        return view('admin.pages.dashboard.index');
    }
    
    public function analytics()
    {
        return view('admin.pages.analytics.index');
    }

    public function logout()
    {
        Session::invalidate();
        Auth::logout();
        return Redirect::to(route('login'));
    }
}
