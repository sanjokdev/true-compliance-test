<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Models\Certificate;
use App\Models\Property;
use Illuminate\Http\Request;

class CertificatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Certificate::all()->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        return $certificate->toJson();
    }

    public function notes(Certificate $certificate)
    {
        return $certificate->notes->toJson();
    }

    public function storeNote(StoreNoteRequest $request, Certificate $certificate)
    {
        $note = $certificate->notes()->create($request->validated());
        return $note;
    }
}
