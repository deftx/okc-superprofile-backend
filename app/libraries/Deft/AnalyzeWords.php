<?php

namespace Deft;

class AnalyzeWords
{
    const MIN_RESULTS = 3;
    protected $profiles = array();
    protected $results = array();

    public function __construct()
    {
        $profiles = \RawProfile::findLatest();

        $profiles->each(function ($p) {
            $profile = json_decode($p->json_data);

            if (!empty($profile->essays)) {
                $this->profiles[] = $profile;
            }
        });

        $this->getWordCountForAllEssays();
        $this->getWordCountForEachEssay();
        $this->printResults();
    }

    public function getWordCountForAllEssays()
    {
        $allEssays = '';

        foreach ($this->profiles as $p) {
            foreach ($p->essays as $e) {
                $allEssays .= " " . $e->text;
            }
        }

        $all = Words::wordCount($allEssays, self::MIN_RESULTS);
        arsort($all);

        $this->results['all'] = $all;
    }

    public function getWordCountForEachEssay()
    {
        $essays = array();

        foreach ($this->profiles as $p) {
            foreach ($p->essays as $e) {
                if (empty($essays[$e->num])) {
                    $essays[$e->num] = '';
                }

                $essays[$e->num] .= " " . $e->text;
            }
        }

        foreach ($essays as $num => $text) {
            $result = Words::wordCount($text, self::MIN_RESULTS);

            arsort($result);

            $this->results['each'][$num] = $result;
        }
    }

    public function getWordCountForEachColor()
    {
        $colors = array(

        );

        foreach ($this->profiles as $p) {
            foreach($this->essays as $e) {
                print_r($e);
            }
        }
    }

    /**
     *
     * @return array
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     */
    public function printResults()
    {
        print_r($this->results);

        return;
    }

}