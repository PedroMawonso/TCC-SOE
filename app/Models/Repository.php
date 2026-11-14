<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
final class Repository {
    public static function all(string $table,?int $userId=null):array{$items=Database::read()[$table]??[];return $userId===null?$items:array_values(array_filter($items,fn($item)=>(int)($item['user_id']??0)===$userId));}
    public static function create(string $table,array $item):array{$data=Database::read();$item['id']=Database::nextId($data[$table]??[]);$data[$table][]=$item;Database::write($data);return $item;}
    public static function delete(string $table,int $id,?int $userId=null):void{$data=Database::read();$data[$table]=array_values(array_filter($data[$table]??[],fn($x)=>(int)$x['id']!==$id||($userId!==null&&(int)($x['user_id']??0)!==$userId)));Database::write($data);}
}