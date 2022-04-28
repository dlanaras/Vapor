<?php
interface RepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function add($entry);
    public function update($entry);
}
?>