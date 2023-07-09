<?php 
namespace App\Repositories;

interface RepositoryInterface{
    public function getAll();
    public function find($id);
    public function create($data = []);
    public function edit($data = [], $id);
    public function delete($id);
}