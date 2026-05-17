<?php

namespace App\Http\Controllers;

use App\Events\CompanyCreated;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('companies.index', [
            'companies' => Company::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
        ]);

        $company = Company::create($data);

        CompanyCreated::dispatch($company);

        return response()->json($company, 201);
    }
}
