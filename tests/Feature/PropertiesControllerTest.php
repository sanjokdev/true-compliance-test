<?php


namespace Tests\Feature;


use App\Models\Certificate;
use App\Models\Note;
use App\Models\Property;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertiesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_display_all_properties()
    {
        //Arrange
        Property::factory(5)->create();

        //Act
        $response = $this->getJson('/api/property');

        //Asserts
        $response->assertOk();
        $response->assertJsonCount(5);
        $response->assertJsonStructure([
            [
                'id',
                'organisation',
                'property_type',
                'parent_property_id',
                'uprn',
                'address',
                'town',
                'postcode',
                'live',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ]);
    }

    public function test_it_can_display_single_property()
    {
        //Arrange
        $property = Property::factory()->create();

        //Act
        $response = $this->getJson('/api/property/' . $property->id);

        //Asserts
        $response->assertOk();
        $response->assertJsonStructure([
                'id',
                'organisation',
                'property_type',
                'parent_property_id',
                'uprn',
                'address',
                'town',
                'postcode',
                'live',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        );
        $response->assertJson([
            'id' => $property->id,
            'organisation' => $property->organisation,
            'property_type' => $property->property_type,
            'parent_property_id' => $property->parent_property_id,
            'uprn' => $property->uprn,
            'address' => $property->address,
            'town' => $property->town,
            'postcode' => $property->postcode,
            'live' => $property->live,
        ]);
    }

    public function test_it_can_create_a_new_property()
    {
        //Arrange
        $payload = [
            "organisation" => "Test Housing",
            "property_type" => "Resident",
            "parent_property_id" => null,
            "uprn" => "15211",
            "address" => "Example 1 street",
            "town" => "New Town",
            "postcode" => "CA12 7UU",
            "live" => 1,
        ];

        //Act
        $response = $this->postJson("/api/property", $payload);

        //Asserts
        $response->assertCreated();
        $response->assertJsonStructure(
            [
                'id',
                'organisation',
                'property_type',
                'parent_property_id',
                'uprn',
                'address',
                'town',
                'postcode',
                'live',
                'created_at',
                'updated_at',
            ]
        );
        $this->assertDatabaseHas('properties', [
            'organisation' => "Test Housing",
            "property_type" => "Resident",
            "parent_property_id" => null,
            "uprn" => "15211",
            "address" => "Example 1 street",
            "town" => "New Town",
            "postcode" => "CA12 7UU",
            "live" => 1,
        ]);

    }

    public function test_it_can_update_a_single_property()
    {
        //Arrange
        $property = Property::factory()->create();
        $payload = [
            "organisation" => "Updated Organisation",
            "address" => "Updated 1st Street"
        ];

        //Act
        $response = $this->putJson("/api/property/{$property->id}", $payload);

        //Assert
        $response->assertOk();
        $this->assertDatabaseHas('properties', [
            "organisation" => "Updated Organisation",
            "address" => "Updated 1st Street"
        ]);

    }

    public function test_it_soft_deletes_a_property()
    {
        //Arrange
        $property = Property::factory()->create();

        //Act
        $response = $this->delete("/api/property/{$property->id}");

        //Asserts
        $response->assertOk();
        $this->assertSoftDeleted('properties', ["id" => $property->id]);
        $this->assertNull(Property::find($property->id));
    }

    public function test_it_returns_certificates_of_a_property()
    {
        //Arrange
        $property = Property::factory()
            ->has(Certificate::factory()->count(3))
            ->create();


        //Act
        $response = $this->getJson("/api/property/{$property->id}/certificate");

        //Assert
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
                [
                    "id",
                    "stream_name",
                    "property_id",
                    "issue_date",
                    "next_due_date",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                ]
            ]
        );
    }

    public function test_it_returns_notes_of_property()
    {
        //Arrange
        $property = Property::factory()->create();
        Note::factory()
            ->count(3)
            ->model($property)
            ->create();

        //Act
        $response = $this->getJson("/api/property/{$property->id}/note");

        //Assert
        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            [
                "id",
                "model_type",
                "model_id",
                "note",
                "created_at",
                "updated_at",
            ]
        ]);
    }

    public function test_it_can_create_notes_for_a_property()
    {
        //Arrange
        $property = Property::factory()->create();
        $payload = [
            "note" => "test note for a property"
        ];

        $response = $this->postJson("/api/property/{$property->id}/note", $payload);
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
            "note" => "test note for a property",
            "model_id" => $property->id,
            "model_type" => "Property"
        ]);
    }

    public function test_it_returns_properties_with_certificate_more_than_5_using_eloquent_query_endpoint()
    {
        //Arrange
        $propertywith6cert = Property::factory()
            ->has(Certificate::factory()->count(6))
            ->create();
        $propertywith7cert = Property::factory()
            ->has(Certificate::factory()->count(7))
            ->create();
        $propertywith3cert = Property::factory()
            ->has(Certificate::factory()->count(3))
            ->create();

        //Act
        $response = $this->getJson("/api/property/certicates/eloq");

        //Assert
        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            "property_id" => $propertywith6cert->id,
            "property_id" => $propertywith7cert->id
        ]);
        $response->assertJsonMissing([
            "property_id" => $propertywith3cert->id
        ]);
    }

    public function test_it_returns_properties_with_certificate_more_than_5_using_raw_query_endpoint()
    {
        //Arrange
        $propertywith6cert = Property::factory()
            ->has(Certificate::factory()->count(6))
            ->create();
        $propertywith7cert = Property::factory()
            ->has(Certificate::factory()->count(7))
            ->create();
        $propertywith3cert = Property::factory()
            ->has(Certificate::factory()->count(3))
            ->create();

        //Act
        $response = $this->getJson("/api/property/certicates/raw");

        //Assert
        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            "id" => $propertywith6cert->id,
            "id" => $propertywith7cert->id
        ]);
        $response->assertJsonMissing([
            "id" => $propertywith3cert->id
        ]);
    }
}
