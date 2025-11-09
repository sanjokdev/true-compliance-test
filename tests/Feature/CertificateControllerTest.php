<?php


namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Note;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_display_all_certificates()
    {
        //Arrange
        Certificate::factory(3)->create();

        //Act
        $response = $this->getJson('/api/certificate');

        //Asserts
        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            [
                'id',
                'stream_name',
                'property_id',
                'issue_date',
                'next_due_date',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ]);
    }

    public function test_it_can_display_single_certificate()
    {
        //Arrange
        $certificate = Certificate::factory()->create();

        //Act
        $response = $this->getJson("/api/certificate/{$certificate->id}");

        //Asserts
        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'stream_name',
            'property_id',
            'issue_date',
            'next_due_date',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
        $response->assertJson([
            'id' => $certificate->id,
            'stream_name' => $certificate->stream_name,
            'property_id' => $certificate->property_id,
            'issue_date' => $certificate->issue_date,
            'next_due_date' => $certificate->next_due_date,
            'deleted_at' => null,
        ]);
    }

    public function test_it_can_create_a_new_certificate()
    {
        //Arrange
        $property = Property::factory()->create();

        $payload = [
            "stream_name" => "Test Gas",
            "issue_date" => "2020-07-03",
            "next_due_date" => "2021-07-03"
        ];

        //Act
        $response = $this->postJson("/api/property/{$property->id}/certificate", $payload);

        //Asserts
        $response->assertCreated();
        $response->assertJsonStructure(
            [
                'id',
                'stream_name',
                'issue_date',
                'next_due_date',
                'property_id',
                'updated_at',
                'created_at',
            ]
        );
        $this->assertDatabaseHas('certificates', [
            "stream_name" => "Test Gas",
            "issue_date" => "2020-07-03",
            "next_due_date" => "2021-07-03",
            "property_id" => $property->id
        ]);
    }

    public function test_it_can_get_notes_of_a_certificate()
    {
        //Arrange
        $certificate = Certificate::factory()->create();
        Note::factory()
            ->count(3)
            ->model($certificate)
            ->create();

        //Act
        $response = $this->getJson("/api/certificate/{$certificate->id}/note");

        //Asserts
        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            [
                'id',
                'model_type',
                'model_id',
                'note',
                'updated_at',
                'created_at',
            ]
        ]);
    }

    public function test_it_can_create_note_for_a_certificate()
    {
        //Arrange
        $certificate = Certificate::factory()->create();
        $payload = [
            "note" => "test note for a certificate"
        ];

        //Act
        $response = $this->postJson("/api/certificate/{$certificate->id}/note", $payload);

        //Assert
        $response->assertCreated();
        $response->assertJsonStructure([
            "id",
            "note",
            "model_id",
            "model_type",
            "updated_at",
            "created_at",
        ]);
        $this->assertDatabaseHas('notes', [
            "note" => "test note for a certificate",
            "model_id" => $certificate->id,
            "model_type" => "Certificate"
        ]);
    }
}
