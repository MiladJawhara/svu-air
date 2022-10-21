<?php

use App\Http\Controllers\QuestionsController;
use App\Models\Question;
use App\Utilities\BooleanParser;
use App\Utilities\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return [
        "milad" => 'HAHAH'
    ];
});

Route::get('/search', function () {
    $text = request('text');
    $parsed = BooleanParser::pars($text);
    return Question::whereIn('id', $parsed->calculate())->get()->map(function (Question $item) use ($parsed) {
        foreach ($parsed->getKeywords() as $keyword) {
            $item->text = Helper::hilightWordInText($item->text, $keyword);
            $item->answer = Helper::hilightWordInText($item->answer, $keyword);
        }
        return $item;
    });
});

Route::get('/question/{question}', [QuestionsController::class, "show"]);
Route::post('/questions', [QuestionsController::class, "store"]);
Route::put('/question/{question}', [QuestionsController::class, "update"]);
