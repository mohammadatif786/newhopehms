<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\Attachment;
use App\Models\Invoice;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PatientDetailController extends Controller
{
    /**
     * Constructor
     */
    function __construct()
    {
        $this->middleware('permission:patient-detail-read|patient-detail-create|patient-detail-update|patient-detail-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:patient-detail-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient-detail-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:patient-detail-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     if ($request->export) {
    //         return $this->doExport($request);
    //     }

    //     // Fetch patients who are not discharged, not old patients, and are not doctors
    //     $patientDetails = User::where('status', '!=', 'discharged')
    //         ->where('status', '!=', 'old patient') // Exclude 'old patient' status
    //         ->where('role', '!=', 'doctor') // Exclude users with the role of doctor
    //         ->paginate(10);

    //     return view('patient-detail.index', compact('patientDetails'));
    // }

    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }

        // Initialize the query
        $query = User::query();

        // Apply filters if any exist
        $query = $this->filter($request, $query);

        // Exclude discharged, old patients, and doctors
        $patientDetails = $query->where('status', '!=', 'discharged')
            ->where('status', '!=', 'old patient')
            // ->where('role', '!=', 'doctor') // Exclude doctors
            ->paginate(10);

        return view('patient-detail.index', compact('patientDetails'));
    }



    /**
     * Performs exporting
     *
     * @param Request $request
     * @return void
     */
    private function doExport(Request $request)
    {
        return Excel::download(new UserExport($request, 'Patient'), 'Patients.xlsx');
    }

    /**
     * Filter function
     *
     * @param Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function filter(Request $request)
    {
        // Step 1: Check the role of the authenticated user
        if (auth()->user()->hasRole('Patient')) {
            $query = User::role('Patient')
                ->where('company_id', session('company_id'))
                ->where('id', auth()->id())
                ->latest();
        } else {
            $query = User::role('Patient')
                ->where('company_id', session('company_id'))
                ->latest();
        }

        // Step 2: Apply the filters if they are provided
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->phone) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Corrected 'categorey' filter with wildcard search
        if ($request->categorey) {
            $query->where('categorey', 'like', '%' . $request->categorey . '%');
        }

        // Step 3: Return the query (it can be executed later in the flow)
        return $query;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = User::role('doctor')->get();
        return view('patient-detail.create', compact('doctors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validation($request); // Assuming this handles validation rules

        // Include doctor_id in the user data
        $userData = $request->only(['name', 'email', 'phone', 'address', 'gender', 'blood_group', 'status', 'doctor_id', 'text_field', 'custom_field', 'previous_drug', 'total_payment', 'categorey']);
        $userData['company_id'] = session('company_id');
        $userData['password'] = bcrypt($request->password);

        if ($request->photo) {
            $userData['photo'] = 'storage/' . $request->photo->store('user-images');
        }

        if ($request->date_of_birth) {
            $userData['date_of_birth'] = Carbon::parse($request->date_of_birth);
        }

        // Start the database transaction
        DB::transaction(function () use ($request, $userData) {
            $user = User::create($userData);
            $role = Role::where('name', 'Patient')->first();
            $user->assignRole([$role->id]);
            $user->companies()->attach(session('company_id'));

            // Store attachments if they exist
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {

                    $fileName = $file->getClientOriginalName();
                    $file->storeAs('attachments', $fileName, 'public');


                    Attachment::create([
                        'user_id' => $user->id,
                        'file_path' => $fileName,
                    ]);
                }
            }
        });

        return redirect()->route('patient-details.index')->with('success', trans('Patient Added Successfully'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $patientDetail
     * @return \Illuminate\Http\Response
     */
    public function show(User $patientDetail)
    {
        // If the user is a patient and they are not viewing their own profile, redirect them
        if (auth()->user()->hasRole('Patient') && auth()->id() != $patientDetail->id) {
            return redirect()->route('dashboard');
        }

        // Fetch the invoices for the specific patient using their ID
        $invoices = Invoice::where('user_id', $patientDetail->id)->first(); // Corrected line

        // Eager load the 'doctor' relationship
        $patientDetail->load('doctor'); // Assuming 'doctor' is the relationship name

        return view('patient-detail.show', compact('patientDetail', 'invoices'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $patientDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(User $patientDetail)
    {
        $doctors = User::role('doctor')->get();
        return view('patient-detail.edit', compact('patientDetail', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $patientDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $patientDetail)
    {
        $this->validation($request, $patientDetail->id);

        $userData = $request->only(['name', 'email', 'phone', 'address', 'gender', 'blood_group', 'status', 'text_field', 'custom_field', 'previous_drug', 'doctor_id', 'total_payment']);
        if ($request->password)
            $userData['password'] = bcrypt($request->password);

        if ($request->photo)
            $userData['photo'] = 'storage/' . $request->photo->store('user-images');

        if ($request->date_of_birth)
            $userData['date_of_birth'] = Carbon::parse($request->date_of_birth);

        DB::transaction(function () use ($patientDetail, $userData) {
            $patientDetail->update($userData);
        });

        return redirect()->route('patient-details.index')->with('success', trans('Patient Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $patientDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $patientDetail)
    {
        $patientDetail->delete();
        return redirect()->route('patient-details.index')->with('success', trans('Patient Deleted Successfully'));
    }

    /**
     * validation check for create & edit
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['unique:users,', 'max:255'],
            'phone' => ['nullable', 'string', 'max:14'],
            'gender' => ['nullable', 'in:male,female'],
            'blood_group' => ['nullable', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'address' => ['nullable', 'string', 'max:1000'],
            'date_of_birth' => ['nullable', 'date'],
            'status' => ['required', 'in:0,1'],
            'doctor_id' => 'nullable|exists:users,id',
            //'file_path' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // if (empty($id)) {
        //     $request->validate([
        //         'password' => ['required', 'string', 'min:8', 'max:255']
        //     ]);
        // } else {
        //     $request->validate([
        //         'password' => ['nullable', 'string', 'min:8', 'max:255']
        //     ]);
        // }
    }
    public function discharge(User $patient)
    {
        // Check if the due_payment is null
        if (is_null($patient->due_payment)) {
            // Prevent discharge and show an error message
            return redirect()->back()->withErrors(['discharge_error' => 'Patient cannot be discharged as the due payment status is unknown.']);
        }

        // Check if the patient has due payments
        if ($patient->due_payment > 0) {
            // Prevent discharge and show an error message
            return redirect()->back()->withErrors(['discharge_error' => 'Patient cannot be discharged until the due payment is cleared.']);
        }

        // Allow discharge since there are no due payments
        $patient->status = 'discharged';
        $patient->save();

        return redirect()->back()->with('success', 'Patient discharged successfully.');
    }


    public function dischargedPatients()
    {
        // Get all patients with 'discharged' status
        $dischargedPatients = User::where('status', 'discharged')->paginate(10);

        // Pass the discharged patients to the view
        return view('patient-detail.discharge-patient', compact('dischargedPatients'));
    }
    public function markAsOld($patientId)
    {
        // Find the patient by ID
        $patient = User::findOrFail($patientId);

        // Update the status to 'old patient'
        $patient->status = 'old patient';
        $patient->save();

        // Redirect back to the patient details page with a success message
        return redirect()->route('patient-details.index')->with('success', 'Patient status updated to old patient.');
    }
    public function oldPatients()
    {
        if (!auth()->check()) {
            // Handle the case where the user is not logged in
            return redirect()->route('login')->with('error', 'You need to log in to view this page.');
        }

        // Get the current user's company_id
        $companyId = auth()->user()->company_id;

        // Fetch patients whose status is 'old patient' and belong to the same company
        $oldPatients = User::where('status', 'old patient')
            ->where('company_id', $companyId)
            ->paginate(10);

        // Return the data to the new view
        return view('patient-detail.old-patient', compact('oldPatients'));
    }

    public function updateStatus($id)
    {
        // Find the patient by ID
        $patient = User::findOrFail($id);

        // Update the status to '1'
        $patient->status = 1;

        // Save the changes
        $patient->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Patient status updated successfully.');
    }
    public function changeStatus($id)
    {
        // Find the patient by ID
        $patient = User::find($id);

        if ($patient) {
            // Set the status to '1' (discharged)
            $patient->status = 1;  // Use 'status' column instead of 'discharged'
            $patient->save();
        }

        return redirect()->back()->with('success', 'Patient status changed successfully.');
    }
    public function updatePayment(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id',  // Validate that user_id exists
            'paid_payment' => 'required|numeric', // Validate paid_payment is a number
        ]);

        // Retrieve the user by user_id
        $user = User::findOrFail($request->user_id);

        // Calculate due payment and update the user record
        $due_payment = $user->total_payment - $request->paid_payment;

        // Update user's payment details
        $user->paid_payment = $request->paid_payment;
        $user->due_payment = $due_payment;
        $user->save();

        return redirect()->back()->with('success', 'Payment updated successfully!');
    }
    // public function showAttachments($userId)
    // {
    //     // Retrieve the user by ID or fail if not found
    //     $user = User::findOrFail($userId);

    //     // Directly use attachments if it's already an array
    //     $attachments = $user->attachments ?? []; // Ensure it's an array

    //     // Pass the user and attachments to the view
    //     return view('patient-detail.attachment', compact('user', 'attachments'));
    // }
    public function showAttachments(Request $request, $id)
    {

        $user = User::find($id);
        $attachments = Attachment::where('user_id', $user->id)->get();

        return view('patient-detail.attachment', compact('user', 'attachments'));
    }
    public function uploadAttachments($id)
    {
        $user = User::find($id);
        return view('patient-detail.upload-attachment', compact('user'));
    }
    public function storeAttachments(Request $request, $id)
    {
        //dd($request->all());
        $user = User::find($id);
        if ($request->ajax()) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('attachments', $fileName, 'public');
                $fileType = $file->getClientOriginalExtension();
                Attachment::create([
                    'user_id' => $user->id,
                    'file_path' => $fileName,
                    'file_type' => $fileType,
                ]);
            }
        }
    }
}
