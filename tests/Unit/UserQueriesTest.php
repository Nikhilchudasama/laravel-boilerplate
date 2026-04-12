<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;
use App\Domain\Users\UserQueries;

beforeEach(function (): void {
    seedRoles();
    $this->userQueries = new UserQueries;
});

test('list query returns paginated results', function (): void {
    User::factory()->count(15)->create();

    $result = $this->userQueries->listQuery([
        'per_page' => 10,
    ]);

    expect($result->total())->toBeGreaterThanOrEqual(15)
        ->and($result->perPage())->toBe(10);
});

test('list query can filter by search text', function (): void {
    User::factory()->create(['name' => 'John Unique Name']);
    User::factory()->create(['name' => 'Jane Different']);

    $result = $this->userQueries->listQuery([
        'search_text' => 'Unique',
        'per_page' => 10,
    ]);

    expect($result->total())->toBe(1);
});

test('list query can sort results', function (): void {
    User::factory()->create(['name' => 'Zebra']);
    User::factory()->create(['name' => 'Alpha']);

    $result = $this->userQueries->listQuery([
        'sort_by' => 'name',
        'sort_direction' => 'asc',
        'per_page' => 10,
    ]);

    expect($result->first()->name)->toBe('Alpha');
});
