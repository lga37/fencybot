<?php

namespace App\Http\Controllers;

use App\Fence;
use App\Device;
use App\FenceDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FenceDeviceController extends Controller
{

    public function index(Request $request)
    {
        $devices = Device::all();
        $fencedevices = FenceDevice::all();

        $fences = Fence::all();
        return view('drag.index', compact('fences', 'devices', 'fencedevices'));
    }



    public function store(Request $request)
    {
        $this->isValid($request);

        $fencedevice = new FenceDevice();

        $fencedevice->save();

        return back()->withSuccess('Record Inserted with Success');

    }


    public function update(Request $request, int $id)
    {
        $this->isValid($request);


        $fencedevice = FenceDevice::find($id);
        $fence_id = (int) $request->get('fence_id');
        $fencedevice->save();

        return back()->withSuccess('Record Updated with Success');;

    }


    public function destroy(FenceDevice $fencedevice)
    {
        $fencedevice->delete();
        return back()->withSuccess('Record Deleted with Success');
    }


    private function isValid(Request $request){

        $request->only(['name', ]);

        $request->validate([
            'name' => 'required|min:2',
        ]);
        return $request;
    }

}
