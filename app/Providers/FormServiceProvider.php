<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    const PATH = 'components.form.';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Form::component('bs_text',
            self::PATH.'text', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null]);
//        \Form::component('bs_simple_text',
//            self::PATH.'simple_text', ['label' => null, 'value' => null, 'name' => 'is_active', 'help' => null]);
//        \Form::component('bs_textarea',
//            self::PATH.'textarea', ['name', 'label', 'value' => null, 'attributes' => ['rows' => 3], 'help' => null]);
//        \Form::component('bs_full_textarea',
//            self::PATH.'full_textarea', ['name', 'label', 'value' => null, 'attributes' => ['rows' => 3], 'help' => null]);
//        \Form::component('bs_email',
//            self::PATH.'email', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null]);
//        \Form::component('bs_checkbox',
//            self::PATH.'checkbox', ['name', 'label', 'is_checked' => false, 'attributes' => [], 'help' => null]);
//        \Form::component('bs_submit_btn',
//            self::PATH.'submit_btn', ['label' => 'Сохранить', 'class' => 'btn btn-primary btn-sm']);
//        \Form::component('bs_select',
//            self::PATH.'select', ['name', 'label', 'values' => null, 'selected' => null, 'attributes' => [], 'help' => null]);
//        \Form::component('bs_tree_select',
//            self::PATH.'tree_select', ['name', 'label', 'values' => null, 'selected' => null, 'attributes' => []]);
//        \Form::component('bs_autoselect',
//            self::PATH.'autoselect', ['name', 'label', 'values' => null, 'selected' => null, 'show_id' => false,
//                'multiple' => false, 'attributes' => [], 'multiple_id' => null]);
//        \Form::component('bs_clock',
//            self::PATH.'clock', ['name', 'label', 'value' => null, 'attributes' => []]);
//        \Form::component('bs_file',
//            self::PATH.'file', ['name', 'label', 'id' => 1, 'value' => null, 'attributes' => [], 'mb0' => false]);
//        \Form::component('bs_img',
//            self::PATH.'img', ['label', 'value' => null, 'is_round' => true, 'name' => null, 'attributes' => []]);
//        \Form::component('bs_simple_file',
//            self::PATH.'simple_file', ['name', 'label', 'attributes' => [], 'help' => null]);
//        \Form::component('bs_simple_date',
//            self::PATH.'simple_date', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null]);
//        \Form::component('bs_error',
//            self::PATH.'error', ['name' => null]);
//        \Form::component('bs_success',
//            self::PATH.'success', ['name' => null]);
//        \Form::component('bs_alert',
//            self::PATH.'alert', ['name' => null, 'is_success' => true]);
//        \Form::component('bs_password',
//            self::PATH.'password', ['name', 'label', 'attributes' => [], 'help' => null]);
//        \Form::component('bs_date',
//            self::PATH.'date', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null, 'id' => null]);
//        \Form::component('bs_typeahead',
//            self::PATH.'typeahead', ['name', 'label', 'value' => null, 'attributes' => [],
//                'help' => 'Вводите ID, ФИО или email — автопоиск предложит варианты']);
//        \Form::component('bs_url',
//            self::PATH.'url', ['label', 'value' => null, 'href' => null, 'help' => null]);
//        \Form::component('bs_number',
//            self::PATH.'number', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null]);
//        \Form::component('bs_phone',
//            self::PATH.'phone', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
