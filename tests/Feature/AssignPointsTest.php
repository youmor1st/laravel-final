<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\DisciplineRule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignPointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_assign_points()
{
    $teacher = User::factory()->create(['role' => 'teacher']);

    $class = SchoolClass::create(['name' => '10A']);

    $studentUser = User::factory()->create(['role' => 'student']);

    $student = Student::create([
        'user_id' => $studentUser->id,
        'class_id' => $class->id,
        'current_points' => 10,
    ]);

    $rule = DisciplineRule::create([
        'name' => 'Good',
        'points' => 5,
        'type' => 'reward',
        'is_active' => true,
    ]);

    $response = $this->actingAs($teacher)->post(
        route('teacher.assign'),
        [
            'mode' => '2', // 👈 обязательно
            'student_id' => $student->id,
            'rule_ids' => [$rule->id],
        ]
    );

    $response->assertStatus(302);

    $this->assertDatabaseHas('point_histories', [
        'student_id' => $student->id,
        'points' => 5,
    ]);
}
    public function test_student_cannot_assign_points()
{
    $student = User::factory()->create(['role' => 'student']);

    $response = $this->actingAs($student)->post(
        route('teacher.assign'),
        []
    );

    // скорее всего редирект или отказ
    $response->assertStatus(403);
}
public function test_penalty_decreases_points()
{
    $teacher = User::factory()->create(['role' => 'teacher']);

    $class = SchoolClass::create(['name' => '10A']);

    $studentUser = User::factory()->create(['role' => 'student']);

    $student = Student::create([
        'user_id' => $studentUser->id,
        'class_id' => $class->id,
        'current_points' => 10,
    ]);

    $rule = DisciplineRule::create([
        'name' => 'Late',
        'points' => -3,
        'type' => 'penalty',
        'is_active' => true,
    ]);

    $this->actingAs($teacher)->post(
        route('teacher.assign'),
        [
            'mode' => '2',
            'student_id' => $student->id,
            'rule_ids' => [$rule->id],
        ]
    );

    $this->assertEquals(7, $student->fresh()->current_points);
}
public function test_guest_redirected_to_login()
{
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
}
}