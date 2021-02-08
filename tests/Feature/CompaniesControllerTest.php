<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompaniesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function test_companies_can_be_listed()
    {
        $user = User::factory()->create();

        $user->companies()->saveMany(
            Company::factory(3)->make()
        );

        $response = $this->actingAs($user)->get('/companies');

        $response->assertJsonCount(3);
    }

    public function test_companies_can_be_created()
    {
        $user = User::factory()->create();

        $data = Company::factory()->make()->makeHidden('logo')->toArray();

        $response = $this->actingAs($user)->post('/companies', $data);

        $response->assertJson([
            'name' => $data['name'],
        ]);
    }

    public function test_companies_can_be_created_with_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $data = Company::factory()->make()->makeHidden('logo')->toArray();

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($user)->post('/companies', array_merge($data, ['logo' => $file]));

        $response->assertJson([
            'name' => $data['name'],
        ]);

        Storage::disk('public')->assertExists("/images/{$file->hashName()}");
    }

    public function test_companies_cant_be_created_with_invalid_data()
    {

    }

    public function test_companies_can_be_shown()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $response = $this->actingAs($user)->get("/companies/{$company->id}");

        $response->assertJson([
            'name' => $company->name,
        ]);
    }

    public function test_companies_can_be_updated_without_logo()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $data = Company::factory()->make()->makeHidden('logo')->toArray();

        $response = $this->actingAs($user)->put("/companies/{$company->id}", $data);

        $response->assertJson([
            'name' => $data['name'],
        ]);
    }

    public function test_companies_can_be_updated_with_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $previousFile = UploadedFile::fake()->image('image.jpg');

        $company = $user->companies()->save(
            Company::factory()->make(['logo' => "/images/{$previousFile->hashName()}"])
        );

        $data = Company::factory()->make()->makeHidden('logo')->toArray();

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($user)->put("/companies/{$company->id}", array_merge($data, ['logo' => $file]));

        $response->assertJson([
            'name' => $data['name'],
        ]);

        Storage::disk('public')->assertExists("/images/{$file->hashName()}");
        Storage::disk('public')->assertMissing("/images/{$previousFile->hashName()}");
    }

    public function test_companies_cant_be_updated_with_invalid_data()
    {

    }
}
