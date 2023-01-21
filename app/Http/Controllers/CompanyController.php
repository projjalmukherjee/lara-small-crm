<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::orderBy('id', 'desc')->paginate(2);
        return view('company.index', ['companies' => $companies]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|unique:companies,email',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:mim_width=100,min_height=100',
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('logo')) {
           $logo = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $logo);
           // $image->move($destinationPath, $profileImage);
            $input['logo'] = "$logo";
        }
    
        Company::create($input);
     
        return redirect()->route('company.index')
                        ->with('success','Company created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {

        return view('company.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|unique:companies,email,'.$company->id,
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:mim_width=100,min_height=100',
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('logo')) {
            $logo = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $logo);
           // $image->move($destinationPath, $profileImage);
            $input['logo'] = "$logo";
            if ($company->logo) {
                Storage::delete('public/images/'.$company->logo);
              }
        }else{
            unset($input['logo']);
        }
          
        $company->update($input);
    
        return redirect()->route('company.index')
                        ->with('success','Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        Storage::delete('public/images/'.$company->logo);
        return redirect()->route('company.index')
                        ->with('success','Company deleted successfully');
    }
}
