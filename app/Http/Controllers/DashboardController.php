<?php

namespace App\Http\Controllers;

use App\Models\HospitalDepartment;
use App\Models\Invoice;
use App\Models\PatientAppointment;
use App\Models\PatientCaseStudy;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @access public
     * @return mixed
     */
    public function index()
    {
        if (auth()->user()->hasRole('Super Admin'))
            return $this->adminDashboard();
        else
            // return view('dashboards.common-dashboard');
            return $this->adminDashboard();

    }

    /**
     * shows admin dashboard
     *
     * @return \Illuminate\Http\Response
     */
    private function adminDashboard()
    {
        $companyId = auth()->user()->company_id; // Assuming the user's company is stored in the `company_id` field

        // Fetch the filtered dashboard counts
        $dashboardCounts = $this->dashboardCounts($companyId);

        // Other financial data, also filtered by company_id
        $monthlyDebitCredit = $this->monthlyDebitCredit($companyId);
        $currentYearDebitCredit = $this->currentYearDebitCredit($companyId);
        $overallDebitCredit = $this->overallDebitCredit($companyId);

        // Return the view with the data
        return view('dashboardview', compact('dashboardCounts', 'monthlyDebitCredit', 'currentYearDebitCredit', 'overallDebitCredit'));
    }



    /**
     * shows admin char data
     *
     * @return \Illuminate\Http\Response
     */
    public function getChartData()
    {
        return response()->json([
            'monthlyDebitCredit' => $this->monthlyDebitCredit(),
            'currentYearDebitCredit' => $this->currentYearDebitCredit(),
            'overallDebitCredit' => $this->overallDebitCredit()
        ], 200);
    }

    /**
     * sums debit/credit monthly for bar chart
     *
     * @return array
     */
    private function monthlyDebitCredit()
    {
        return cache()->remember('monthlyDebitCredit', 600, function () {
            $credits = [];
            $debits = [];
            $labels = [];
            $results = DB::select('SELECT DISTINCT YEAR(invoice_date) AS "year", MONTH(invoice_date) AS "month" FROM invoices ORDER BY year DESC LIMIT 12');
            foreach ($results as $result) {
                $labels[] = '"' . date('F', mktime(0, 0, 0, $result->month, 10)) . ' ' . $result->year . '"';
                $credits[] = '"' . Payment::whereYear('payment_date', $result->year)->whereMonth('payment_date', $result->month)->sum('amount') . '"';
                $debits[] = '"' . Invoice::whereYear('invoice_date', $result->year)->whereMonth('invoice_date', $result->month)->sum('grand_total') . '"';
            }

            return [
                'credits' => $credits,
                'debits' => $debits,
                'labels' => $labels
            ];
        });
    }

    /**
     * sums debit/credit of current year for pie chart
     *
     * @return array
     */
    private function currentYearDebitCredit()
    {
        return cache()->remember('currentYearDebitCredit', 600, function () {
            $credits = 0;
            $debits = 0;

            $credits = Payment::whereYear('payment_date', date('Y'))->sum('amount');
            $debits = Invoice::whereYear('invoice_date', date('Y'))->sum('grand_total');

            return [
                'credits' => $credits,
                'debits' => $debits
            ];
        });
    }

    /**
     * sums debit/credit of overall for pie chart
     *
     * @return array
     */
    private function overallDebitCredit()
    {
        return cache()->remember('overallDebitCredit', 600, function () {
            $credits = 0;
            $debits = 0;

            $credits = Payment::sum('amount');
            $debits = Invoice::sum('grand_total');

            return [
                'credits' => $credits,
                'debits' => $debits
            ];
        });
    }

    private function dashboardCounts($companyId)
    {
        return [
            //'departments' => HospitalDepartment::where('company_id', $companyId)->count(),
            'doctors' => User::role('Doctor')->where('company_id', $companyId)->count(),
            'patients' => User::role('Patient')->where('company_id', $companyId)->count(),
            //'appointments' => PatientAppointment::where('company_id', $companyId)->count(),
            //'caseStudies' => PatientCaseStudy::where('company_id', $companyId)->count(),
            //'prescriptions' => Prescription::where('company_id', $companyId)->count(),
            //'invoices' => Invoice::where('company_id', $companyId)->count(),
            //'payments' => Payment::where('company_id', $companyId)->count(),
            'old_patients' => User::where('company_id', $companyId)->where('status', 'old patient')->count() ?: 0,
            'discharge_patient' => User::where('company_id', $companyId)->where('status', 'discharged')->count() ?: 0,
        ];
    }
}
