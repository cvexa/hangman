<?php

namespace App\Services;


use App\Models\Statistic;

class GameService
{
    const maxAttemptsAllowed = 5;

    public static function startGame(int $wordId, string $string, string $description, int $category): bool
    {
        session()->start();
        session()->put([
            'wordId' => $wordId,
            'word' => $string,
            'maskedWordDescription' => $description,
            'maskedWord' => self::maskInitialWord($string),
            'attempts' => 0,
            'fullAttempt' => 0,
            'letters' => [],
            'usedLetters' => [],
            'victory' => 0,
            'category' => $category,
        ]);
        session()->save();

        return true;
    }

    public static function refreshGame(): string
    {
        return self::revealLetterPosition();
    }

    private static function maskInitialWord(string $string): string
    {
        $stringArr = explode(' ', $string);
        foreach ($stringArr as $pos => $word) {
            $lastNumber = mb_strlen($word) - 2;
            $stringArr[$pos] = mb_substr($word, 0, 1) . str_repeat(" _ ", $lastNumber) . mb_substr($word, -1);
        }
        return implode(' ', $stringArr);
    }

    public static function getWord(): string|bool
    {
        return session()->get('word') ?? false;
    }

    public static function getMaskedWord(): string|bool
    {
        return !is_null(session()->get('maskedWord')) ? session()->get('maskedWord') : false;
    }

    public static function getMaskedWordDescription(): string|bool
    {
        return session()->get('maskedWordDescription') ?? false;
    }

    public static function getAlphabet(): array
    {
        return ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж ', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ь', 'Ю', 'Я'];
    }

    public static function isGameStarted(int $categoryId): bool
    {
        if ($categoryId != session()->get('category')) {
            self::clearSession();
            return false;
        }
        return session()->has('maskedWord') ?? false;
    }

    public static function checkAttempts(): bool|int
    {
        $currentAttempt = session()->get('attempts') + 1;
        session()->put('attempts', $currentAttempt);
        session()->save();

        if ($currentAttempt > self::maxAttemptsAllowed) {
            session()->put('attempts', 5);
            session()->save();
            return false;
        }
        return $currentAttempt;
    }

    public static function checkLetterPosition(string $char): bool
    {
        $char = mb_strtolower($char);
        session()->push('usedLetters', $char);
        session()->save();
        $theWord = mb_strtolower(self::getWord());
        if (mb_stripos($theWord, $char) || str_contains($theWord, $char)) {
            session()->push('letters', $char);
            session()->save();
            return true;
        }
        return false;
    }

    public static function revealLetterPosition(): string
    {
        $words = explode(' ', session()->get('word'));
        $newWord = '';

        foreach ($words as $word) {
            $splitWord = mb_str_split($word);
            foreach ($splitWord as $letterPos => $letter) {
                if ($letterPos === array_key_first($splitWord) || $letterPos === array_key_last($splitWord)) {
                    $newWord .= $letter;
                    $letterPos === array_key_last($splitWord) ? $newWord .= ' ' : null;
                    continue;
                }
                $correctLetters = session()->get('letters');
                if (!in_array($letter, $correctLetters)) {
                    $newWord .= ' _ ';
                } else {
                    $newWord .= $letter;
                }
            }
        }

        if(self::checkWholeWord($newWord)){
            session()->put('victory', 1);
            session()->save();
        }

        return $newWord;
    }

    public static function isWinner($clear = false): bool
    {
        $isVictory = session()->get('victory') == 1;
        if ($clear) {
            self::endGame();
        }
        return $isVictory;
    }

    public static function checkWholeWord(string $word = '', $fullAttempt = false): bool
    {
        if (empty($word)) {
            return false;
        }

        $gameWord = mb_strtolower(str_replace(' ', '', session()->get('word')));
        $word = mb_strtolower(str_replace(' ', '', $word));
        //the words don't match
        if (strcmp($gameWord, $word) !== 0) {
            return false;
        }

        if (session()->get('attempts') < 1) {
            session()->put('attempts', 1);
        }

        session()->put('victory', 1);
        if ($fullAttempt) {
            session()->put('fullAttempt', 1);
            self::endGame();
        }

        return true;
    }

    public static function endGame(): bool
    {
        if (session()->get('userId')) {
            $insertStatistic = new Statistic();
            $insertStatistic->user_id = session()->get('userId');
            $insertStatistic->word_id = session()->get('wordId');
            $insertStatistic->attempts = session()->get('attempts') ?? 0;
            $insertStatistic->victory = session()->get('victory') ?? 0;
            $insertStatistic->full_attempt = session()->get('fullAttempt') ?? 0;
            $insertStatistic->save();
        }
        self::clearSession();

        return true;
    }

    public static function clearSession()
    {
        session()->forget([
            'userId',
            'maskedWord',
            'wordDescription',
            'word',
            'attempts',
            'fullAttempt',
            'letters',
            'usedLetters',
            'victory',
            'category'
        ]);
    }
}
