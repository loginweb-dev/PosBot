<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormDataComment;
use Illuminate\Support\Facades\View;
class FormDataCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->only('form_data_id', 'comment');
            $input['user_id'] = \Auth::id();

            $comment = FormDataComment::create($input);
            
            $comments[] = FormDataComment::with('commentedBy')
                            ->findOrFail($comment->id);

            //generate comment html
            $comment_html = View::make('form_data.partials.comment')
                            ->with(compact('comments'))
                            ->render();

            $output = $this->respondSuccess(__('messages.success'), ['comment' => $comment_html]);
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $form_data_id = request()->input('form_data_id');
            $comment = FormDataComment::where('form_data_id', $form_data_id)
                        ->findOrFail($id);
            $comment->delete();
            
            $output = $this->respondSuccess();
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }
}
