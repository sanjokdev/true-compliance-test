<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Property::all()->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        $property = Property::create($request->validated());
        return $property;
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        return $property;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $property->update($request->validated());
        return $property;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        return $property->delete();
    }

    public function certificates(Property $property)
    {
        return $property->certificates->toJson();
    }

    public function storeCertificate(StoreCertificateRequest $request, Property $property)
    {
        $certificate = $property->certificates()->create($request->validated());
        return $certificate;
    }

    public function notes(Property $property)
    {
        return $property->notes;
    }

    public function storeNote(StoreNoteRequest $request, Property $property)
    {
        $note = $property->notes()->create($request->validated());
        return $note;
    }

    public function getPropertiesWithCertificatesMoreThan5Eloq()
    {
        return Property::has('certificates', '>', 5)->with('certificates')->get();
    }

    public function getPropertiesWithCertificatesMoreThan5Raw()
    {
        $sql = "SELECT p.*,COUNT(c.id) as 'certificate_count'
                FROM properties p
                JOIN certificates c
                ON c.property_id = p.id
                GROUP BY p.id, p.organisation
                HAVING COUNT(c.id) > 5;
                ";
        $result = DB::select($sql);
        return response()->json($result);
    }
}
