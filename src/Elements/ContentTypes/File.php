<?php

namespace Pedreiro\Elements\ContentTypes;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends BaseType
{
    /**
     * @return false|null|string
     */
    public function handle()
    {
        if (! $this->request->hasFile($this->row->field)) {
            return;
        }

        $files = Arr::wrap($this->request->file($this->row->field));

        $filesPath = [];
        $path = $this->generatePath();

        foreach ($files as $file) {
            $filename = $this->generateFileName($file, $path);
            $file->storeAs(
                $path,
                $filename.'.'.$file->getClientOriginalExtension(),
                \Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk', 'public')
            );

            array_push(
                $filesPath,
                [
                'download_link' => $path.$filename.'.'.$file->getClientOriginalExtension(),
                'original_name' => $file->getClientOriginalName(),
                ]
            );
        }

        return json_encode($filesPath);
    }

    /**
     * @return string
     */
    protected function generatePath()
    {
        return $this->slug.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function generateFileName($file, string $path)
    {
        if (isset($this->options->preserveFileUploadName) && $this->options->preserveFileUploadName) {
            $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
            $filename_counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            while (Storage::disk(\Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()).(string) ($filename_counter++);
            }
        } else {
            $filename = Str::random(20);

            // Make sure the filename does not exist, if it does, just regenerate
            while (Storage::disk(\Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                $filename = Str::random(20);
            }
        }

        return $filename;
    }
}
