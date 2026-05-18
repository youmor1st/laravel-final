<?php

use Tests\TestCase;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\DisciplineRule;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_points_are_added_correctly()
    {
        $service = app(PointService::class);

        $class = SchoolClass::create(['name' => '10A']);

        $user = User::factory()->create();
        $student = Student::create([
            'user_id' => $user->id,
            'class_id' => $class->id,
            'current_points' => 10,
        ]);

        $rule = DisciplineRule::create([
            'name' => 'Good',
            'points' => 5,
            'type' => 'reward',
            'is_active' => true,
        ]);

        $service->assignPoints(
            studentIds: [$student->id],
            ruleIds: [$rule->id],
            comment: '',
            actorUser: $user,
            actorRole: 'teacher',
        );

        $this->assertEquals(15, $student->fresh()->current_points);
    }
}