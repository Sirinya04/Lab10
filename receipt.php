<?php
require_once("database.php");
require_once("menu.php");
require_once("customer.php");

class Receipt extends DataMapper
{

    public const table = "receipt";
    public const pk = "receipt_id";
    public const fields = ['customer_id', 'menu_id'];
    public $data = [];
    public $is_new = false;

    public function __construct($data = null, $is_new = false)
    {
        parent::__construct(self::table, self::pk, self::fields);
        $this->data = $data;
        $this->is_new = $is_new;
    }

    public static function load($id)
    {
        $data = parent::select(
            self::table,
            [self::pk => ':id'],
            ['id' => $id]
        );
        return new self($data[0]);
    }

    public function get($id)
    {
        $data = parent::get($id);
        return new Receipt($data);
    }

    public function getMenu()
    {
        $menu = new Menu();
        $data = $menu->get($this->data['menu_id']);
        if ($data)
            return $data;
    }
    public function getCustomer()
    {
        $customer = new Customer();
        $data = $customer->get($this->data['customer_id']);
        if ($data)
            return $data;
    }

}