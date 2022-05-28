<?php
class ProductCategories extends Connection
{
    private $table = 'tbl_product_categories';
    public $pk = 'product_category_id';
    public $name = 'product_category';

    public function add()
    {
        $form = array(
            $this->name => $this->clean($this->inputs[$this->name])
        );
        return $this->insertIfNotExist($this->table, $form);
    }

    public function edit()
    {
        $form = array(
            $this->name => $this->clean($this->inputs[$this->name])
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : '';
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        return $result->fetch_assoc();
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->name];
    }
}
