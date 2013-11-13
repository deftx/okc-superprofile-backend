<?php

namespace Deft;

class Words
{
    // "ive", "i", "im", "you", "me", "we", "us", "your", "youre", "people"
    static protected $stopWords = array("like", "dont" , "thi", "a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also", "although", "always", "am", "among", "amongst", "amoungst", "amount", "an", "and", "another", "any", "anyhow", "anyone", "anything", "anyway", "anywhere", "are", "around", "as", "at", "back", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom", "but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven", "else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own", "part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "very", "via", "was", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "yourself", "yourselves", "the");

    static public function wordCount($text, $minResult = 0) {
        $text = self::cleanText($text);
        $words = explode(" ", $text);
        $wordCount = array();

        foreach ($words as $word) {
            $word = self::cleanWord($word);

            // Is this a stop word?
            if (!strlen($word) || in_array($word, self::$stopWords)) {
                continue;
            }

            if (!isset($wordCount[$word])) {
                $wordCount[$word] = 1;
            } else {
                $wordCount[$word] += 1;
            }
        }

        if ($minResult > 0) {
            foreach($wordCount as $k=>$v) {
                if ($v < $minResult) {
                    unset($wordCount[$k]);
                }
            }
        }
        return $wordCount;
    }

    static protected function cleanText($text)
    {
        $text = strip_tags($text);
        $text = preg_replace("/[\s]+/", " ", $text);
        $text = preg_replace("/[^A-Za-z ]+/", "", $text);

        return $text;
    }

    static protected function cleanWord($word)
    {
        $word = strtolower($word);
        $word = self::depluralize($word);
        $word = self::removeAdverb($word);

        return $word;
    }

    /**
     * Hackish way to remove adverb
     *
     * @param $word
     * @return mixed
     */
    static protected function removeAdverb($word) {
        if (preg_match("/ly$/", $word)) {
            return '';
        }

        return $word;
    }
    static protected function depluralize($word)
    {
        // Here is the list of rules. To add a scenario,
        // Add the plural ending as the key and the singular
        // ending as the value for that key. This could be
        // turned into a preg_replace and probably will be
        // eventually, but for now, this is what it is.
        //
        // Note: The first rule has a value of false since
        // we don't want to mess with words that end with
        // double 's'. We normally wouldn't have to create
        // rules for words we don't want to mess with, but
        // the last rule (s) would catch double (ss) words
        // if we didn't stop before it got to that rule.
        $rules = array(
            'ss' => false,
            'os' => 'o',
            'ies' => 'y',
            'xes' => 'x',
            'oes' => 'o',
            'ies' => 'y',
            'ves' => 'f',
            's' => '');
        // Loop through all the rules and do the replacement.
        foreach (array_keys($rules) as $key) {
            // If the end of the word doesn't match the key,
            // it's not a candidate for replacement. Move on
            // to the next plural ending.
            if (substr($word, (strlen($key) * -1)) != $key)
                continue;
            // If the value of the key is false, stop looping
            // and return the original version of the word.
            if ($key === false)
                return $word;
            // We've made it this far, so we can do the
            // replacement.
            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }
        return $word;
    }
}