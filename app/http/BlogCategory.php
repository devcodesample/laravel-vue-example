<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model {

    protected $fillable = ['name', 'blog_cat_slug', 'description', 'status'];
    protected $appends = ['blog_count'];

    public function getBlogCountAttribute() {
        return $this->blogCount();
    }

    /**
     * Get blog of this categories.
     */
    public function blogs() {
        return $this->belongsToMany(Blog::class, 'cat_blog', 'cat_id', 'blog_id');
    }

    /**
     * Get blog of this categories.
     */
    public function blogCount() {
        return $this->belongsToMany(Blog::class, 'cat_blog', 'cat_id', 'blog_id')->count();
    }

    /**
     * Belongs to therapist
     */
    public function therapist() {
        return $this->belongsTo('App\Therapiest');
    }

    /**
     * 
     * Belong to Admin
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

}
