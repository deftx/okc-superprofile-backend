<?php

class RawProfileController extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $profiles = RawProfile::findLatest();

        return $profiles;
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('crawlData');

        if (empty($input['crawlData'])) {
            return array('msg' => 'empty');
        }

        try {
            foreach ($input['crawlData'] as $k=>$v) {
                $rawprofile = new RawProfile;

                if (!empty($v['user'])) {
                    $rawprofile->username = $v['user'];
                    $rawprofile->json_data = json_encode($v);
                    $rawprofile->save();
                }
            }

            return array('msg'=>'stored');
        } catch (Exception $e) {
            return array('msg'=>'not_stored', 'e' => json_encode($e));
        }
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return RawProfile::find($id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        RawProfile::find($id)->delete();
	}

}