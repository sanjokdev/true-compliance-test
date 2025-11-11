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
     * Display a listing of all properties.
     */
    public function index()
    {
        return Property::all()->toJson();
    }

    /**
     * Store a new property.
     */
    public function store(StorePropertyRequest $request)
    {
        $property = Property::create($request->validated());
        return $property;
    }

    /**
     * Display single property.
     */
    public function show(Property $property)
    {
        return $property;
    }

    /**
     * Update a property.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $property->update($request->validated());
        return $property;

    }

    /**
     * Soft delete a property.
     */
    public function destroy(Property $property)
    {
        return $property->delete();
    }

    /**
     * Show certificates related to a property
     */
    public function certificates(Property $property)
    {
        return $property->certificates->toJson();
    }

     /**
     * Store a certificate related to a property
     */
    public function storeCertificate(StoreCertificateRequest $request, Property $property)
    {
        $certificate = $property->certificates()->create($request->validated());
        return $certificate;
    }

     /**
     * Show notes related to a property
     */
    public function notes(Property $property)
    {
        return $property->notes;
    }

     /**
     * Store notes related to a property
     */
    public function storeNote(StoreNoteRequest $request, Property $property)
    {
        $note = $property->notes()->create($request->validated());
        return $note;
    }

     /**
     * Using Eloquent query, getting properties with more than 5 certificates
     */
    public function getPropertiesWithCertificatesMoreThan5Eloq()
    {
        return Property::has('certificates', '>', 5)->with('certificates')->get();
    }

     /**
     * Using Raw query, getting properties with more than 5 certificates
     */
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
