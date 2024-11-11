<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $data = [];
    protected $directory = null;

    protected function render($filename)
    {
        $this->setData();
        return view($this->getView($filename), $this->data);
    }

    private function setData()
    {
        $app = getApp();
        $this->data['app_name'] = $app->name;
    }

    private function getView($filename)
    {
        return !empty($this->directory) ? $this->directory . '.' . $filename : $filename;
    }
}
