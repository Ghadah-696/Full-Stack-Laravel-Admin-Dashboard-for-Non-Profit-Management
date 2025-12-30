<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Project;

class Impact extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'required_amount',
        'raised_amount',
        'progress_percentage', // 💡 أُضيف حديثاً
        'goal_ar',
        'goal_en',
        'reached_ar',
        'reached_en',
        'status',
    ];

    // تعريف العلاقة: كل سجل أثر يخص مشروع واحد فقط
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
