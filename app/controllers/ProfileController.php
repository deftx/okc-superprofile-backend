<?php

class ProfileController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $crawlData = Input::get('crawlData');

        if (!$crawlData) {
            throw new Exception('fuck');
        }

        foreach ($crawlData as $k => $v) {
            if (isset($v['essays'])) {
                foreach ($v['essays'] as $essay) {
                }
            }
        }

        arsort($this->words);
        foreach ($this->words as $word => $num) {
            echo str_repeat($word . " ", $num);
        }

        return;
        return $this->words;


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}