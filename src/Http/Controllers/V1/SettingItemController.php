<?php

namespace Sitic\Settings\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Sitic\Settings\Http\Models\SettingItem;
use Sitic\Settings\Http\Resources\SettingItemResource;


/**
 * @group Settings Item API
 *
 * APIs for settings item
 */
class SettingItemController extends Controller
{

    /**
     * Index Settings Item
     *
     * This endpoint lets you list a settings items.
     * @authenticated
     * @queryParam perPage integer List setting items per page. Example: 30
     *
     */
    public function index(Request $request) {
        $this->validate($request, [
            'perPage' => 'integer'
        ]);

        try {
            return SettingItemResource::collection(SettingItem::paginate($request->perPage ?? config('settings.perPage', 10)));
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Store Settings Items
     *
     * This endpoint lets you create a new settings item.
     * @authenticated
     * @bodyParam setting_id string required Setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     * @bodyParam title string required Setting item title. Example: App name
     * @bodyParam name string required Setting item name. Example: app_name
     * @bodyParam tyoe string required Setting item type. Example: string
     * @bodyParam value json required Setting item value. Example: ["SITIC"]
     *
     */
    public function store(Request $request) {
        $this->validate($request, [
            'setting_id' => 'string|required|min:36|max:36|exists:Sitic\Settings\Http\Models\Setting,id',
            'title' => 'string|required|max:255',
            'name' => 'string|required|max:255|unique:setting_items,name',
            'type' => 'required|string|in:string,form,boolean,select,multiselect|max:255',
            'value' => 'required|json',
        ]);

        $fillable = $request->only(['setting_id', 'title', 'name', 'type', 'value']);
        $fillable['value'] = json_decode($request->value, true);

        try {
            $settingItem = SettingItem::create($fillable);
            Cache::forget('site_settings');
            return response()->json(['status' => 'success', 'data' => new SettingItemResource($settingItem)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Show Settings Item
     *
     * This endpoint lets you show a settings item.
     * @authenticated
     * @urlParam id string required The settings item UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function show(Request $request, $id) {
        try {
            $request->with ? $with = explode(',', $request->with) : $with = [];
            $setting = SettingItem::findOrFail($id);
            foreach ($with as $relation) {
                if ($setting->$relation) {
                    $setting = $setting->with($relation);
                }
            }
            return response()->json(['status' => 'success', 'data' => new SettingItemResource($setting->first())]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Update Settings Item
     *
     * This endpoint lets you update a settings item.
     * @authenticated
     * @bodyParam setting_id string Setting UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     * @bodyParam title string Setting item title. Example: App name
     * @bodyParam name string Setting item name. Example: app_name
     * @bodyParam tyoe string item type. Example: string
     * @bodyParam value json item value. Example: ["SITIC"]
     *
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'setting_id' => 'string|min:36|max:36|exists:Sitic\Settings\Http\Models\Setting,id',
            'title' => 'string|max:255',
            'name' => 'string|max:255|unique:setting_items,name',
            'type' => 'string|in:string,form,boolean,select,multiselect|max:255',
            'value' => 'json'
        ]);

        $fillable = $request->only(['setting_id', 'title', 'name', 'type', 'value']);

        try {
            $setting = SettingItem::findOrFail($id);
            if (!empty($fillable)) {
                if ($setting->update($fillable)) Cache::forget('site_settings');
            }
            return response()->json(['status' => 'success', 'data' => new SettingItemResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Destroy Settings Item
     *
     * This endpoint lets you destroy a settings item.
     * @authenticated
     * @urlParam id string required The settings item UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function destroy(Request $request, $id) {
        try {
            $setting = SettingItem::findOrFail($id);
            if ($setting->delete($setting)) Cache::forget('site_settings');
            return response()->json(['status' => 'success', 'data' => new SettingItemResource($setting)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Restore Settings Item
     *
     * This endpoint lets you restore deleted settings item.
     * @authenticated
     * @urlParam id string required The settings item UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function restore(Request $request, $id) {
        try {
            $model = SettingItem::withTrashed()->findOrFail($id);
            if ($model) $model->restore();
            return response()->json(['status' => 'success', 'data' => new SettingItemResource($model)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Force Delete Settings Item
     *
     * This endpoint lets you force delete deleted settings item.
     * @authenticated
     * @urlParam id string required The settings item UUID. Example: b97b3ab8-a2e7-4cb7-883e-c72ca10adcc8
     *
     */
    public function forceDelete(Request $request, $id) {
        try {
            $model = SettingItem::withTrashed()->findOrFail($id);
            if ($model) $model->forceDelete();
            return response()->json(['status' => 'success', 'data' => new SettingItemResource($model)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }
}
