<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Domain;

use App\Http\Requests\StoreDomainRequest;

class DomainController extends Controller
{
	private function isValidDomain($domain)
	{
		return preg_match('/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/', $domain) ? true : false;
	}

	public function storeDomains(StoreDomainRequest $request)
	{
		try {
			$user = Auth::user();
			$domains = explode('\n', $request->input('domains'));
			$domain = array_map('trim', $domains);
			$domainsArr = [];

			foreach ($domains as $domain) {
				if ($this->isValidDomain($domain)) {
					$count = Domain::where('domain_name', $domain)->count();
					if ($count < 1) {
						array_push($domainsArr, [
							'user_id' => $user->id,
							'domain_name' => $domain,
							'no_of_items' => 0
						]);
					}
				}
			}

			if (count($domainsArr) > 0) {
				$isSaved = Domain::insert($domainsArr);

				if ($isSaved) {
					return response()->json([
						'success' => $isSaved,
					], 200);
				} else {
					return response()->json([
						'success' => true,
						'error' => 'Unable to save domains. Please try again!'
					], 422);
				}
			} else {
				return response()->json([
					'success' => true,
					'error' => count($domains) ? 'The domains already exist.' : 'The domain is already exists.'
				], 422);
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function countDomains()
	{
		try {
			$user = Auth::user();

			return response()->json([
				'success' => true,
				'data' => [
					'count' => Domain::where('user_id', $user->id)->count()
				]
			], 200);
		} catch (\Exception  $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}

	public function countHitsDomain()
	{
		try {
			$user = Auth::user();

			return response()->json([
				'success' => true,
				'data' => [
					'count' => Domain::where('user_id', $user->id)->where('no_of_items', '>', 0)->count()
				]
			], 200);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}

	public function countNoHitsDomains()
	{
		try {
			$user = Auth::user();

			return response()->json([
				'success' => true,
				'data' => [
					'count' => Domain::where('user_id', $user->id)->where('no_of_items', 0)->count()
				]
			], 200);
		} catch (\Exception  $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
}
