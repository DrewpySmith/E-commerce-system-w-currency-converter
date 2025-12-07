<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\EmployeeModel;

class EmployeesController extends Controller
{
    public function index()
    {
        $model = new EmployeeModel();
        $employees = $model->orderBy('created_at', 'DESC')->findAll();
        return view('admin/employees/index', ['employees' => $employees, 'title' => 'Employees']);
    }

    public function create()
    {
        helper(['form']);
        return view('admin/employees/create', ['title' => 'Create Employee']);
    }

    public function store()
    {
        $model = new EmployeeModel();
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'position' => 'required',
            'salary' => 'permit_empty|numeric'
        ];
        if ($this->validate($rules)) {
            $model->save([
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'position' => $this->request->getPost('position'),
                'salary' => $this->request->getPost('salary') ?: null,
            ]);
            return redirect()->to('/admin/employees');
        }
        return view('admin/employees/create', ['validation' => $this->validator, 'title' => 'Create Employee']);
    }

    public function edit($id)
    {
        helper(['form']);
        $model = new EmployeeModel();
        $employee = $model->find($id);
        if (!$employee) return redirect()->to('/admin/employees');
        return view('admin/employees/edit', ['employee' => $employee, 'title' => 'Edit Employee']);
    }

    public function update($id)
    {
        $model = new EmployeeModel();
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'position' => 'required',
            'salary' => 'permit_empty|numeric'
        ];
        if ($this->validate($rules)) {
            $model->update($id, [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'position' => $this->request->getPost('position'),
                'salary' => $this->request->getPost('salary') ?: null,
            ]);
            return redirect()->to('/admin/employees');
        }
        return $this->edit($id);
    }

    public function delete($id)
    {
        $model = new EmployeeModel();
        $model->delete($id);
        return redirect()->to('/admin/employees');
    }
}
