<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project_reports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'report_date',
        'user_project_id',
        'agent_id',
    ];

    const GOOGLE_SEARCH_TYPE = 'google_search';
    const GOOGLE_IMAGES_TYPE = 'google_image';
    const SOCIAL_MEDIA_TYPE = 'social_media';
    const AT_SOURCE_TYPE = 'at_source';

    public function project()
    {
        return $this->hasOne(UserProject::class, 'id', 'user_project_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function googleSearchReports()
    {
        return $this->hasMany(ReportContent::class, 'report_id')->where('type', ProjectReport::GOOGLE_SEARCH_TYPE);
    }

    public function googleImagesReports()
    {
        return $this->hasMany(ReportContent::class, 'report_id')->where('type', ProjectReport::GOOGLE_IMAGES_TYPE);
    }

    public function socialMediaReports()
    {
        return $this->hasMany(ReportContent::class, 'report_id')->where('type', ProjectReport::SOCIAL_MEDIA_TYPE);
    }

    public function atSourceReports()
    {
        return $this->hasMany(ReportContent::class, 'report_id')->where('type', ProjectReport::AT_SOURCE_TYPE);
    }
}
