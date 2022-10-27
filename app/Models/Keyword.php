<?php

namespace App\Models;

use App\Utilities\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\isNull;

class Keyword extends Model
{
    use HasFactory;

    protected $guarded = [];

    private static array $_extractedKeyWords = [];

    public function addQuestion($questionId)
    {
        $old = KeywordQuestion::where([
            'keyword_id' => $this->id,
            'question_id' => $questionId,
        ])->first();

        if (!$old) {
            KeywordQuestion::create([
                'keyword_id' => $this->id,
                'question_id' => $questionId,
                'frequency' => 1,
            ]);
        } else {
            $old->frequency++;
            $old->save();
        }
    }

    public function questions()
    {
        return $this->hasManyThrough(Question::class, KeywordQuestion::class);
    }


    /**
     * @param Question $question
     * @param bool $force
     * @return void
     */
    public static function extractKeywords(Question $question, bool $force = false): void
    {
        if (!isNull($question->keywords_extracted_at) && !$force) return;

        $text = $question->text . " " . $question->answer;

        $keywords = explode(" ", Helper::cleanStrForExtractKeywords($text));
        $oldKeywords = Keyword::whereIn('text', $keywords)->get();
        foreach ($keywords as $keyword) {
            $oldKeyword = $oldKeywords->where('text', '=', $keyword)->first();
            if (!$oldKeyword) {
                $newKeyword = Keyword::create([
                    'text' => $keyword,
                    'language_id' => $question->language_id,
                    'frequency' => 1,
                ]);
                $newKeyword->addQuestion($question->id);
                $oldKeywords->add($newKeyword);
            } else {
                $oldKeyword->frequency++;
                $oldKeyword->addQuestion($question->id);
            }
        }

        $oldKeywords->each(function (Keyword $keyword) {
            $keyword->save();
        });

        $question->keywords_extracted_at = Carbon::now();
    }

}
