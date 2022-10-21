<?php

namespace App\Models;

use App\Utilities\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;

/**
 * @property int $id
 * @property string $text
 * @property string $answer
 * @property string $keywords_extracted_at
 */
class Question extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function extractKeywords(bool $force = false){

        if(!isNull($this->keywords_extracted_at) && !$force) return;

        $text = $this->text . " " . $this->answer;

        $keywords = explode(" ", Helper::cleanStrForExtractKeywords($text));

        foreach ($keywords as $keyword) {
            $oldQuestion = Keyword::where(['text' => $keyword])->first();
            if (!$oldQuestion) {
                $newKeyword = Keyword::create([
                    'text' => $keyword,
                    'language_id' => $this->language_id,
                    'frequency' => 1,
                ]);
                $newKeyword->addQuestion($this->id);
            } else {
                $oldQuestion->frequency++;
                $oldQuestion->save();
                $oldQuestion->addQuestion($this->id);
            }
        }

        $this->keywords_extracted_at = Carbon::now();
    }


}
