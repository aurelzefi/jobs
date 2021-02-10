<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $user = User::factory()
            ->has(Company::factory(3))
            ->create();

        $response = $this->actingAs($user)->get('/api/companies');

        $response->assertJsonCount(3);
    }

    public function test_companies_can_be_created()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->make()->makeHidden('logo');

        $response = $this->actingAs($user)->post('/api/companies', $company->toArray());

        $response->assertJson([
            'name' => $company->name,
        ]);
    }

    public function test_companies_can_be_created_with_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $company = Company::factory()->for($user)->make()->makeHidden('logo');

        $logo = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($user)->post('/api/companies', array_merge($company->toArray(), ['logo' => $logo]));

        $response->assertJson([
            'name' => $company->name,
        ]);

        Storage::disk('public')->assertExists("/images/{$logo->hashName()}");
    }

    public function test_companies_cant_be_created_with_invalid_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $logo = UploadedFile::fake()->create('document.pdf');

        $response = $this->actingAs($user)->post('/api/companies', [
            'logo' => $logo,
            'website' => ['wrong-website'],
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'logo', 'description', 'website', 'address', 'city',
        ]);

        $logo = UploadedFile::fake()->image('image.jpg')->size(2048);

        $response = $this->actingAs($user)->post('/api/companies', [
            'country_id' => 'wrong-country',
            'name' => ['wrong-name'],
            'logo' => $logo,
            'description' => ['wrong-description'],
            'website' => 'wrong-website',
            'address' => Str::random(256),
            'city' => Str::random(256),
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'logo', 'description', 'website', 'address', 'city',
        ]);

        $company = Company::factory()->for($user)->make();

        $response = $this->actingAs($user)->post('/api/companies', [
            'country_id' => $company->country_id,
            'name' => Str::random(256),
            'description' => $company->description,
            'website' => Str::random(256),
            'address' => Str::random(256),
            'city' => Str::random(256),
        ]);

        $response->assertJsonValidationErrors(['name', 'website', 'address', 'city']);
    }

    public function test_companies_can_be_shown()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $response = $this->actingAs($user)->get("/api/companies/{$company->id}");

        $response->assertJson([
            'name' => $company->name,
        ]);
    }

    public function test_companies_can_be_updated_without_logo()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $newCompany = Company::factory()->for($user)->make()->makeHidden('logo');

        $response = $this->actingAs($user)->put("/api/companies/{$company->id}", $newCompany->toArray());

        $response->assertJson([
            'name' => $newCompany->name,
        ]);
    }

    public function test_companies_can_be_updated_with_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $logo = UploadedFile::fake()->image('image.jpg');

        $company = Company::factory()->for($user)->create([
            'logo' => "/images/{$logo->hashName()}",
        ]);

        $newCompany = Company::factory()->for($user)->make()->makeHidden('logo');

        $newLogo = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($user)->put(
            "/api/companies/{$company->id}", array_merge($newCompany->toArray(), ['logo' => $newLogo])
        );

        $response->assertJson([
            'name' => $newCompany->name,
        ]);

        Storage::disk('public')->assertMissing("/images/{$logo->hashName()}");
        Storage::disk('public')->assertExists("/images/{$newLogo->hashName()}");
    }

    public function test_companies_cant_be_updated_with_invalid_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $logo = UploadedFile::fake()->create('document.pdf');

        $response = $this->actingAs($user)->put("/api/companies/{$company->id}", [
            'logo' => $logo,
            'website' => ['wrong-website'],
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'logo', 'description', 'website', 'address', 'city',
        ]);

        $logo = UploadedFile::fake()->image('image.jpg')->size(2048);

        $response = $this->actingAs($user)->put("/api/companies/{$company->id}", [
            'country_id' => 'wrong-country',
            'name' => ['wrong-name'],
            'logo' => $logo,
            'description' => ['wrong-description'],
            'website' => 'wrong-website',
            'address' => Str::random(256),
            'city' => Str::random(256),
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'logo', 'description', 'website', 'address', 'city',
        ]);

        $newCompany = Company::factory()->for($user)->make();

        $response = $this->actingAs($user)->put("/api/companies/{$company->id}", [
            'country_id' => $newCompany->country_id,
            'name' => Str::random(256),
            'description' => $newCompany->description,
            'website' => Str::random(256),
            'address' => Str::random(256),
            'city' => Str::random(256),
        ]);

        $response->assertJsonValidationErrors(['name', 'website', 'address', 'city']);
    }

    public function test_companies_can_be_deleted()
    {
        $user = User::factory()->create();

        Storage::disk('public')->put($logo = '/images/fake-file.txt', 'fake-content');

        $company = Company::factory()->for($user)->create([
            'logo' => $logo,
        ]);

        Storage::disk('public')->assertExists($company->logo);

        $response = $this->actingAs($user)->delete("/api/companies/{$company->id}");

        $response->assertNoContent();

        Storage::disk('public')->assertMissing($company->logo);
    }
}
