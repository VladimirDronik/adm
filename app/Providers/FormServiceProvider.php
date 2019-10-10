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
            self::PATH.'text', ['name', 'label', 'value' => null, 'attributes' => [], 'help' => null, 'col' => 3]);
        \Form::component('bs_textarea',
            self::PATH.'textarea', ['name', 'label', 'value' => null, 'attributes' => ['rows' => 3], 'help' => null, 'col' => 3]);
        \Form::component('bs_label',
            self::PATH.'label', ['name', 'label', 'is_required' => false, 'col' => 3, 'class' => null]);
        \Form::component('bs_title',
            self::PATH.'title', ['title']);
        \Form::component('bs_hr',
            self::PATH.'hr', ['margin' => 40]);
        \Form::component('bs_submit_btn',
            self::PATH.'submit_btn', ['label' => 'Сохранить', 'col' => 3, 'class' => 'btn btn-success p-l-30 p-r-30']);
        \Form::component('bs_select',
            self::PATH.'select', ['name', 'label', 'values' => null, 'selected' => null, 'attributes' => [], 'help' => null, 'col' => 3]);
        \Form::component('bs_field_help',
            self::PATH.'field_help', ['help' => null]);
        \Form::component('bs_field_error',
            self::PATH.'field_error', ['name' => null, 'error' => null]);
        \Form::component('bs_number',
            self::PATH.'number', ['name', 'label', 'value' => null, 'attributes' => ['min' => 0], 'help' => null, 'col' => 3]);
        \Form::component('bs_error',
            self::PATH.'error', ['name' => null]);
        \Form::component('bs_success',
            self::PATH.'success', ['name' => null]);
        \Form::component('bs_alert',
            self::PATH.'alert', ['name' => null, 'is_success' => true]);
        \Form::component('bs_radio',
            self::PATH.'radio', ['name', 'label', 'values', 'checked_key' => null, 'attributes' => [], 'help' => null, 'col' => 3]);
        \Form::component('bs_hidden',
            self::PATH.'hidden', ['name', 'value' => '']);
        \Form::component('bs_image',
            self::PATH.'image', ['prefix', 'label', 'value', 'col' => 3]);
        \Form::component('bs_autoselect',
            self::PATH.'autoselect', ['name', 'label', 'values' => null, 'selected' => null, 'show_id' => false,
                'multiple' => false, 'attributes' => [], 'multiple_id' => null, 'help' => null, 'col' => 3]);
        \Form::component('bs_checkbox',
            self::PATH.'checkbox', ['name', 'label', 'is_checked' => false, 'attributes' => [], 'help' => null, 'col' => 3]);
        \Form::component('bs_color',
            self::PATH.'color', ['name', 'label', 'value', 'attributes' => [], 'help' => null, 'col' => 3]);
        \Form::component('bs_simple_text',
            self::PATH.'simple_text', ['label' => null, 'value' => null, 'name' => null, 'help' => null, 'col' => 3]);
        \Form::component('bs_password',
            self::PATH.'password', ['name', 'label', 'attributes' => [], 'help' => null, 'col' => 3]);
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
