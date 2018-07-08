<?php
namespace asbamboo\framework\_test\fixtures\controller;

// use asbamboo\http\Stream;
// use asbamboo\http\Response;
use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\framework\Constant;
use asbamboo\framework\_test\fixtures\model\product\ProductEntity;
use asbamboo\database\Factory;

class Home extends ControllerAbstract
{
    public function index($p1, $p2)
    {
        
        $this->createProduct();
        $this->searchProduct();
        return $this->view([
            'p1' => $p1,
            'p2' => $p2,
        ]);
//         $Stream   = new Stream('php://temp', 'w+b');
//         $Stream->write("test kernel route. p1:{$p1}, p2:{$p2}");
//         return new Response($Stream);
    }
    
    public function createProduct()
    {
        /**
         * 
         * @var Factory $Db
         */
        $Db         = $this->Container->get(Constant::KERNEL_DB);
        $conn       = ['default','a','b'];
        $i          = array_rand($conn, 1);
        $Manager    = $Db->getManager($conn[$i]);
//         $Manager->getConnection()->exec("CREATE TABLE products(id INTEGER PRIMARY KEY, name)");
        
        $Product    = new ProductEntity();
        $Product->setName('test'.uniqid());
        $Manager->persist($Product);
//         $Manager->flush();
    }
    
    public function searchProduct()
    {
        /**
         *
         * @var Factory $Db
         */
        $Db         = $this->Container->get(Constant::KERNEL_DB);
        $conn       = ['default','a','b'];
        $i          = array_rand($conn, 1);
        $Manager    = $Db->getManager($conn[$i]);
        $list       = $Manager->getRepository(ProductEntity::class)->findAll();
//         var_dump($list);
//         exit;
    }
}