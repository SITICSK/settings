<?php

namespace Sitic\Settings\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sitic\Settings\Http\Models\Setting;
use Sitic\Settings\Http\Resources\SettingResource;


/**
 * @group Settings API
 *
 * APIs for settings
 */
class SettingController extends Controller
{

    /**
     * Index
     *
     * This endpoint lets you list a settings categories.
     * @authenticated
     * @queryParam perPage integer List settings per page. Defaults to '10'. Example: 30
     *
     */
    public function index(Request $request) {
        $this->validate($request, [
            'perPage' => 'integer'
        ]);

        try {
            return SettingResource::collection(Setting::paginate($request->perPage ?? config('settings.perPage', 10)));
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Store
     *
     * This endpoint lets you create a new settings category.
     * @authenticated
     * @bodyParam title string required Setting category name. Example: System
     * @bodyParam description string Setting category description. Example: Base project settings.
     * @urlParam id string required The setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'string|required|max:255|min:3',
            'description' => 'min:3|max:1000'
        ]);

        $fillable = $request->only(['title', 'description']);

        try {
            $setting = Setting::create($fillable);
            return response()->json(['status' => 'success', 'data' => new SettingResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Show
     *
     * This endpoint lets you show a settings category.
     * @authenticated
     * @urlParam id string required The setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     * @queryParam with string Get relations. Example: settingItems
     *
     */
    public function show(Request $request, $id) {
        try {
            $request->with ? $with = explode(',', $request->with) : $with = [];
            $setting = Setting::findOrFail($id);
            foreach ($with as $relation) {
                if ($setting->$relation) {
                    $setting = $setting->with($relation);
                }
            }
            return response()->json(['status' => 'success', 'data' => new SettingResource($setting->first())]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }


    /**
     * Update
     *
     * This endpoint lets you update a settings category.
     * @authenticated
     * @urlParam id string required The setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     * @bodyParam title string Setting category name. Example: System
     * @bodyParam description string Setting category description. Example: Base project settings.
     *
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'title' => 'string|max:255|min:3',
            'description' => 'min:3|max:1000'
        ]);

        $fillable = $request->only(['title', 'description']);

        try {
            $setting = Setting::findOrFail($id);
            if (!empty($fillable)) {
                $setting->update($fillable);
            }
            return response()->json(['status' => 'success', 'data' => new SettingResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Destroy
     *
     * This endpoint lets you destroy a settings category.
     * @authenticated
     * @urlParam id string required The setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function destroy(Request $request, $id) {
        try {
            $setting = Setting::findOrFail($id);
            $setting->delete($setting);
            return response()->json(['status' => 'success', 'data' => new SettingResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Restore
     *
     * This endpoint lets you restore deleted settings category.
     * @authenticated
     * @urlParam id string required The setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function restore(Request $request, $id) {
        try {
            $setting = Setting::withTrashed()->findOrFail($id)->restore();
            return response()->json(['status' => 'success', 'data' => new SettingResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Force Delete
     *
     * This endpoint lets you force delete deleted settings category.
     * @authenticated
     * @urlParam id string required The setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function forceDelete(Request $request, $id) {
        try {
            $setting = Setting::withTrashed()->findOrFail($id)->forceDelete();
            return response()->json(['status' => 'success', 'data' => new SettingResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }
}
