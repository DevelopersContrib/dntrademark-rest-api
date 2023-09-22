<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Package;

class PackageController extends Controller
{
	//
	public function index() {
		$packages = Package::all();

		return response()->json([
			'success' => true,
			'data' => [
				'data' => $packages,
				'success' => true,
				'error' => ''
			],
		], 200);
	}
}
