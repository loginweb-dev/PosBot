<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        $packages = Package::latest()
                        ->paginate(20);

        return view('superadmin.packages.index')
            ->with(compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        $lists = Package::list();

        return view('superadmin.packages.create')
            ->with(compact('lists'));
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
            if ($request->ajax()) {
                $input = $request->only('name', 'description', 'no_of_active_forms', 'price_interval', 'interval', 'price', 'sort_order', 'is_active', 'is_form_downloadable');
            
                if (empty($input['is_active'])) {
                    $input['is_active'] = 0;
                }
                if (empty($input['is_form_downloadable'])) {
                    $input['is_form_downloadable'] = 0;
                }
                if (empty($input['no_of_active_forms'])) {
                    $input['no_of_active_forms'] = 0;
                }
                if (empty($input['price'])) {
                    $input['price'] = 0;
                }

                Package::create($input);

                $package_dashboard_url['redirect'] = action('Superadmin\PackageController@index');

                $output = $this->respondSuccess(__('messages.saved_successfully'), $package_dashboard_url);
            }
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
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (!empty($id)) {
            $package = Package::find($id);
            $lists = Package::list();
            return view('superadmin.packages.edit')
                ->with(compact('package', 'lists'));
        }
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
        try {
            if ($request->ajax()) {
                $input = $request->only('name', 'description', 'no_of_active_forms', 'price_interval', 'interval', 'price', 'sort_order', 'is_active', 'is_form_downloadable');
            
                if (empty($input['is_active'])) {
                    $input['is_active'] = 0;
                }
                if (empty($input['is_form_downloadable'])) {
                    $input['is_form_downloadable'] = 0;
                }
                if (empty($input['no_of_active_forms'])) {
                    $input['no_of_active_forms'] = 0;
                }
                if (empty($input['price'])) {
                    $input['price'] = 0;
                }

                Package::where('id', $id)
                    ->update($input);

                $package_dashboard_url['redirect'] = action('Superadmin\PackageController@index');
                
                $output = $this->respondSuccess(__('messages.updated_successfully'), $package_dashboard_url);
            }
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }

        return $output;
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
            Package::destroy($id);
            $output = $this->respondSuccess(__('messages.deleted_successfully'));
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }
}
