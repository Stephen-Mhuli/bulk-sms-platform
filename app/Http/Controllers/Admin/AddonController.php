<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AddonController extends Controller
{
    public function index()
    {
        return view('admin.addon.index');
    }

    public function getAll()
    {
        $modules = \Module::all();
        $newArray = [];
        foreach ($modules as $module) {
            $newArray[] = [
                'name' => $module->getName(),
                'status' => $module->isEnabled(),
            ];
        }


        return datatables()->of($newArray)
            ->addColumn('title', function ($q) {
                return $q['name'];
            })
            ->addColumn('status', function ($q) {
                return $q['status'] == true ? 'enabled' : 'disabled';
            })
            ->addColumn('action', function ($q) {
                $status = '';
                if ($q['status'] == true) {
                    $status = '<button class="btn btn-sm btn-info" data-message="Are you sure you want to disable this module?"
                                        data-action=' . route('admin.addon.change-status') . '
                                        data-input={"_method":"post","name":"' . $q['name'] . '","status":"disable"}
                                        data-toggle="modal" data-target="#modal-confirm">Disable</button> ';
                } else if ($q['status'] == false) {
                    $status = ' <button class="btn btn-sm btn-success" data-message="Are you sure you want to enable this module?"
                                        data-action=' . route('admin.addon.change-status') . '
                                        data-input={"_method":"post","name":"' . $q['name'] . '","status":"enable"}
                                        data-toggle="modal" data-target="#modal-confirm">Enable</button> ';
                }

                return $status . ' <button class="btn btn-sm btn-danger" data-message="Are you sure you want to uninstall this module?"
                                        data-action=' . route('admin.addon.uninstall') . '
                                        data-input={"_method":"delete","name":"' . $q['name'] . '"}
                                        data-toggle="modal" data-target="#modal-confirm">Uninstall</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function uninstall(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $module = \Module::find($request->name);
        if (!$module) return redirect()->back()->withErrors(['msg' => trans('Invalid request')]);
        $module->delete();

        return redirect()->back()->with('success', trans('Module uninstalled successfully'));

    }


    public function changeStatus(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|in:enable,disable'
        ]);

        $module = \Module::find($request->name);
        if (!$module) return redirect()->back()->withErrors(['msg' => trans('Invalid request')]);

        if ($request->status == 'enable') {
            $module->enable();
            \Artisan::call("module:publish " . $request->name);
            \Artisan::call("module:update");
        } else if ($request->status == 'disable') {
            $module->disable();
        }

        return redirect()->back()->with('success', trans('Module status successfully changed'));
    }

    public function import()
    {
        //    echo phpinfo();
        //   exit();
        return view('admin.addon.import');
    }

    public function importPost(Request $request)
    {

        $request->validate([
            'addon' => 'required|mimes:zip'
        ]);
        $search_this = [
            'composer.json',
            'Config',
            'Controllers',
            'module.json',
            'package.json',
            'Resources',
            'views',
            'web.php',
            'webpack.mix.js',
        ];
        if ($request->hasFile('addon')) {
            $file = $request->file('addon');
            $path = \Module::getPath();
            $zip = new \ZipArchive();
            $res = $zip->open($file);
            if ($res === TRUE) {
                $fileNames = [];
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    $fileNames[] = basename($stat['name']);
                }

                $containsAllValues = !array_diff($search_this, $fileNames);
                if (!$containsAllValues) {
                    return redirect()->back()->withErrors(['msg' => trans('Invalid module selected')]);
                }


                $zip->extractTo($path);
                $zip->close();
                return redirect()->route('admin.addon.index')->with('success', trans('Module uploaded successfully'));
            } else {
                return redirect()->back()->withErrors(['msg' => trans('Invalid module')]);
            }
        }
    }
}
