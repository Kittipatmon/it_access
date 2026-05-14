<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\RequestFormRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $requestRepo;

    public function __construct(RequestFormRepository $requestRepo)
    {
        $this->requestRepo = $requestRepo;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!($user->role === 'admin' || $user->dept_id == 16)) {
            return redirect()->route('request.index')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงส่วนงานนี้ เฉพาะแผนก ICT หรือผู้ดูแลระบบเท่านั้น');
        }

        // Get filter params (default to current month/year)
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', date('m'));

        // Query with filters
        $query = \App\Models\RequestForm::query();
        if ($selectedYear) {
            $query->whereYear('created_at', $selectedYear);
        }
        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->whereMonth('created_at', $selectedMonth);
        }

        $allRequests = $query->with(['steps', 'confidentialityAgreement'])
            ->latest()
            ->get();
        
        $stats = [
            'total' => $allRequests->count(),
            'pending' => $allRequests->where('status', 'pending')->count(),
            'completed' => $allRequests->where('status', 'completed')->filter(function($r) {
                $nda = $r->confidentialityAgreement;
                if (!$nda) return false;
                $isWaitingWitness = !$nda->witness1_agreed_at || ($nda->witness2_user_id && !$nda->witness2_agreed_at);
                return !$isWaitingWitness;
            })->count(),
            'it_pending' => $allRequests->where('status', 'approved')->where('it_status', '!=', 'completed')->count() + 
                           $allRequests->where('status', 'completed')->filter(function($r) {
                                $nda = $r->confidentialityAgreement;
                                if (!$nda) return true;
                                return !$nda->witness1_agreed_at || ($nda->witness2_user_id && !$nda->witness2_agreed_at);
                           })->count(),
            'rejected' => $allRequests->where('status', 'rejected')->count(),
        ];

        // Data for Charts
        $chartData = [
            'status' => [
                $stats['pending'],
                $stats['it_pending'],
                $stats['completed'],
                $stats['rejected']
            ],
            'monthly' => $allRequests->groupBy(function($date) {
                return $date->created_at->format('M');
            })->map->count(),
            'depts' => $allRequests->groupBy('department_name')->map->count()->sortDesc()->take(5)
        ];

        // Filter Options
        $years = [date('Y'), date('Y') - 1]; // Lookback 2 years
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];

        return view('backend.dashboard', compact('stats', 'allRequests', 'chartData', 'selectedYear', 'selectedMonth', 'years', 'months'));
    }
}
