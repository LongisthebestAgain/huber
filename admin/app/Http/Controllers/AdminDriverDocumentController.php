<?php

namespace App\Http\Controllers;

use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDriverDocumentController extends Controller
{
    public function index()
    {
        // Get all drivers who have documents
        $drivers = User::where('role', 'driver')
                      ->whereHas('driverDocuments')
                      ->with('driverDocuments')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('admin.driver_documents.index', compact('drivers'));
    }

    public function show($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driverDocument = DriverDocument::where('user_id', $driver->id)->first();
        
        return view('admin.driver_documents.show', compact('driver', 'driverDocument'));
    }

    public function verify($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->update(['is_verified' => true]);
        
        return redirect()->route('admin.driver_documents.show', $driver->id)
                        ->with('success', 'Driver has been verified successfully!');
    }

    public function unverify($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->update(['is_verified' => false]);
        
        return redirect()->route('admin.driver_documents.show', $driver->id)
                        ->with('success', 'Driver verification has been revoked.');
    }

    public function create()
    {
        return view('admin.driver_documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_number' => 'required',
        ]);
        DriverDocument::create($request->all());
        return redirect()->route('admin.driver_documents.index')->with('success', 'Driver document created successfully');
    }

    public function edit($id)
    {
        $document = DriverDocument::findOrFail($id);
        return view('admin.driver_documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = DriverDocument::findOrFail($id);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_number' => 'required',
        ]);
        $document->update($request->all());
        return redirect()->route('admin.driver_documents.index')->with('success', 'Driver document updated successfully');
    }

    public function destroy($id)
    {
        $document = DriverDocument::findOrFail($id);
        $document->delete();
        return redirect()->route('admin.driver_documents.index')->with('success', 'Driver document deleted successfully');
    }
} 