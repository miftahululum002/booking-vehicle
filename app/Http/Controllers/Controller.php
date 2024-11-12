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

    protected function renderDatatable($filename, $datatable)
    {
        $this->setData();
        return $datatable->render($this->getView($filename), $this->data);
    }

    private function setData()
    {
        $this->data['title'] = !empty($this->data['title']) ? $this->data['title'] : 'Index';
        $app = getApp();
        $this->data['app_name'] = $app->name;
        $author = getAuthor();
        $this->data['author'] = $author;
        $userLogin = getUserLogin();
        if ($userLogin) {
            $this->data['user_login'] = $userLogin;
        }
        $this->data['table'] = getTableId();
    }

    private function getView($filename)
    {
        return !empty($this->directory) ? $this->directory . '.' . $filename : $filename;
    }
}
