<?php

namespace App\Http\Controllers\admin;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::latest()->paginate(10);
        return view('admin.companies.index', compact('companies'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount_staff' => 'nullable|integer|min:1',
            'location' => 'required|string|max:255',
            'image_company' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $company = new Company($request->except('image_company'));
        
        if ($request->hasFile('image_company')) {
            $image = $request->file('image_company');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/companies'), $imageName);
            $company->image_company = $imageName;
        }

        $company->save();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Công ty đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount_staff' => 'nullable|integer|min:1',
            'location' => 'required|string|max:255',
            'image_company' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $company->fill($request->except('image_company'));

        if ($request->hasFile('image_company')) {
            // Xóa ảnh cũ nếu có
            if ($company->image_company) {
                $oldImagePath = public_path('uploads/companies/' . $company->image_company);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image_company');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/companies'), $imageName);
            $company->image_company = $imageName;
        }

        $company->save();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Thông tin công ty đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Xóa ảnh nếu có
        if ($company->image_company) {
            $imagePath = public_path('uploads/companies/' . $company->image_company);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Công ty đã được xóa thành công.');
    }
}
