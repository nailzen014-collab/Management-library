<?php

use App\Models\author;
use App\Models\buku;
use App\Models\kategori;
use App\Models\kelas;
use App\Models\siswa;
use App\Models\User;
use Database\Seeders\DemoUserSeeder;

function registerApiUser($test): array
{
    $response = $test->postJson('/api/v1/register', [
        'name' => 'API Tester',
        'email' => 'api.tester@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();

    return [
        'user' => User::query()->where('email', 'api.tester@example.com')->firstOrFail(),
        'token' => $response->json('data.token'),
    ];
}

test('users can register and receive a bearer token', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'API Tester',
        'email' => 'api.tester@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.user.email', 'api.tester@example.com')
        ->assertJsonPath('data.token_type', 'Bearer')
        ->assertJsonStructure(['data' => ['user', 'token', 'token_type']]);

    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
});

test('users can login and receive a bearer token', function () {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'password',
    ]);

    $this->postJson('/api/v1/login', [
        'email' => 'login@example.com',
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('data.user.email', 'login@example.com')
        ->assertJsonStructure(['data' => ['user', 'token', 'token_type']]);
});

test('the demo user seeder creates an account that can login', function () {
    app(DemoUserSeeder::class)->run();

    $this->postJson('/api/v1/login', [
        'email' => 'api.tester@example.com',
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('data.user.name', 'API Tester')
        ->assertJsonStructure(['data' => ['user', 'token', 'token_type']]);
});

test('protected endpoints require a bearer token', function () {
    $this->getJson('/api/v1/buku')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Unauthenticated.');
});

test('authenticated users can create and list books', function () {
    ['token' => $token] = registerApiUser($this);

    $author = author::create(['nama_author' => 'Eko Kurniawan']);
    $kategori = kategori::create(['nama_kategori' => 'Programming']);

    $this->withToken($token)
        ->postJson('/api/v1/buku', [
            'judul' => 'Belajar OpenAPI',
            'stock' => 5,
            'author_id' => $author->id,
            'kategori_id' => [$kategori->id],
        ])
        ->assertCreated()
        ->assertJsonPath('data.judul', 'Belajar OpenAPI')
        ->assertJsonPath('data.author.nama_author', 'Eko Kurniawan')
        ->assertJsonPath('data.kategoris.0.nama_kategori', 'Programming');

    $this->withToken($token)
        ->getJson('/api/v1/buku?search=OpenAPI')
        ->assertOk()
        ->assertJsonPath('data.0.judul', 'Belajar OpenAPI')
        ->assertJsonPath('total', 1);
});

test('authenticated users can read a student detail with class', function () {
    ['token' => $token] = registerApiUser($this);

    $kelas = kelas::create(['nama_kelas' => 'XI RPL 1', 'tingkat' => 'XI']);
    $siswa = siswa::create([
        'nama' => 'Budi Santoso',
        'nis' => 12345,
        'kelas_id' => $kelas->id,
    ]);

    $this->withToken($token)
        ->getJson('/api/v1/siswa/'.$siswa->id)
        ->assertOk()
        ->assertJsonPath('data.nama', 'Budi Santoso')
        ->assertJsonPath('data.nis', 12345)
        ->assertJsonPath('data.kelas.nama_kelas', 'XI RPL 1');
});

test('book payload validation returns 422', function () {
    ['token' => $token] = registerApiUser($this);

    $this->withToken($token)
        ->postJson('/api/v1/buku', [
            'judul' => '',
            'stock' => -1,
            'author_id' => 999,
            'kategori_id' => [],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['judul', 'stock', 'author_id', 'kategori_id']);
});

test('a class with students cannot be deleted', function () {
    ['token' => $token] = registerApiUser($this);

    $kelas = kelas::create(['nama_kelas' => 'XI RPL 2', 'tingkat' => 'XI']);
    siswa::create([
        'nama' => 'Siti Aminah',
        'nis' => 67890,
        'kelas_id' => $kelas->id,
    ]);

    $this->withToken($token)
        ->deleteJson('/api/v1/kelas/'.$kelas->id)
        ->assertStatus(409)
        ->assertJsonPath('message', 'Kelas tidak bisa dihapus karena masih punya siswa terkait.');
});

test('dashboard returns library statistics', function () {
    ['token' => $token] = registerApiUser($this);

    $author = author::create(['nama_author' => 'Penulis']);
    $kategori = kategori::create(['nama_kategori' => 'Fiksi']);
    $kelas = kelas::create(['nama_kelas' => 'X RPL 1', 'tingkat' => 'X']);

    buku::create([
        'judul' => 'Buku Contoh',
        'stock' => 3,
        'author_id' => $author->id,
    ]);

    siswa::create([
        'nama' => 'Siswa Contoh',
        'nis' => 11111,
        'kelas_id' => $kelas->id,
    ]);

    $this->withToken($token)
        ->getJson('/api/v1/dashboard')
        ->assertOk()
        ->assertJsonPath('data.stats.total_buku', 1)
        ->assertJsonPath('data.stats.total_author', 1)
        ->assertJsonPath('data.stats.total_kategori', 1)
        ->assertJsonPath('data.stats.total_siswa', 1)
        ->assertJsonPath('data.stats.total_kelas', 1);
});
