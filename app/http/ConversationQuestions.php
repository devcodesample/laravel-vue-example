<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationQuestions extends Model
{
   protected $fillable = ['question','answered','answered_by','status'];
public $timestamps = true;
}
