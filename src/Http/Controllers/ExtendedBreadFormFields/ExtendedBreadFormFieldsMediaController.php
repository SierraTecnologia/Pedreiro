<?php

namespace Support\Http\Controllers\ExtendedBreadFormFields;

use Exception;
use Facilitador\Http\Controllers\FacilitadorMediaController;
use Illuminate\Http\Request;
use Support\Facades\Support;

class ExtendedBreadFormFieldsMediaController extends FacilitadorMediaController
{
    public function remove(Request $request)
    {
        if ($request->get('multiple_ext')) {
            try {
                // GET THE SLUG, ex. 'posts', 'pages', etc.
                $slug = $request->get('slug');
    
                // GET image name
                $image = $request->get('image');
    
                // GET record id
                $id = $request->get('id');
    
                // GET field name
                $field = $request->get('field');
    
                // GET THE DataType based on the slug
                $dataType = Support::model('DataType')->where('slug', '=', $slug)->first();
    
                // Check permission
                Support::canOrFail('delete_'.$dataType->name);
    
                // Load model and find record
                $model = app($dataType->model_name);
                $data = $model::find([$id])->first();
    
                // Check if field exists
<<<<<<< HEAD
                if (!isset($data->{$field})) {
                    throw new Exception(__('pedreiro::generic.field_does_not_exist'), 400);
=======
                if (! isset($data->{$field})) {
                    throw new Exception(__('facilitador::generic.field_does_not_exist'), 400);
>>>>>>> 5c903fb258ad1626860a5f217c8b6b11e51d0b1b
                }
    
                // Check if valid json
                if (is_null(@json_decode($data->{$field}))) {
                    throw new Exception(__('pedreiro::json.invalid'), 500);
                }
                
                // Decode field value
                $fieldData = @json_decode($data->{$field}, true);
                foreach ($fieldData as $i => $single) {
                    // Check if image exists in array
                    if (in_array($image, array_values($single))) {
                        $founded = $i;
                    }
                }
<<<<<<< HEAD
                if(!isset($founded)) {
                    throw new Exception(__('pedreiro::media.image_does_not_exist'), 400);
=======
                if (! isset($founded)) {
                    throw new Exception(__('facilitador::media.image_does_not_exist'), 400);
>>>>>>> 5c903fb258ad1626860a5f217c8b6b11e51d0b1b
                }
                
                // Remove image from array
                unset($fieldData[$founded]);
    
                // Generate json and update field
                $data->{$field} = json_encode($fieldData);
                $data->save();
    
                return response()->json(
                    [
                    'data' => [
<<<<<<< HEAD
                       'status'  => 200,
                       'message' => __('pedreiro::media.image_removed'),
=======
                       'status' => 200,
                       'message' => __('facilitador::media.image_removed'),
>>>>>>> 5c903fb258ad1626860a5f217c8b6b11e51d0b1b
                    ],
                    ]
                );
            } catch (Exception $e) {
                $code = 500;
                $message = __('pedreiro::generic.internal_error');
    
                if ($e->getCode()) {
                    $code = $e->getCode();
                }
    
                if ($e->getMessage()) {
                    $message = $e->getMessage();
                }
    
                return response()->json(
                    [
                    'data' => [
                        'status' => $code,
                        'message' => $message,
                    ],
                    ],
                    $code
                );
            }
        } else {
            FacilitadorMediaController::remove($request);
        }
    }
}
