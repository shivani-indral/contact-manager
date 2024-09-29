<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

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
    $request->validate([
        'xml_file' => 'required|file|mimes:xml',
    ]);

    $xmlFile = $request->file('xml_file');
    $xmlContent = simplexml_load_file($xmlFile);

    foreach ($xmlContent->contact as $contact) {
        Contact::create([
            'name' => (string) $contact->name,
            'lastname' => (string) $contact->lastname,
            'phone' => (string) $contact->phone,
        ]);
    }

    return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
}

}
