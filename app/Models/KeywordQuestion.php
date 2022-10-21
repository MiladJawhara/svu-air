<?php

namespace App\Models;

use App\Constants\DBConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = DBConstants::TABLE_KEYWORDS_QUESTIONS;
    protected $primary_key = [];
}
