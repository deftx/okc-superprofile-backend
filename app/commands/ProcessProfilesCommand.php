<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProcessProfilesCommand extends Command {

    protected $minResult = 3;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'process-profiles';
    protected $profiles = array();
    protected $results = array();

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process profiles in the queue.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
        $profiles = RawProfile::findLatest();

        $profiles->each(function ($p) {
            $profile = json_decode($p->json_data);

            $this->profiles[] = $profile;
        });

        $this->getWordCountForAllEssays();
        $this->getWordCountForEachEssay();
        $this->printResults();
	}

    protected function getWordCountForAllEssays()
    {
        $allEssays = '';

        foreach ($this->profiles as $p) {
            foreach ($p->essays as $e) {
                $allEssays .= " " . $e->text;
            }
        }

        $all = Deft\Words::wordCount($allEssays, $this->minResult);
        arsort($all);

        $this->results['all'] = $all;
    }

    protected function getWordCountForEachEssay()
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
            $result = Deft\Words::wordCount($text, $this->minResult);

            arsort($result);

            $this->results['each'][$num] = $result;
        }
    }

    protected function getWordCountForEachColor()
    {
        $colors = array(

        );

        foreach ($this->profiles as $p) {
            foreach($this->essays as $e) {
                print_r($e);
            }
        }
    }


    protected function printResults()
    {
        print_r($this->results);
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}