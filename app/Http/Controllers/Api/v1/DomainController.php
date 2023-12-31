<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Domain;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Resources\DomainCollection;
use App\Http\Resources\DomainResource;
use App\Models\DomainItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
			$noItemsPerPage = $request->limit ? $request->limit : 10;

			$orderBy = !empty($request->sortBy) ? $request->orderBy: 'desc';
			$sortBy = !empty($request->sortBy) ? $request->sortBy: 'domain_name';
			$filterBy = !empty($request->filterBy) ? $request->filterBy : 'domain_name';
			$searchKey = $request->filter;

			if ($searchKey) {
				$domains = Domain::where('user_id', $user->id)
					->where($filterBy, 'like', '%' . $searchKey . '%')
					->orderBy($sortBy, $orderBy) 
					->paginate($noItemsPerPage);
			} else {
				$domains = Domain::where('user_id', $user->id)
					->orderBy($sortBy, $orderBy)
					->paginate($noItemsPerPage);
			}

			return response()->json([
				'succes' => true,
				'domains' => $domains
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
			$message = '';

			$currentDateTime = Carbon::now();
			$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

			$userDomainsCount = Domain::where('user_id', $user->id)->count();
			$package = $user->package;

			if (!$package) {
				return response()->json([
					'success' => false,
					'error' => 'Package unavailable.'
				], JsonResponse::HTTP_ACCEPTED);
			}

			if ($userDomainsCount < $package->end_limit) {
				foreach ($domains as $domain) {
					if ($this->isValidDomain($domain)) {
						$count = Domain::where('domain_name', $domain)->count();
						if ($count < 1) {
							array_push($domainsArr, [
								'user_id' => $user->id,
								'domain_name' => strtolower($domain),
								'no_of_items' => 0,
								'created_at' => $formattedDateTime,
								'updated_at' => $formattedDateTime,
							]);
							$totalAddedDomains = $userDomainsCount + count($domainsArr);

							if ($totalAddedDomains >= $package->end_limit) {
								$message = ' Cannot add more domains. Upgrade your plan to add more domains.';
								break;
							}
						}
					}
				}
			} else if ($request->add_domain === true) { 
				foreach ($domains as $domain) {
					if ($this->isValidDomain($domain)) {
						$count = Domain::where('domain_name', $domain)->count();
						if ($count < 1) {
							array_push($domainsArr, [
								'user_id' => $user->id,
								'domain_name' => strtolower($domain),
								'no_of_items' => 0,
								'created_at' => $formattedDateTime,
								'updated_at' => $formattedDateTime,
							]);
							$totalAddedDomains = $userDomainsCount + count($domainsArr);
						}
					}
				}
			} else {
				return response()->json([
					'success' => false,
					'error' => 'Unable to add more domains. Upgrade your plan to add more domains.'
				], JsonResponse::HTTP_ACCEPTED);
			}

			if (count($domainsArr) > 0) {
				$isSaved = Domain::insert($domainsArr);

				if ($isSaved) {
					//Call create invoice vnoc.
					InvoiceController::create($user->id);

					$message = count($domainsArr) . ' out of ' . count($domains) . (count($domainsArr) > 1 ? ' domains are saved.' : ' domain is saved.') . $message;
					return response()->json([
						'success' => $isSaved,
						'message' =>  $message,
					], JsonResponse::HTTP_OK);
				} else {
					return response()->json([
						'success' => false,
						'error' => 'Unable to save domains. Please try again!'
					], JsonResponse::HTTP_ACCEPTED);
				}
			} else {
				return response()->json([
					'success' => false,
					'error' => count($domains) ? 'The domains already exist.' : 'The domain is already exists.'
				], JsonResponse::HTTP_ACCEPTED);
			}
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'error' => $e->getMessage()
			], JsonResponse::HTTP_ACCEPTED);
		}
	}

	public function domainStats(Request $request)
	{
		try {
			$user = $request->user();
			$domainsCount = Domain::where('user_id', $user->id)->count();
			$hitsCount = Domain::where('user_id', $user->id)->where('no_of_items', '>', 0)->count();
			$noHitsCount = Domain::where('user_id', $user->id)->where('no_of_items', 0)->count();
			$domainsAtRiskCount = $this->domainsAtRiskCount($user->id);
			$investorSpaceCount = Domain::where('is_cron', '=', 1)
							 ->where('user_id', '=', $user->id)
							 ->count();

			return response()->json([
				'success' => true,
				'data' => [
					'domainsCount' => $domainsCount,
					'hitsCount' => $hitsCount,
					'noHitsCount' => $noHitsCount,
					'domainsAtRiskCount' => $domainsAtRiskCount,
					'investorSpaceCount' => $investorSpaceCount,
				]
			], JsonResponse::HTTP_OK);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage()
			], JsonResponse::HTTP_ACCEPTED);
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
			$count = $this->domainsAtRiskCount($user->id);

			return response()->json([
				'success' => true,
				'domains' => $count
			], JsonResponse::HTTP_OK);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function domainsAtRisk($userId)
	{
		Domain::where('user_id', $userId)->with('items')->get();

		$filteredDomains = Domain::whereHas('items', function ($query) {
			$query->where('status_label', 'like', '%pending%')
				->where('registration_number', '0000000')
				->where('status_definition', 'like', '%NEW%');
		})->get();

		return DomainResource::collection($filteredDomains);
	}

	private function domainsAtRiskCount($userId)
	{

		Domain::where('user_id', $userId)->with('items')->get();

		$count = Domain::whereHas('items', function ($query) {
			$query->where('status_label', 'like', '%pending%')
				->where('registration_number', '0000000')
				->where('status_definition', 'like', '%NEW%');
		})->count();

		return $count;
	}

	public function getDomainsWithHits (Request $request)
	{
		try {
			$user = $request->user();

			$noItemsPerPage = $request->limit ? $request->limit : 10;
			$orderBy = !empty($request->sortBy) ? $request->orderBy: 'desc';
			$sortBy = !empty($request->sortBy) ? $request->sortBy: 'domain_name';
			$filterBy = !empty($request->filterBy) ? $request->filterBy : 'domain_name';
			$searchKey = $request->filter;

			if ($searchKey) {
				$domains = Domain::where('user_id', $user->id)
					->where('no_of_items', '>', 0)
					->where($filterBy, 'like', '%' . $searchKey . '%')
					->orderBy($sortBy, $orderBy) 
					->paginate($noItemsPerPage);
			} else {
				$domains = Domain::where('user_id', $user->id)
					->where('no_of_items', '>', 0)
					->orderBy($sortBy, $orderBy)
					->paginate($noItemsPerPage);
			}

			return response()->json([
				'succes' => true,
				'domains' => $domains
			], JsonResponse::HTTP_OK);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function getDomainsWithoutHits (Request $request)
	{
		try {
			$user = $request->user();
			$noItemsPerPage = $request->limit ? $request->limit : 10;
			$orderBy = !empty($request->sortBy) ? $request->orderBy: 'desc';
			$sortBy = !empty($request->sortBy) ? $request->sortBy: 'domain_name';
			$filterBy = !empty($request->filterBy) ? $request->filterBy : 'domain_name';
			$searchKey = $request->filter;

			if ($searchKey) {
				$domains = Domain::where('user_id', $user->id)
					->where('no_of_items', '=', 0)
					->where($filterBy, 'like', '%' . $searchKey . '%')
					->orderBy($sortBy, $orderBy) 
					->paginate($noItemsPerPage);
			} else {
				$domains = Domain::where('user_id', $user->id)
					->where('no_of_items', '=', 0)
					->orderBy($sortBy, $orderBy)
					->paginate($noItemsPerPage);
			}

			return response()->json([
				'succes' => true,
				'domains' => $domains
			], JsonResponse::HTTP_OK);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function getDomainItems(Request $request, $domainId) {
		try {
			$noItemsPerPage = $request->limit ? $request->limit : 10;
			$orderBy = !empty($request->sortBy) ? $request->orderBy: 'desc';
			$sortBy = !empty($request->sortBy) ? $request->sortBy: 'domain_id';
			$filterBy = !empty($request->filterBy) ? $request->filterBy : 'keyword';
			$searchKey = $request->filter;

			if ($searchKey) {
				$items = DomainItem::with('domain')
                    ->where('domain_id', '=', $domainId)
					->where($filterBy, 'like', '%' . $searchKey . '%')
					->orderBy($sortBy, $orderBy) 
					->paginate($noItemsPerPage);
			} else {
				$items = DomainItem::with('domain')
                    ->where('domain_id', '=', $domainId)
					->orderBy($sortBy, $orderBy)
					->paginate($noItemsPerPage);
			}

			return response()->json([
				'succes' => true,
				'items' => $items
			], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
				'succes' => false,
				'message' => $e->getMessage()
			], JsonResponse::HTTP_OK);
        }
	}

	public function destroy(Request $request) {
		try {
			$validatedData = $request->validate([
				'domains' => 'required|array',
			]);

			$status = Domain::whereIn('id', $validatedData['domains'])->delete();

			return response()->json([
				'success' => true
			], JsonResponse::HTTP_OK);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function historicalHits () : JsonResponse
	{
		try {
			$results = DB::table('domain_items')
			->selectRaw('COUNT(*) as my_y, SUBSTRING(DATE_FORMAT(`updated_at`, "%M"), 1, 3) as my_month, YEAR(`updated_at`) as the_year')
			->selectRaw('CONCAT(SUBSTRING(DATE_FORMAT(`updated_at`, "%M"), 1, 3), " ", YEAR(`updated_at`)) as my_x')
			->groupBy('the_year', 'my_month', 'my_x') // Include all selected columns in the GROUP BY clause
			->get();

			return response()->json([
				'success' => true,
				'data' => $results
			], JsonResponse::HTTP_OK);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
