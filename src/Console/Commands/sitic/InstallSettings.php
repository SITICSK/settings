<?php

namespace Sitic\Settings\Console\Commands\sitic;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Sitic\Settings\Http\Models\Setting;
use Sitic\Settings\Http\Models\SettingItem;

class InstallSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitic:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install default settings.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Setting::count() > 0) {
            if (!$this->confirm(__('Some settings already exists. Do you want to continue?'), false)) {
                return;
            }
        }

        $this->installSettings();
    }

    private function installSettings() {
        $settingsList = config('settings.default');
        if (empty($settingsList)) {
            $this->error(__('Empty default list.'));
        }

        foreach ($settingsList as $settingArray) {
            $settingCategory = collect($settingArray)->only(['title', 'description']);
            $settingCategory = Validator::validate($settingCategory->toArray(), [
                'title' => 'string|required|max:255|min:3',
                'description' => 'min:3|max:1000'
            ]);

            $fillable = $settingCategory;
            $setting = Setting::create($fillable);;
            if (count($settingArray['items'])) {
                $this->createSettingItems($setting, $settingArray['items']);
            }
        }
    }

    private function createSettingItems($setting, $items) {
        foreach ($items as $item) {
            $settingItem = SettingItem::create([
                'setting_id' => $setting['id'],
                'title' => $item['title'],
                'name' => $item['name'],
                'type' => $item['type'],
                'value' => $item['value'],
            ]);
        }
    }
}
