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
     * Display a all certificates.
     */
    public function index()
    {
        return Certificate::all()->toJson();
    }

    /**
     * Display a certificate.
     */
    public function show(Certificate $certificate)
    {
        return $certificate->toJson();
    }

    /**
     * Display notes related to a certificate.
     */
    public function notes(Certificate $certificate)
    {
        return $certificate->notes->toJson();
    }

    /**
     * Store a note related to a certificate.
     */
    public function storeNote(StoreNoteRequest $request, Certificate $certificate)
    {
        $note = $certificate->notes()->create($request->validated());
        return $note;
    }
}
