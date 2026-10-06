<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_search_matches_title_and_skills_and_owner(): void
    {
        $owner = User::factory()->create(['name' => 'Alice Developer']);
        $project = Project::factory()->create([
            'owner_id' => $owner->id,
            'title' => 'E-Commerce Platform',
            'description' => 'Building online store',
            'category' => 'Development',
            'status' => 'active',
        ]);

        $skill = Skill::create(['name' => 'VueJS', 'category' => 'Frontend']);
        $project->skills()->attach($skill->id);

        $viewer = User::factory()->create();

        // 1. Search by skill name
        $response1 = $this->actingAs($viewer)->get(route('projects.index', ['search' => 'VueJS']));
        $response1->assertOk();
        $response1->assertSee('E-Commerce Platform');

        // 2. Search by owner name
        $response2 = $this->actingAs($viewer)->get(route('projects.index', ['search' => 'Alice']));
        $response2->assertOk();
        $response2->assertSee('E-Commerce Platform');

        // 3. Search by title keyword
        $response3 = $this->actingAs($viewer)->get(route('projects.index', ['search' => 'Commerce']));
        $response3->assertOk();
        $response3->assertSee('E-Commerce Platform');

        // 4. Empty search returns all projects
        $response4 = $this->actingAs($viewer)->get(route('projects.index', ['search' => '']));
        $response4->assertOk();
        $response4->assertSee('E-Commerce Platform');
    }

    public function test_live_search_endpoint_returns_json_results(): void
    {
        $user = User::factory()->create(['name' => 'Bob Marley', 'major' => 'Informatics']);
        $project = Project::factory()->create([
            'title' => 'Music Streaming App',
            'category' => 'Mobile',
            'status' => 'active',
        ]);
        Skill::create(['name' => 'Kotlin', 'category' => 'Mobile']);

        $response = $this->actingAs($user)->getJson(route('search.live', ['q' => 'Music']));
        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Music Streaming App']);

        $userSearch = $this->actingAs($user)->getJson(route('search.live', ['q' => 'Bob']));
        $userSearch->assertOk();
        $userSearch->assertJsonFragment(['name' => 'Bob Marley']);

        $shortQuery = $this->actingAs($user)->getJson(route('search.live', ['q' => 'a']));
        $shortQuery->assertOk();
        $shortQuery->assertExactJson(['projects' => [], 'users' => [], 'skills' => []]);
    }
}
