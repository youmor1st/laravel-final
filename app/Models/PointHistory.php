<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'rule_id',
        'teacher_id',
        'points',
        'balance_before',
        'balance_after',
        'comment',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function rule()
    {
        return $this->belongsTo(DisciplineRule::class, 'rule_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
