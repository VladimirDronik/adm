<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * App\Models\Script
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name Название скрипта
 * @property string $link ссылка на скрипт в папке скрипты
 * @property int|null $count количество раз, которое выполнился скрипт
 * @property int $system
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereSystem($value)
 * @property-read string $code
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Method[] $systemMethods
 * @property-read int|null $system_methods_count
 */
class Script extends Model
{
    const LINK_PATH = '/';
    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * @param string $code
     * @throws Exception
     */
    public function storeCodeToFile(string $code, string $link = '')
    {
        if ($link === '') {
            $name = mb_strtolower($this->name, 'UTF-8');
            $name = preg_replace('/\s\s+/', ' ', $name);
            $name = translitRussian($name);
            $name = str_replace(' ', '_', $name);
            $filename = $name . '.php';
        } else {
            $name = pathinfo($link,PATHINFO_FILENAME);
            $filename = $link;
        }

        $count = 1;
        while (Storage::disk('scripts')->exists(self::LINK_PATH . $filename)) {
            $filename = $name.'_'.$count.'.php';
            $count++;
            if ($count > 1000) {
                throw new Exception('Не удалось сохранить файл');
            }
        }

        Storage::disk('scripts')->put(self::LINK_PATH . $filename, $code);

        $this->link = $filename;
    }

    public function updateCodeToFile(string $code)
    {
        if ($this->isLinkExists()) {
            Storage::disk('scripts')->put(self::LINK_PATH . $this->link, $code);
        } elseif (empty($this->link)) {
            $this->storeCodeToFile($code);
        } else {
            $this->storeCodeToFile($code, $this->link);
        }
    }

    public function isLinkExists()
    {
        return !empty($this->link) && Storage::disk('scripts')->exists(self::LINK_PATH . $this->link);
    }

    public function deleteFile()
    {
        Storage::disk('scripts')->delete(self::LINK_PATH .$this->link);
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getCodeAttribute()
    {
        if (!$this->isLinkExists()) {
            return '';
        }

        return Storage::disk('scripts')->get(self::LINK_PATH . $this->link);
    }

    /* attributes */
    public function systemMethods() {

        return $this->hasMany(Method::class, 'script', 'id')->where('is_system', 1);
    }


}
