<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        Contact::create($request->validated());

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request,$id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->validated());

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }

    public function importXML(Request $request)
    {
        // Validate the uploaded XML file
        $request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);
    
        // Load the XML content
        $xmlFile = $request->file('xml_file');
        $xmlContent = simplexml_load_file($xmlFile);
    
        $errors = [];
    
        // Check if the XML content has contacts
        if (empty($xmlContent->contact)) {
            return redirect()->route('contacts.index')->with('error', 'No contacts found in the XML file.');
        }
    
        // Iterate through each contact in the XML file
        foreach ($xmlContent->contact as $contact) {
            // Convert contact data to an array
            $contactData = [
                'name' => (string) $contact->name,
                'lastname' => (string) $contact->lastname,
                'phone' => (string) $contact->phone,
            ];
    
            // Manually validate the data using Laravel's Validator
            $validator = Validator::make($contactData, [
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'phone' => 'required',
            ]);
    
            if ($validator->fails()) {
                // Collect errors for this contact and skip creation
                $errors[] = [
                    'contact' => $contactData,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }
    
            // If validation passes, create the contact
            Contact::create($contactData);
        }
    
        // If there are any validation errors, redirect back with the error messages
        if (!empty($errors)) {
            // Use the array of errors to create a single error message
            $xmlErrors = [];
            foreach ($errors as $error) {
                $xmlErrors[] = 'Contact: ' . implode(', ', $error['contact']) . ' - Errors: ' . implode(', ', $error['errors']);
            }
    
            return redirect()->route('contacts.index')->withErrors(['xml_file' => $xmlErrors])->with('error', 'Some contacts could not be imported due to validation errors.');
        }
    
        return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
    }
    
}
