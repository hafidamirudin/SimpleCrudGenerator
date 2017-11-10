<?php

namespace Tests\Generators;

use Tests\TestCase;

class LangGeneratorTest extends TestCase
{
    /** @test */
    public function it_creates_correct_model_lang_content()
    {
        $this->artisan('make:crud', ['name' => $this->model_name, '--no-interaction' => true]);

        $locale = config('app.locale');
        $langPath = resource_path('lang/'.$locale.'/'.$this->lang_name.'.php');
        $displayModelName = ucwords(str_replace('_', ' ', snake_case($this->model_name)));
        $this->assertFileExists($langPath);
        $langFileContent = "<?php

return [
    // Labels
    '{$this->lang_name}'     => '{$displayModelName}',
    'list'           => '{$displayModelName} List',
    'search'         => 'Search {$displayModelName}',
    'detail'         => '{$displayModelName} Detail',
    'not_found'      => '{$displayModelName} not found.',
    'empty'          => '{$displayModelName} is empty.',
    'back_to_show'   => 'Back to {$displayModelName} Detail',
    'back_to_index'  => 'Back to {$displayModelName} List',

    // Actions
    'create'         => 'Create new {$displayModelName}',
    'created'        => 'Create new {$displayModelName} succeded.',
    'show'           => 'View {$displayModelName} Detail',
    'edit'           => 'Edit {$displayModelName}',
    'update'         => 'Update {$displayModelName}',
    'updated'        => 'Update {$displayModelName} succeded.',
    'delete'         => 'Delete {$displayModelName}',
    'delete_confirm' => 'Are you sure to delete this {$displayModelName}?',
    'deleted'        => 'Delete {$displayModelName} succeded.',
    'undeleted'      => '{$displayModelName} not deleted.',
    'undeleteable'   => '{$displayModelName} data cannot be deleted.',

    // Attributes
    'name'           => '{$displayModelName} Name',
    'description'    => '{$displayModelName} Description',
];
";
        $this->assertEquals($langFileContent, file_get_contents($langPath));
    }

    /** @test */
    public function it_creates_correct_model_lang_content_based_on_locale_config()
    {
        config(['app.locale' => 'id']);
        $this->artisan('make:crud', ['name' => $this->model_name, '--no-interaction' => true]);

        $locale = config('app.locale');
        $langPath = resource_path('lang/'.$locale.'/'.$this->lang_name.'.php');
        $displayModelName = ucwords(str_replace('_', ' ', snake_case($this->model_name)));
        $this->assertFileExists($langPath);
        $langFileContent = "<?php

return [
    // Labels
    '{$this->lang_name}'     => '{$displayModelName}',
    'list'           => 'Daftar {$displayModelName}',
    'search'         => 'Cari {$displayModelName}',
    'detail'         => 'Detail {$displayModelName}',
    'not_found'      => '{$displayModelName} tidak ditemukan',
    'empty'          => 'Belum ada {$displayModelName}',
    'back_to_show'   => 'Kembali ke detail {$displayModelName}',
    'back_to_index'  => 'Kembali ke daftar {$displayModelName}',

    // Actions
    'create'         => 'Input {$displayModelName} Baru',
    'created'        => 'Input {$displayModelName} baru telah berhasil.',
    'show'           => 'Lihat Detail {$displayModelName}',
    'edit'           => 'Edit {$displayModelName}',
    'update'         => 'Update {$displayModelName}',
    'updated'        => 'Update data {$displayModelName} telah berhasil.',
    'delete'         => 'Hapus {$displayModelName}',
    'delete_confirm' => 'Anda yakin akan menghapus {$displayModelName} ini?',
    'deleted'        => 'Hapus data {$displayModelName} telah berhasil.',
    'undeleted'      => 'Data {$displayModelName} gagal dihapus.',
    'undeleteable'   => 'Data {$displayModelName} tidak dapat dihapus.',

    // Attributes
    'name'           => 'Nama {$displayModelName}',
    'description'    => 'Deskripsi {$displayModelName}',
];
";
        $this->assertEquals($langFileContent, file_get_contents($langPath));
    }

    /** @test */
    public function it_creates_app_lang_if_it_doesnt_exists()
    {
        $this->artisan('make:crud', ['name' => $this->model_name, '--no-interaction' => true]);

        $locale = config('app.locale');
        $langPath = resource_path('lang/'.$locale.'/app.php');

        $this->assertFileExists($langPath);
        $appLangContent = "<?php

return [
    // Labels
    'table_no'          => '#',
    'total'             => 'Total',
    'action'            => 'Actions',
    'views'             => 'Views',
    'downloads'         => 'Downloads',
    'show_detail_title' => 'View :name :type detail',

    // Actions
    'show'           => 'View Detail',
    'edit'           => 'Edit',
    'delete'         => 'Delete',
    'cancel'         => 'Cancel',
    'reset'          => 'Reset',
    'delete_confirm' => 'Are you sure to delete this?',
    'delete_confirm_button' => 'YES, delete it!',
];
";
        $this->assertEquals($appLangContent, file_get_contents($langPath));
    }
}
