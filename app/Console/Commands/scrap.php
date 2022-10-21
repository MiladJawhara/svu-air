<?php

namespace App\Console\Commands;

use App\Constants\GlobalConstants;
use App\Models\Question;
use File;
use Goutte;
use Illuminate\Console\Command;
use Masterminds\HTML5\Parser\UTF8Utils;
use Nette\Utils\Json;
use PHPUnit\Util\Json as UtilJson;
use Symfony\Component\DomCrawler\Crawler;
use Weidner\Goutte\GoutteFacade;

class scrap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap';

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
        // $file = File::get('storage/pages/test2.html');
        $URL1 = "https://www.dubaitourism.gov.ae/ar/faqs";
        $URL2 = "https://www.dubaitourism.gov.ae/en/faqs";

        $this->scrap($URL1,GlobalConstants::LANGUAGE_AR);
        $this->scrap($URL2);
        return 0;
    }

    private function scrap($URL,$language = GlobalConstants::LANGUAGE_EN)
    {
        $crawler =  Goutte::request('GET', $URL);
        $i = 0;

        if (!is_dir("storage/json/")) {
            File::makeDirectory('storage/json/');
        }

        $crawler->filter("[type='application/ld+json']")->each(function ($node) use (&$i,$language) {
            if ($i > 0) {

                $data = json_decode($node->text(), true);
                $data = array_values(array_values($data)[2]);

                foreach ($data as $d) {
                    $questionHtml = $d['name'];
                    $crawler = new Crawler($questionHtml);
                    $question = $crawler->text();

                    $answerHTML = $d['acceptedAnswer']['text'];
                    $crawler = new Crawler($answerHTML);

                    $answer = $crawler->text();

                    Question::create([
                        'text' => $question,
                        'answer' => $answer,
                        'language_id' => $language,
                    ]);
                }
            }
            $i++;
        });
    }
}
