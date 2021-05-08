<?php
use \LeanCloud\Query;
use \LeanCloud\Object;

class Category_model extends CI_Model {
	// 获取全部分类
	public function findAll() {
		// 1. 查询所有顶级分类
		$query = new Query("Category");
		$query->equalTo('parent', null);
		$categoris = $query->find();
		// 2. sub
		foreach ($categoris as $category) {
			$query->equalTo('parent', $category);
			$children = $query->find();
			// 不必使用转数组再动态添加成员属性，$category = $category->toJSON();object同样可以实现操作
			$category->children = $children;
			$result[] = $category;

		}
		return $result;
	}

	// 删除分类
	public function delete($objectId) {
		$category = Object::create('Category', $objectId);
		$category->destroy();
	}
}
?>