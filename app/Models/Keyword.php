<?php

namespace App\Models;

use App\Constants\DBConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function addQuestion($questionId) {
       $old =  KeywordQuestion::where([
            'keyword_id' => $this->id,
            'question_id' => $questionId,
        ])->first();

        if(!$old){
            KeywordQuestion::create([
                'keyword_id'=>$this->id,
                'question_id'=>$questionId,
                'frequency' => 1,
            ]);
        } else {
            $old->frequency++;
            $old->save();
        }
    }

    public function questions(){
        return $this->hasManyThrough(Question::class,KeywordQuestion::class);
    }

}
