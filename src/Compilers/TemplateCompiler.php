<?php

namespace Dogado\Laroute\Compilers;

use Illuminate\Support\Str;

class TemplateCompiler implements CompilerInterface
{
    /**
     * Compile a template with given data.
     *
     * @param $template
     * @param $data
     *
     * @return string
     */
    public function compile($template, $data)
    {
        foreach ($data as $key => $value) {
            $key = Str::upper($key);

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            $template = preg_replace("#\\$$key\\$#i", $value, $template);
        }

        return $template;
    }
}
