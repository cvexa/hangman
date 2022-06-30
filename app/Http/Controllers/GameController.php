<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Statistic;
use App\Models\Word;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();

        return view('pages.select_category', ['categories' => $categories]);
    }


    public function startGame($categoryId): View
    {
        if (!GameService::isGameStarted($categoryId)) {
            session()->put('userId', Auth::id());
            session()->save();
            $getRandomWord = Word::where('category_id', $categoryId)->inRandomOrder()->first();
            GameService::startGame($getRandomWord->id, $getRandomWord->word, $getRandomWord->description, $categoryId);
        }
        $refreshedWord = GameService::refreshGame();
        $maskedWord = GameService::getMaskedWord();
        $alphabet = GameService::getAlphabet();
        $wordDescription = GameService::getMaskedWordDescription();

        return view('pages.game', ['maskedWord' => $maskedWord, 'wordDescription' => $wordDescription, 'alphabet' => $alphabet, 'refreshedWord' => $refreshedWord]);
    }

    public function characterCheck(Request $request): array
    {
        $char = $request->char;
        $checkAttemptsCount = GameService::checkAttempts();

        if ($checkAttemptsCount) {
            $checkLetterPosition = GameService::checkLetterPosition($char);
            if ($checkLetterPosition) {
                $revealedWord = GameService::revealLetterPosition();
                $isWinner = GameService::isWinner();
                if ($isWinner) {
                    GameService::endGame();
                }
                return ['word' => $revealedWord, 'redirect' => $isWinner, 'isWinner' => $isWinner];
            }
            return ['word' => false, 'redirect' => false, 'isWinner' => GameService::isWinner()];
        }
        return ['word' => false, 'redirect' => true, 'isWinner' => GameService::isWinner('clear')];

    }

    public function checkWholeWord(Request $request): array
    {
        $checkWholeWord = GameService::checkWholeWord($request->fullText, true);

        return ['word' => false, 'redirect' => true, 'isWinner' => $checkWholeWord];
    }

    public function viewStatistics(): View
    {
        GameService::clearSession();
        $userStats = Statistic::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);

        return view('pages.statistic', ['userStats' => $userStats]);
    }
}
