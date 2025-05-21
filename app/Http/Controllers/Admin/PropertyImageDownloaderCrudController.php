<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyImageDownloaderRequest;
use App\Models\Property;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PropertyImageDownloaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PropertyImageDownloaderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Property::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/property-image-downloader');
        CRUD::setEntityNameStrings('property image downloader', 'property image downloaders');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->addColumns([
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text'
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text'
            ],
        ]);

        CRUD::addButtonFromModelFunction('line', 'open_google', 'openGoogle', 'beginning');

    }

    public function download(string $unique_id) {
        $property = Property::where('property_code', $unique_id)->first();

        // create new archive
        $zipFile = new \PhpZip\ZipFile();
        try{
            foreach ($property->getImageUrls() as $image) {
                $zipFile
                    ->addFile(public_path($image)); // add an entry from the file
            }

            $zipFile->outputAsAttachment($property->property_code . ".zip"); // save the archive to a file

        }
        catch(\PhpZip\Exception\ZipException $e){
            // handle exception
        }
        finally{
            $zipFile->close();
        }

    }

}
