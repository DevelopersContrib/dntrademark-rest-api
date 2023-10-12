<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Domain;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Resources\DomainResource;
use Illuminate\Http\JsonResponse;

class DomainController extends Controller
{
	private function isValidDomain($domain)
	{
		return preg_match('/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/', $domain) ? true : false;
	}

	public function index(Request $request)
	{
		try {
			$user = $request->user();
			$domains = Domain::with('items')->where('user_id', $user->id)->get();

			return response()->json([
				'succes' => true,
				'domains' => DomainResource::collection($domains)
			]);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function storeDomains(StoreDomainRequest $request)
	{
		try {
			$user = $request->user();
			$domains = explode(',', $request->input('domains'));
			$domains = array_map('trim', $domains);
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
						'message' => count($domainsArr) > 1 ? 'Domains saved.' : 'Domain is saved.'
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
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'error' => $e->getMessage()
			], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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

	public function getDomainsAtRisk(Request $request)
	{
		try {
			$user = $request->user();
			$domains = Domain::where('user_id', $user->id)->with('items')->get();

			$filteredDomains = Domain::whereHas('items', function ($query) {
				$query->where('status_label', 'like', '%pending%')
					->where('registration_number', '0000000')
					->where('status_definition', 'like', '%NEW%');
			})->get();

			return response()->json([
				'success' => true,
				'domains' => DomainResource::collection($filteredDomains)
			], JsonResponse::HTTP_OK);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
