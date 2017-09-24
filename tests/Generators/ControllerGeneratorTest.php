<?php

namespace Tests\Generators;

use Tests\TestCase;

class ControllerGeneratorTest extends TestCase
{
    /** @test */
    public function it_creates_correct_controller_class_content()
    {
        $this->artisan('make:crud', ['name' => $this->modelName, '--no-interaction' => true]);

        $this->assertFileExists(app_path("Http/Controllers/{$this->pluralModelName}Controller.php"));
        $ctrlClassContent = "<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    /**
     * Display a listing of the item.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \$editableItem = null;
        \$items = Item::paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            \$editableItem = Item::find(request('id'));
        }

        return view('items.index', compact('items', 'editableItem'));
    }

    /**
     * Store a newly created item in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request)
    {
        \$this->validate(\$request, [
            'name' => 'required|max:60',
            'description' => 'required|max:255',
        ]);

        Item::create(\$request->only('name', 'description'));

        flash(trans('item.created'), 'success');

        return redirect()->route('items.index');
    }

    /**
     * Update the specified item in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\Item  \$item
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, Item \$item)
    {
        \$this->validate(\$request, [
            'name' => 'required|max:60',
            'description' => 'required|max:255',
        ]);

        \$item = \$item->update(\$request->only('name', 'description'));

        flash(trans('item.updated'), 'success');

        return redirect()->route('items.index');
    }

    /**
     * Remove the specified item from storage.
     *
     * @param  \App\Item  \$item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item \$item)
    {
        \$this->validate(request(), [
            'item_id' => 'required',
        ]);

        if (request('item_id') == \$item->id && \$item->delete()) {
            flash(trans('item.deleted'), 'success');

            return redirect()->route('items.index');
        }

        flash(trans('item.undeleted'), 'error');

        return back();
    }
}
";
        $this->assertEquals($ctrlClassContent, file_get_contents(app_path("Http/Controllers/{$this->pluralModelName}Controller.php")));
    }
}