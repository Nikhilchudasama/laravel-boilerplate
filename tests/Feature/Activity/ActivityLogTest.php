<?php

declare(strict_types=1);

use App\Domain\Activity\ActivityQueries;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->queries = new ActivityQueries;
    $this->user = User::factory()->create(['name' => 'John Doe']);

    // Create some activities
    activity('users')->causedBy($this->user)->log('created');
    activity('roles')->causedBy($this->user)->log('updated');

    // Create an old activity
    $oldActivity = activity('system')->log('cleanup');
    $oldActivity->created_at = now()->subDays(10);
    $oldActivity->save();
});

test('it can filter by log name', function (): void {
    $filterData = ['log_name' => 'users'];
    $results = $this->queries->listQuery($filterData);

    expect($results->total())->toBe(1);
    expect($results->first()->log_name)->toBe('users');
});

test('it can filter by causer id', function (): void {
    $filterData = ['causer_id' => $this->user->id];
    $results = $this->queries->listQuery($filterData);

    expect($results->total())->toBe(2);
});

test('it can filter by date range', function (): void {
    // Current activities
    $filterDataCurrent = [
        'date_from' => now()->subDay()->toDateString(),
        'date_to' => now()->addDay()->toDateString(),
    ];
    $resultsCurrent = $this->queries->listQuery($filterDataCurrent);
    expect($resultsCurrent->total())->toBe(3);

    // Old activity
    $filterDataOld = [
        'date_from' => now()->subDays(11)->toDateString(),
        'date_to' => now()->subDays(9)->toDateString(),
    ];
    $resultsOld = $this->queries->listQuery($filterDataOld);
    expect($resultsOld->total())->toBe(1);
    expect($resultsOld->first()->log_name)->toBe('system');
});

test('it can search by description or log name', function (): void {
    $filterData = ['search_text' => 'cleanup'];
    $results = $this->queries->listQuery($filterData);

    expect($results->total())->toBe(1);
    expect($results->first()->description)->toBe('cleanup');
});
