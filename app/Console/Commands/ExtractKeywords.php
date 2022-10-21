<?php

namespace App\Console\Commands;

use App\Models\Keyword;
use App\Models\Question;
use App\Utilities\Helper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExtractKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $questions = Question::where(['keywords_extracted_at' => null])->get()->all();
        foreach ($questions as $question) {
            $question->extractKeywords();
        }
        return 0;
    }
}
