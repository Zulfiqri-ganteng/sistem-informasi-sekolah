<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ErrorLogModel;

class ErrorLogController extends BaseController
{
    public function index()
    {
        $model = new ErrorLogModel();
        $data['logs'] = $model->orderBy('id', 'DESC')->findAll();

        return view('admin/error-log/index', $data);
    }

    public function detail($id)
    {
        $model = new ErrorLogModel();
        $data['log'] = $model->find($id);

        return view('admin/error-log/detail', $data);
    }
}
