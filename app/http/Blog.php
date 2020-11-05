<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BlogCategory;
use Illuminate\Support\Str;
use Carbon;
class Blog extends Model {

    protected $fillable = [
        'title',
        'therapist_id',
        'user_id',
        'description',
        'category_id',
        'featured_image',
        'blog_slug',
        'is_featured',
        'blog_author',
        'status'];
    protected $appends = ['blog_author_name', 'blog_author_slug','custom_date','total_likes'];

public function getTotalLikesAttribute()
 {
    return $this->hasMany('App\Comment')->where('blog_id',$this->id)->count();

 }

    public function getBlogAuthorNameAttribute() {
        if ($this->blog_author == 'Admin') {
            return $this->user->name;
        }
        return $this->therapist->name;
    }

    public function getBlogAuthorSlugAttribute() {
        if ($this->blog_author == 'Admin') {
            return Str::slug($this->user->name);
        }
        return Str::slug($this->therapist->name);
    }
public function getCustomDateAttribute(){
return $this->created_at->isoFormat('MMMM Y');
}
    /**
     * Get blog categories.
     */
    public function blogCategories() {
        return $this->belongsToMany(BlogCategory::class, 'cat_blog', 'blog_id', 'cat_id');
    }

    /**
     * Belongs to therapist
     */
    public function therapist() {
        return $this->belongsTo('App\Therapiest');
    }

    /**
     * 
     * Belong to admin
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
     public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
