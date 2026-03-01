<?php

namespace App\Services;

use Sastrawi\Stemmer\StemmerFactory;

class TextPreprocessingService
{
    private array $stopwords = [
        'dan','yang','di','ke','dari','untuk','dengan','atau','ini','itu','pada','dalam','sebagai',
        'adalah','karena','agar','oleh','jadi','lebih','sangat','akan','dapat','bisa','juga','serta',
        'kopi'
    ];

    public function preprocess(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\\s]/', ' ', $text) ?? '';
        $tokens = preg_split('/\\s+/', trim($text)) ?: [];

        $factory = new StemmerFactory();
        $stemmer = $factory->createStemmer();

        $terms = [];
        foreach ($tokens as $token) {
            if ($token === '' || in_array($token, $this->stopwords, true)) {
                continue;
            }
            $terms[] = $stemmer->stem($token);
        }

        return $terms;
    }
}
